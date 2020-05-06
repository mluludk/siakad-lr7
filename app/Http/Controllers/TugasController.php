<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	use Siakad\Tugas;
	use Siakad\Http\Controllers\Controller;
	
	class TugasController extends Controller
	{
		use \Siakad\TapelTrait;
		protected $jenis = [
		1 => 'Upload File',
		2 => 'Essay',
		3 => 'Pilihan Ganda'
		];
		
		public function index2()
		{
			$user = \Auth::user();
			
			$jadwal = [];
			$mahasiswa_tugas = [];
			
			$kelas =  \Cache::get('kelas_' .$user -> authable_id, function() use($user){
				$kelas = \Siakad\Nilai::KelasKuliahMahasiswa($user -> authable_id) -> pluck('id') -> toArray();
				\Cache::put('kelas_' . $user -> authable_id, $kelas, 60);
				return $kelas;
			});
			
			if(count($kelas) >0)
			{
				$tugas_all = \Cache::get('tugas_all', function(){
					$ta = Tugas::all();
					\Cache::put('tugas_all', $ta, 60);
					return $ta;
				});
				
				foreach($tugas_all as $tg)
				{
					if(in_array($tg -> matkul_tapel_id, $kelas)) 
					$mahasiswa_tugas[] = [
					'tugas_id' => $tg -> id,
					'mahasiswa_id' => $user -> authable_id
					];
				}
				if(count($mahasiswa_tugas) > 0)
				\Siakad\MahasiswaTugas::insertIgnore($mahasiswa_tugas);				
			}
			
			$tugas = Tugas::tugasMahasiswa($user -> authable_id) -> with('perkuliahan.tim_dosen') -> get();
			
			$jenis = $this -> jenis;
			return view('mahasiswa.tugas.index2', compact('tugas', 'jenis', 'user'));
		}
		
		public function index()
		{
			$user = \Auth::user();
			$tugas = Tugas::daftarTugas(null) -> with('perkuliahan.tim_dosen') -> get();
			
			$jenis = $this -> jenis;
			return view('mahasiswa.tugas.index', compact('tugas', 'jenis', 'user'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create(Request $request)
		{
			$tapel_id = false !== $request -> get('tapel_id') ? $request -> get('tapel_id') : null;
			$matkul_tapel_id = false !== $request -> get('mt_id') ? $request -> get('mt_id') : null;
			$edit = false;
			
			$user = \Auth::user();
			$matkul = $this -> getMatkulList($user, $tapel_id, null);
			$jenis_nilai = $this -> getJenisNilaiList($matkul_tapel_id);
			
			$tapel = $user -> authable_id > 2 ? null : $this -> getTapelSelection('desc');
			$jenis = $this -> jenis;
			
			return view('mahasiswa.tugas.create', compact('tapel', 'matkul', 'jenis', 'jenis_nilai', 'edit', 'request'));
		}		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$input= $request -> except('_token', 'tapel_id', 'files');
			\Cache::forget('tugas_all');
			$tugas = Tugas::create($input);			
			return Redirect::route('mahasiswa.tugas.detail.index', $tugas -> id) -> with('message', 'Data Tugas Mahasiswa berhasil disimpan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$edit = true;
			$tugas = Tugas::find($id);
			
			$tugas = Tugas::find($id);		
			if($tugas && $this -> authorized($tugas -> matkul_tapel_id)) 
			{
				$perkuliahan = \Siakad\MatkulTapel::getDataMataKuliah($tugas -> matkul_tapel_id) -> first();
				if($perkuliahan -> count()) $matkul_tapel_id = $perkuliahan -> matkul_tapel_id;
				$tugas -> tapel_id = $perkuliahan -> tapel_id;
				
				$jenis_nilai = $this -> getJenisNilaiList($matkul_tapel_id);
				
				$user = \Auth::user();
				$matkul = $tapel = [];
				$jenis = $this -> jenis;
				
				return view('mahasiswa.tugas.edit', compact('tugas', 'matkul', 'tapel', 'jenis', 'jenis_nilai', 'edit'));
			}
			
			return Redirect::route('mahasiswa.tugas.index') -> withErrors(['Maaf, anda tidak berhak merubah Data Tugas Mahasiswa ini.']);
		}
		
		/**
			* Update the specified resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $id)
		{
			$input= $request -> except('_token', 'tapel_id', 'files');
			\Cache::forget('tugas_all');
			Tugas::find($id) -> update($input);			
			return Redirect::route('mahasiswa.tugas.index') -> with('message', 'Data Tugas Mahasiswa berhasil diperbarui.');
		}
		
		public function publish(Tugas $tugas, $type='y')
		{
			if($type == 'y') //check Tugas detail, apakah sudah ada?
			{
				if($tugas -> detail -> count() < 1)
				return Redirect::back() -> with('warning', 'Anda belum meng-upload File / Membuat Pertanyaan. Tugas belum bisa di-Publikasikan.');
			}
			$tugas -> update(['published' => $type]);			
			return Redirect::route('mahasiswa.tugas.index') -> with('message', 'Data Tugas Mahasiswa berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			$tugas = Tugas::find($id);		
			if($tugas && $this -> authorized($tugas -> matkul_tapel_id)) 
			{
				$tugas -> delete();			
				return Redirect::route('mahasiswa.tugas.index') -> with('success', 'Data Tugas Mahasiswa berhasil dihapus.');
			}
			return Redirect::route('mahasiswa.tugas.index') -> withErrors(['Maaf, anda tidak berhak menghapus Tugas Mahasiswa ini.']);
		}
		
		
		//////////////////////PRIVATE PROPERTY///////////////////////////////////////////PRIVATE PROPERTY/////////////////////////PRIVATE PROPERTY////////////////////
		private function authorized($matkul_tapel_id)
		{
			$user = \Auth::user();
			if($user -> role_id <= 2) return true;
			
			$authorized = false;
			$perkuliahan = \Siakad\MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			$dosen = $perkuliahan -> tim_dosen;
			
			foreach($dosen as $d) if($d -> id == $user -> authable_id) $authorized = true;
			
			return $authorized;
		}
		
		private function getJenisNilaiList($matkul_tapel_id)
		{
			$jenis['-'] = '-- Jenis Nilai --';
			$tmp = \Siakad\MatkulTapel::join('jenis_nilai', 'jenis_nilai.prodi_id', '=', 'matkul_tapel.prodi_id') 
			-> where('matkul_tapel.id', $matkul_tapel_id)
			-> where('jenis_nilai.aktif', 'y')
			-> select('jenis_nilai.id', 'jenis_nilai.nama', 'jenis_nilai.prodi_id', 'bobot')
			-> get();
			if($tmp -> count())
			{
				$jenis[0] = 'Akhir (100%)';				
				foreach($tmp as $t) $jenis[$t -> id] = $t -> nama . ' (' . $t -> bobot . '%)';				
			}
			return $jenis;
		}
		private function getMatkulList($user, $tapel_id, $prodi_id)
		{
			$matkul = [];
			
			if($user -> authable_id > 2) $tapel_id = \Siakad\Tapel::whereAktif('y') -> first(['id']) -> id;
			
			$data = \Siakad\MatkulTapel::kelasKuliah($tapel_id, $prodi_id) -> with('tim_dosen') -> get();
			foreach($data as $mk) 
			{
				$dosen = [];
				$allowed = false;
				foreach($mk -> tim_dosen as $d)
				{
					if($user -> authable_id <= 2 || $d -> id == $user -> authable_id) $allowed = true;
					$dosen[] = $d -> gelar_depan . ' ' . $d -> nama . ' ' . $d -> gelar_belakang;
				}
				//skip not dosen
				if($allowed === false) continue;
				
				if(count($dosen)) $dosen = implode(', ', $dosen); else $dosen = 'belum ada Dosen Pengampu';
				
				$matkul[$mk -> mtid] = $mk -> prodi .  ' - '  . $mk -> program . ' Kelas ' . $mk -> kelas . ' - ' . 
				$mk -> kode . ' - ' . $mk -> matkul .' - ' . $dosen . ' - Smt ' . $mk -> semester . ' - '. $mk -> sks . ' SKS';
			}	
			
			return $matkul;
		}
	}
