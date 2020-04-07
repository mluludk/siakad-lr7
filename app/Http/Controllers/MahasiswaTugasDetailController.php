<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Tugas;
	use Siakad\MahasiswaTugas;
	use Siakad\MahasiswaTugasDetail;
	use Siakad\TugasDetail;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class MahasiswaTugasDetailController extends Controller
	{
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
		
		//MHS
		public function edit($tugas_id)
		{
			$edit = true;
			$user = \Auth::user();
			$tugas = Tugas::daftarTugas($tugas_id, $user -> authable_id) -> where('tugas.published', 'y') -> first();
			if(!$tugas) abort(404);
			
			$tugas = Tugas::daftarTugas($tugas_id, $user -> authable_id) -> where('tugas.published', 'y') -> first();
			$detail = TugasDetail::index($tugas_id, $user -> authable_id) -> get();
			
			$jenis = $this -> jenis;
			
			return view('mahasiswa.tugas.detail.index2', compact('tugas', 'jenis', 'detail', 'user', 'edit'));
		}
		
		public function index($tugas_id)
		{
			$edit = false;
			$user = \Auth::user();
			$tugas = Tugas::daftarTugas($tugas_id, $user -> authable_id) -> where('tugas.published', 'y') -> first();
			
			if(!$tugas) abort(404);
			
			//cek waktu
			$now = strtotime(date('d-m-Y H:i:s'));
			
			if($now < strtotime($tugas -> tanggal . ' 00:00:00')) return Redirect::back() 
			-> with('warning', 'Tugas baru bisa dikerjakan mulai Tanggal ' . $tugas -> tanggal);
			if($now > strtotime($tugas -> batas . ' 23:59:59'))  return Redirect::back() 
			-> withErrors(['TIMEOUT' => 'Waktu pengerjaan Tugas sudah habiss. Hubungi Dosen Pengampu Mata Kuliah yang bersangkutan.']);
			
			//cek Tanggungan
			//SPP only
			$privilege = false;
			$tapel = \Siakad\Tapel::whereAktif('y') -> first();
			$tagihan = \Siakad\Tagihan::rincian($user -> authable_id, 1) -> get();
			
			if(!$tagihan && $user -> role_id == 512) return Redirect::back() -> withErrors(['Tagihan Biaya mahasiswa tidak ditemukan.']);
			$tsemester = $bsemester  = $psemester = $tanggungan = $bayar = 0;
			foreach($tagihan as $t)
			{
				if($t -> privilege == 'y')
				{
					$privilege = true;
					break;
				}
				if($tapel -> nama2 == $t -> tapel) 
				{
					$tsemester = $t -> jumlah;
					$bsemester =  $t -> bayar;
				}
				$tanggungan +=  $t -> jumlah - $t -> bayar;
				$bayar +=  $t -> bayar;
			}
			
			$prosentase = (int) $bayar == 0 || (int) $tanggungan == 0 ? 0 : ((int) $bayar / (int) $tanggungan) * 100;
			$psemester = (int) $bsemester == 0 ? 0 : ((int) $bsemester / (int) $tsemester) * 100;
			
			$jenis_nilai = strtoupper($tugas -> jnilai);
			
			//presentase tanggungan
			$data = $user -> authable;
			if($data -> statusMhs != 1) return Redirect::back() -> withErrors(['ERROR' => 'Maaf, Status Mahasiswa Tidak Aktif. Hubungi Bagian Administrasi.']);
			
			$setup_biaya = \Siakad\BiayaKuliah::where('jenis_biaya_id', 1)
			-> where('angkatan', $data -> angkatan)
			-> where('prodi_id', $data -> prodi_id)
			-> where('kelas_id', $data -> kelasMhs)
			-> where('jenisPembayaran', $data -> jenisPembayaran)
			-> first();
			
			if(!$setup_biaya) return Redirect::back() -> withErrors(['ERROR' => 'Maaf, Biaya Kuliah belum diatur. Hubungi Bagian Administrasi/Keuangan.']);
			
			if( $jenis_nilai == '__FINAL__' or $jenis_nilai == 'UAS')
			{
				if(($psemester < $setup_biaya -> uas && $tsemester > 0 && $privilege == false)) 
				{
					return Redirect::back() 
					-> withErrors(['ERROR' => 'Maaf, anda masih mempunyai tanggungan pembayaran semester ini. Hubungi Bagian Keuangan.']);
				}
			}
			elseif($jenis_nilai == 'UTS')
			{
				if(($psemester < $setup_biaya -> uts && $tsemester > 0 && $privilege == false)) 
				{
					return Redirect::back() 
					-> withErrors(['ERROR' => 'Maaf, anda masih mempunyai tanggungan pembayaran semester ini. Hubungi Bagian Keuangan.']);
				}
			}
			
			$perkuliahan = \Siakad\MatkulTapel::getDataMataKuliah($tugas -> matkul_tapel_id) -> first();
			
			//SEED
			$mtd = $detail = [];
			$tdetail = TugasDetail::where('tugas_id', $tugas_id) -> get();
			
			if($tdetail)
			{
				foreach($tdetail as $td)
				{
					$mtd[] = [
					'mahasiswa_id' => $user -> authable_id,
					'tugas_detail_id' => $td -> id
					];
				}
				MahasiswaTugasDetail::insertIgnore($mtd);
				
				$detail = TugasDetail::index($tugas_id, $user -> authable_id) -> get();
			}
			
			$jenis = $this -> jenis;
			return view('mahasiswa.tugas.detail.index2', compact('tugas', 'jenis', 'detail', 'user', 'edit'));
		}
		
		public function store(Request $request, $tugas_id)
		{
			$user = \Auth::user();
			
			$input = $request -> all();
			
			$mttd = [];
			$waktu = date('Y-m-d H:i:s');
			
			if($input['jenis_tugas'] == 1)
			{
				$saved = [];
				foreach($input['tugas_detail'] as $k => $v)
				{
					if($v !== null)
					{
						$validator = \Validator::make($input, ['tugas_detail.'. $k => 'mimes:pdf,doc,docx']);
						if($validator -> fails())
						{
							return Redirect::back() -> withErrors(['FILE_TYPE_NOT_ALLOWED' => 'File yang diperbolehkan adalah: pdf,doc,docx']);	
						}
						else
						{
							$date = date('Y/m/d/');
							$storage = \Storage::disk('files');
							$file_name = $date . $v->getClientOriginalName();
							$result = $storage -> put($file_name, \File::get($v));
							
							if(!$result)
							{
								return Redirect::back() -> withErrors(['CANNOT_SAVE_FILE' => 'TIdak dapat menyimpan File']);		
							}
							$saved[$k] = $file_name;
						}
					}
				}
				
				$input['tugas_detail'] = $saved;
			}
			
			//cek
			$cdetail = TugasDetail::where('tugas_id', $tugas_id) -> count();
			if($cdetail != count($input['tugas_detail'])) return Redirect::back() 
			-> withErrors(['ITEM_COUNT_DID_NOT_MATCH' => 'Terjadi kesalahan. Pastikan semua pertanyaan sudah dijawab.']);
			
			//MahasiswaTugas
			$mtugas = MahasiswaTugas::where('tugas_id', $tugas_id)
			-> where('mahasiswa_id', $user -> authable_id) -> first();
			
			//build query
			foreach($input['tugas_detail'] as $k => $v)
			{
				if($v == null or $v == '' or !isset($v) or empty($v)) return Redirect::back() 
				-> withErrors(['ITEM_COUNT_DID_NOT_MATCH' => 'Terjadi kesalahan. Pastikan semua pertanyaan sudah dijawab.']);
				
				MahasiswaTugasDetail::where('mahasiswa_id', $user -> authable_id) 
				-> where('tugas_detail_id', $k)
				-> update(['jawaban' => $v]);
			}
			
			MahasiswaTugas::find($mtugas -> id) -> update(['status' => 1, 'waktu' => $waktu]);
			
			
			return Redirect::route('mahasiswa.tugas.index2', $tugas_id) -> with('message', 'Data telah disimpan');
		}
		
		public function get($tugas_id, $mahasiswa_id, $td_id)
		{
			$tugas = Tugas::daftarTugas($tugas_id) -> first();
			if($tugas -> jenis_tugas == 1)
			{
				$mtd = MahasiswaTugasDetail::where('mahasiswa_id', $mahasiswa_id) -> where('tugas_detail_id', $td_id) -> first();
				
				$storage = \Storage::disk('files');
				if(!$storage -> exists($mtd -> jawaban)) return Redirect::back() -> withErrors(['FILE_NOT_FOUND' => 'Berkas tidak ditemukan']);
				
				$file = $storage -> get($mtd -> jawaban);
				$mime = $storage -> mimeType($mtd -> jawaban);
				$response = \Response::make($file, 200);
				$response -> header('Content-type', $mime);
				$response -> header('Content-disposition: attachment;filename="', substr($mtd -> jawaban, 11, strlen($mtd -> jawaban)) . '"');
				return $response;
			}
			else
			return Redirect::back() -> withErrors(['DEFAULT_ERROR' => 'Data tidak ditemukan']);
		}
	}
