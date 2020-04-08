<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	use Siakad\Nilai;
	use Siakad\Tugas;
	use Siakad\TugasDetail;
	use Siakad\Mahasiswa;
	use Siakad\MahasiswaTugas;
	
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class MahasiswaTugasController extends Controller
	{
		use \Siakad\SkalaTrait;
		
		protected $jenis = [
		1 => 'Upload File',
		2 => 'Essay',
		3 => 'Pilihan Ganda'
		];
		protected $status = [
		'Belum',
		'Dikirim',
		'Diperiksa',
		'Perbaikan',
		'Selesai'
		];
		
		public function nilai(Request $request, $tugas_id, $mahasiswa_id)
		{
			$input = $request -> all();
			$skala = $this -> skala($input['prodi_id']);
			
			$huruf = $this -> toHuruf($skala, $input['nilai']);
			
			if(MahasiswaTugas::where('tugas_id', $tugas_id) 
			-> where('mahasiswa_id', $mahasiswa_id) 
			-> update(['nilai' => $huruf, 'angka' => $input['nilai'], 'status' => 4]))
			{
				if($input['jenis_nilai_id'] == 0)
				{
					Nilai::where('matkul_tapel_id', $input['matkul_tapel_id']) 
					-> where('mahasiswa_id', $mahasiswa_id)
					-> delete();
					
					Nilai::create([
					'mahasiswa_id' => $mahasiswa_id,
					'matkul_tapel_id' => $input['matkul_tapel_id'],
					'jenis_nilai_id' => 0,
					'nilai' => $huruf
					]);
					
					Nilai::create([
					'mahasiswa_id' => $mahasiswa_id,
					'matkul_tapel_id' => $input['matkul_tapel_id'],
					'jenis_nilai_id' => 1,
					'nilai' => $input['nilai']
					]);
				}
				else
				{
					//Get jenis & bobot nilai
					$mahasiswa = Mahasiswa::find($mahasiswa_id);
					$tmp = \Siakad\JenisNilai::where('aktif', 'y') -> where('prodi_id', $mahasiswa -> prodi_id) -> get(['id', 'nama', 'bobot']);
					foreach($tmp as $t) $jenis[$t -> id] = $t -> bobot; 
					
					
					//Get existing Nilai
					$nilai = Nilai::where('mahasiswa_id', $mahasiswa_id) 
					-> where('matkul_tapel_id', $input['matkul_tapel_id']) 
					-> where('jenis_nilai_id', '>', 1) 
					-> get();
					foreach($nilai as $n) $jenis_nilai[$n -> jenis_nilai_id] = $n -> nilai;
					
					//map jenis to nilai
					foreach($jenis as $k => $v) $nilai_per_jenis[$k] = isset($jenis_nilai[$k]) ? $jenis_nilai[$k] : '';
					
					//set nilai
					$nilai_per_jenis[$input['jenis_nilai_id']] = $input['nilai'];
					
					
					//hitung nilai akhir
					$akhir = 0;
					foreach($jenis as $k => $v)
					{
						$n = ($nilai_per_jenis[$k] == '' or !isset($nilai_per_jenis[$k])) ? 0 : intval($nilai_per_jenis[$k]);
						$akhir += $n * $v / 100;
					}
					
					//set nilai akhir
					$nilai_per_jenis[0] = $this -> toHuruf($skala, $akhir);
					$nilai_per_jenis[1] = $akhir;
					
					foreach($nilai_per_jenis as $id => $nil)
					{
						if(
						Nilai::where('matkul_tapel_id', $input['matkul_tapel_id']) 
						-> where('mahasiswa_id', $mahasiswa_id) 
						-> where('jenis_nilai_id', $id)
						-> exists()
						)
						{
							Nilai::where('matkul_tapel_id', $input['matkul_tapel_id']) 
							-> where('mahasiswa_id', $mahasiswa_id) 
							-> where('jenis_nilai_id', $id) 
							-> update(['nilai' => $nil]);
						}
						else
						{
							Nilai::create(
							[
							'mahasiswa_id' => $mahasiswa_id,
							'matkul_tapel_id' => $input['matkul_tapel_id'],
							'jenis_nilai_id' => $id,
							'nilai' => $nil
							]
							);
						}
					}
				}				
			}
			
			return Redirect::route('mahasiswa.tugas.hasil.index', [$tugas_id, $mahasiswa_id]) -> with('message', 'Nilai berhasil disimpan.');
		}
		
		public function index($tugas_id)
		{
			$tugas = Tugas::daftarTugas($tugas_id) -> first();
			$hasil = MahasiswaTugas::hasil($tugas_id) -> get();
			$jenis = $this -> jenis;
			return view('mahasiswa.tugas.hasil.index', compact('tugas', 'jenis', 'hasil'));
		}
		
		public function detail($tugas_id, $mahasiswa_id)
		{
			$tugas = Tugas::find($tugas_id);
			$perkuliahan = \Siakad\MatkulTapel::getDataMataKuliah($tugas -> matkul_tapel_id) -> first();
			
			$skala = $this -> skala($perkuliahan -> prodi_id);
			
			$mahasiswa = Mahasiswa::find($mahasiswa_id);
			$hasil_detail = TugasDetail::index($tugas_id, $mahasiswa_id) -> get();
			
			$mt = MahasiswaTugas::where('tugas_id', $tugas_id) 
			-> where('mahasiswa_id', $mahasiswa_id);
			
			$mahasiswa_tugas = $mt -> first();
			//update status
			$mt -> update(['status' => 2]);
			
			$jenis = $this -> jenis;
			return view('mahasiswa.tugas.hasil.detail', compact('tugas', 'jenis', 'hasil_detail', 'tugas', 'mahasiswa', 'skala', 'perkuliahan', 'mahasiswa_tugas'));
		}
		
		public function setStatus($tugas_id, $mahasiswa_id, $status=4)
		{
			$hasil = MahasiswaTugas::where('tugas_id', $tugas_id) 
			-> where('mahasiswa_id', $mahasiswa_id) 
			-> update(['status' => $status]);
			
			if($hasil) return Redirect::back() -> with('message', 'Status berhasil diubah.');
			
			return Redirect::back() -> withErrors(['ERROR' => 'Ubah Status gagal.']);
		}
	}
