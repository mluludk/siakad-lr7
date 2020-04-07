<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Mahasiswa;
	use Siakad\MahasiswaJurnal;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class MahasiswaJurnalController extends Controller
	{		
		//use \Siakad\DataExchangeTrait;	
		
		protected $level = [1 => 'Nasional', 2 => 'Internasional'];
		
        public function daftar($mahasiswa_id)
        {
			$auth = \Auth::user();
			
			if($auth -> role_id == 512) $mahasiswa = $auth-> authable;			
			else $mahasiswa = Mahasiswa::find($mahasiswa_id);
			
			if(!$mahasiswa) abort(404);
			
			$jurnal = MahasiswaJurnal::jurnal($mahasiswa -> id) -> get();
			$level = $this-> level;
			
			return view('mahasiswa.jurnal.daftar', compact('mahasiswa', 'jurnal', 'auth', 'level'));
		}
		
		public function index()
		{
			$jurnal = MahasiswaJurnal::jurnal() -> paginate(30);
			return view('mahasiswa.jurnal.index', compact('jurnal'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create($mahasiswa_id)
		{
			$auth = \Auth::user();
			
			if($auth -> role_id == 512) $mahasiswa = $auth-> authable;			
			else $mahasiswa = Mahasiswa::find($mahasiswa_id);
			
			if(!$mahasiswa) abort(404);
			$level = $this-> level;
			
			return view('mahasiswa.jurnal.create', compact('mahasiswa', 'auth', 'level'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request, $mahasiswa_id)
		{
			$input = $request -> all();
			$input['mahasiswa_id'] = $mahasiswa_id;
			
			MahasiswaJurnal::create($input);			
			return Redirect::route('mahasiswa.jurnal.daftar', $mahasiswa_id) -> with('success', 'Data Jurnal berhasil disimpan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($jurnal_id)
		{
			$auth = \Auth::user();
			$jurnal = MahasiswaJurnal::find($jurnal_id);
			
			if($auth -> role_id == 512)
			{
				if($jurnal -> mahasiswa_id != $auth -> authable_id) abort(401);
				$mahasiswa = $auth -> authable;
			}	
			else
			{
				$mahasiswa = Mahasiswa::find($jurnal -> mahasiswa_id);
			}
			$level = $this-> level;
			
			return view('mahasiswa.jurnal.edit', compact('jurnal', 'auth', 'mahasiswa', 'level'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $jurnal_id)
		{
			$input = $request -> except('_method');
			$jurnal = MahasiswaJurnal::find($jurnal_id);					
			$jurnal -> update($input);					
			return Redirect::route('mahasiswa.jurnal.daftar', $jurnal -> mahasiswa_id) -> with('success', 'Data Jurnal berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($jurnal_id)
		{
			$auth = \Auth::user();
			$jurnal = MahasiswaJurnal::find($jurnal_id);
			
			if($auth -> role_id == 512)
			{
				if($jurnal -> mahasiswa_id != $auth -> authable_id) abort(401);
			}	
			
			$jurnal -> delete();
			
			return Redirect::route('mahasiswa.jurnal.daftar', $jurnal -> mahasiswa_id) -> with('success', 'Data Jurnal Mahasiswa berhasil dihapus.');
		}
		
		public function export()
		{
			$jurnals = MahasiswaJurnal::jurnal() -> get();
			$data = [];
			foreach($jurnals as $j) 
			{
				$level = $this-> level[$j-> level_jurnal];
				$rdata [] = [
				$j -> NIP,  
				$j -> NIDN,  
				$j -> NIK,  
				str_replace("'", '`', $j -> nama), 
				str_replace("'", '`', $j -> judul), 
				$level, 
				str_replace("'", '`', $j -> penerbit), 
				$j -> isbn, 
				$j -> tahun_terbit
				];
			}
			
			$title = 'Form PTKI Jurnal Mahasiswa';
			// $this -> toXlsx(str_slug($title), $title, 'mahasiswa.jurnal.export', $rdata);
			return \Excel::download(new \Siakad\Exports\DataExport('mahasiswa.jurnal.export', $rdata), str_slug($title) . '.xlsx');
		}
	}
