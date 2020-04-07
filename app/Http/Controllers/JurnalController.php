<?php
	
	namespace Siakad\Http\Controllers;
	use Redirect;
	
	use Illuminate\Http\Request;
	
	
	use Siakad\Jurnal;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class JurnalController extends Controller
	{
		use \Siakad\FileEntryTrait;
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index($matkul_tapel_id)
		{
			$data = \Siakad\MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			if($data -> dosen_id != \Auth::user() -> authable -> id) abort(401);
			$anggota = \Siakad\Nilai::getNilaiAkhirMahasiswa($matkul_tapel_id) -> get();
			$jurnals = Jurnal::with('ruang') -> where('matkul_tapel_id', $matkul_tapel_id) -> get();
			return view('matkul.tapel.jurnal.index', compact('data', 'jurnals', 'anggota', 'matkul_tapel_id'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create($matkul_tapel_id)
		{
			$data = \Siakad\MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			$anggota = \Siakad\Nilai::getNilaiAkhirMahasiswa($matkul_tapel_id) -> get();
			$ruang = \Siakad\Ruang::pluck('nama', 'id');
			
			if($anggota -> count() < 1) return Redirect::back() -> withErrors(['error' => 'Belum ada mahasiswa yang mengambil Mata Kuliah ini']);
			
			return view('matkul.tapel.jurnal.create', compact('data', 'ruang', 'anggota', 'matkul_tapel_id'));
		}
		
		/* 		
			private function uploadFile($input)
			{
			$file = $input['file'];
			
			$validator = \Validator::make($input, ['file' => 'mimes:pdf,doc,docx,zip,rar,7z,gz,tar']);
			if($validator -> fails())
			{
			return ['success' => false, 'error' => 'File yang diperbolehkan adalah: pdf, doc, docx, zip, rar, 7z, gz, tar'];	
			}
			else
			{
			$date = date('Y/m/d/');
			
			$filename = str_random(7) . '.' . $file->getClientOriginalExtension();				
			$storage = \Storage::disk('files');
			$result = $storage -> put($date . $filename, \File::get($file));
			
			if($result)
			{
			return ['success' => true, 'filename' => $date . $filename];
			}
			return ['success' => false, 'error' => 'An error occured while trying to save data.'];		
			}
			} 
		*/
		
		public function uploadFile()
		{
			$input = $request -> all();
			$input['akses'] = ["512", "2", "128"];
			$input['tipe'] = '5';
			$result = $this -> upload($input);
			
			if(!$result['success'])
			{
				return ['success' => false, 'error' => 'File yang diperbolehkan adalah: pdf, doc, docx, zip, rar, 7z, gz, tar'];	
			}
			else
			{
				return ['success' => true, 'id' => $result['id'], 'filename' => $result['filename']];
			}
			return ['success' => false, 'error' => 'An error occured while trying to save data.'];		
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request, $matkul_tapel_id)
		{
			$input = $request -> all();
			if(isset($input['file']) and $input['file'] != '')
			{
				$saving = $this -> uploadFIle($input);
				if(!$saving['success']) return redirect() -> back() -> withInput() -> withErrors($saving['error']);
				$input['file'] = $saving['id'];
			}
			$input['matkul_tapel_id'] = $matkul_tapel_id;
			Jurnal::create($input);
			
			return Redirect::route('matkul.tapel.jurnal.index', $matkul_tapel_id);
		}
		
		/**
			* Display the specified resource.
			*
			* @param  int  $matkul_tapel_id
			* @return \Illuminate\Http\Response
		*/
		public function show($matkul_tapel_id, $jurnal_id)
		{
			$data = \Siakad\MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			$anggota = \Siakad\Nilai::getNilaiAkhirMahasiswa($matkul_tapel_id) -> get();
			$jurnal = Jurnal::find($jurnal_id);
			return view('matkul.tapel.jurnal.show', compact('data', 'ruang', 'anggota', 'matkul_tapel_id', 'jurnal'));
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $matkul_tapel_id
			* @return \Illuminate\Http\Response
		*/
		public function edit($matkul_tapel_id, $jurnal_id)
		{
			$data = \Siakad\MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			$anggota = \Siakad\Nilai::getNilaiAkhirMahasiswa($matkul_tapel_id) -> get();
			$ruang = \Siakad\Ruang::pluck('nama', 'id');
			$jurnal = Jurnal::find($jurnal_id);
			return view('matkul.tapel.jurnal.edit', compact('data', 'ruang', 'anggota', 'matkul_tapel_id', 'jurnal'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @param  int  $matkul_tapel_id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $matkul_tapel_id, $jurnal_id)
		{
			$input = $request -> all();
			if(isset($input['file']) and $input['file'] != '')
			{
				$saving = $this -> uploadFIle($input);
				if(!$saving['success']) return redirect() -> back() -> withInput() -> withErrors($saving['error']);
				$input['file'] = $saving['id'];
			}
			$input['matkul_tapel_id'] = $matkul_tapel_id;
			$jurnal = Jurnal::find($jurnal_id);
			$jurnal -> update($input);
			
			return Redirect::route('matkul.tapel.jurnal.index', $matkul_tapel_id) -> with('message', 'Jurnal berhasil di-update');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $matkul_tapel_id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($matkul_tapel_id, $jurnal_id)
		{
			//
		}
		
		public function printJurnal($matkul_tapel_id)
		{
			$data = \Siakad\MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			
			$jurnals = Jurnal::with('ruang') -> where('matkul_tapel_id', $matkul_tapel_id) -> get();
			return view('matkul.tapel.jurnal.printjurnal', compact('data', 'jurnals'));
		}
		public function printFormJurnal($matkul_tapel_id)
		{
			$data = \Siakad\MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			return view('matkul.tapel.jurnal.printjurnalkosong', compact('data'));
		}
	}
