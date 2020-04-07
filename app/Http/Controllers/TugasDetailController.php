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
	
	class TugasDetailController extends Controller
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
		
		//ADMIN
		public function index($tugas_id)
		{
			$user = \Auth::user();
			
			$tugas = Tugas::daftarTugas($tugas_id) -> first();
			$perkuliahan = \Siakad\MatkulTapel::getDataMataKuliah($tugas -> matkul_tapel_id) -> first();
			if($user -> role_id > 2) if($perkuliahan -> dosen_id !== $user -> authable_id) abort(404);
			
			$detail = TugasDetail::where('tugas_id', $tugas_id) -> with('jawaban') -> get();
			$jenis = $this -> jenis;
			
			$dijawab = false;
			return view('mahasiswa.tugas.detail.index', compact('tugas', 'jenis', 'detail', 'dijawab'));
		}
		
		public function store(Request $request, $tugas_id)
		{
			$input = $request -> all();
			
			if($input['jenis_tugas'] == 1)
			{
				$validator = \Validator::make($input, ['pertanyaan' => 'mimes:pdf,doc,docx']);
				if($validator -> fails())
				{
					return Redirect::back() -> withErrors(['FILE_TYPE_NOT_ALLOWED' => 'File yang diperbolehkan adalah: pdf,doc,docx']);	
				}
				else
				{
					$date = date('Y/m/d/');
					$storage = \Storage::disk('files');
					$uploadedFile = $request -> file('pertanyaan');
					$file_name = $date . $uploadedFile->getClientOriginalName();
					$result = $storage -> put($file_name, \File::get($uploadedFile));
					
					if(!$result)
					{
						return Redirect::back() -> withErrors(['CANNOT_SAVE_FILE' => 'TIdak dapat menyimpan File']);		
					}
				}
				
				$detail_tugas = [
				'tugas_id' => $tugas_id,
				'pertanyaan' => $file_name
				];
			}
			elseif($input['jenis_tugas'] == 2)
			{
				$detail_tugas = [
				'tugas_id' => $tugas_id,
				'pertanyaan' => $input['pertanyaan']
				];
			}
			elseif($input['jenis_tugas'] == 3)
			{
				if(count($input['pilihan']) < 2) return Redirect::route('mahasiswa.tugas.detail.index', $tugas_id) -> with('warning', 'Pilihan Jawaban harus lebih dari 1');
				
				$pilihan = [];
				$kunci = null;
				foreach($input['pilihan'] as $p) 
				{
					$rand = mt_rand(11, 999);
					if($kunci == null) $kunci = $rand;
					$pilihan[$rand] = $p;
				}
				
				$detail_tugas = [
				'tugas_id' => $tugas_id,
				'pertanyaan' => $input['pertanyaan'],
				'pilihan' => json_encode($pilihan),
				'kunci' => $kunci
				];
			}
			
			
			TugasDetail::create($detail_tugas);
			return Redirect::route('mahasiswa.tugas.detail.index', $tugas_id) -> with('message', 'Data telah disimpan');
		}
		
		public function get($tugas_id, $tugas_detail_id)
		{
			$tugas = Tugas::daftarTugas($tugas_id) -> first();
			if($tugas -> jenis_tugas == 1)
			{
				$tugas_detail = TugasDetail::find($tugas_detail_id);
				
				$storage = \Storage::disk('files');
				if(!$storage -> exists($tugas_detail -> pertanyaan)) abort(404);
				
				$file = $storage -> get($tugas_detail -> pertanyaan);
				$mime = $storage -> mimeType($tugas_detail -> pertanyaan);
				
				$response = \Response::make($file, 200);
				$response -> header('Content-type', $mime);
				$response -> header('Content-disposition: attachment;filename=', substr($tugas_detail -> pertanyaan, 11, strlen($tugas_detail -> pertanyaan)));
				return $response;
			}
		}
		
		public function edit($tugas_id, $tugas_detail_id)
		{
			$tugas_detail = TugasDetail::find($tugas_detail_id);
			$tugas = Tugas::daftarTugas($tugas_id) -> first();
			
			$jenis = $this -> jenis;
			
			return view('mahasiswa.tugas.detail.edit', compact('tugas', 'tugas_detail', 'jenis'));
		}
		
		public function update(Request $request, $tugas_id, $tugas_detail_id)
		{
			$input = $request -> only('pertanyaan', 'pilihan');
			if(is_array($input['pilihan'])) $input['pilihan'] = json_encode($input['pilihan']);
			
			$tugas_detail = TugasDetail::find($tugas_detail_id) -> update($input);
			
			return Redirect::route('mahasiswa.tugas.detail.index', $tugas_id) -> with('message', 'Perubahan data telah disimpan');
		}
		
		public function destroy($tugas_id, $tugas_detail_id)
		{
			$tugas_detail = TugasDetail::find($tugas_detail_id) -> delete();
			
			return Redirect::route('mahasiswa.tugas.detail.index', $tugas_id) -> with('message', 'Data telah dihapus');
		}
	}
