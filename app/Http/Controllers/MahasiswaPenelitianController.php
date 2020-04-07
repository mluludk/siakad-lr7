<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Mahasiswa;
	use Siakad\MahasiswaPenelitian;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class MahasiswaPenelitianController extends Controller
	{		
		//use \Siakad\DataExchangeTrait;	
		
		protected $jenis = [1 => 'Pribadi', 2 => 'Kelompok'];
		
        public function daftar($mahasiswa_id)
        {
			$auth = \Auth::user();
			
			if($auth -> role_id == 512) $mahasiswa = $auth-> authable;			
			else $mahasiswa = Mahasiswa::find($mahasiswa_id);
			
			if(!$mahasiswa) abort(404);
			
			$penelitian = MahasiswaPenelitian::penelitian($mahasiswa -> id) -> get();
			$jenis = $this-> jenis;
			
			return view('mahasiswa.penelitian.daftar', compact('mahasiswa', 'penelitian', 'auth', 'jenis'));
		}
		
		public function index()
		{
			$penelitian = MahasiswaPenelitian::penelitian() -> paginate(30);
			return view('mahasiswa.penelitian.index', compact('penelitian'));
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
			$jenis = $this-> jenis;
			
			return view('mahasiswa.penelitian.create', compact('mahasiswa', 'auth', 'jenis'));
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
			
			MahasiswaPenelitian::create($input);			
			return Redirect::route('mahasiswa.penelitian.daftar', $mahasiswa_id) -> with('success', 'Data Penelitian berhasil disimpan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($penelitian_id)
		{
			$auth = \Auth::user();
			$penelitian = MahasiswaPenelitian::find($penelitian_id);
			
			if($auth -> role_id == 512)
			{
				if($penelitian -> mahasiswa_id != $auth -> authable_id) abort(401);
				$mahasiswa = $auth -> authable;
			}	
			else
			{
				$mahasiswa = Mahasiswa::find($penelitian -> mahasiswa_id);
			}
			$jenis = $this-> jenis;
			
			return view('mahasiswa.penelitian.edit', compact('penelitian', 'auth', 'mahasiswa', 'jenis'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $penelitian_id)
		{
			$input = $request -> except('_method');
			$penelitian = MahasiswaPenelitian::find($penelitian_id);					
			$penelitian -> update($input);					
			return Redirect::route('mahasiswa.penelitian.daftar', $penelitian -> mahasiswa_id) -> with('success', 'Data Penelitian berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($penelitian_id)
		{
			$auth = \Auth::user();
			$penelitian = MahasiswaPenelitian::find($penelitian_id);
			
			if($auth -> role_id == 512)
			{
				if($penelitian -> mahasiswa_id != $auth -> authable_id) abort(401);
			}	
			
			$penelitian -> delete();
			
			return Redirect::route('mahasiswa.penelitian.daftar', $penelitian -> mahasiswa_id) -> with('success', 'Data Penelitian Mahasiswa berhasil dihapus.');
		}
		
		public function export()
		{
			$penelitians = MahasiswaPenelitian::penelitian() -> get();
			$data = [];
			foreach($penelitians as $j) 
			{
				$jenis = $this-> jenis[$j-> jenis_penelitian];
				$rdata [] = [
				$j -> NIP,  
				$j -> NIDN,  
				$j -> NIK,  
				str_replace("'", '`', $j -> nama), 
				str_replace("'", '`', $j -> judul), 
				$jenis, 
				str_replace("'", '`', $j -> penerbit), 
				$j -> isbn, 
				$j -> tahun_terbit
				];
			}
			
			$title = 'Form PTKI Penelitian Mahasiswa';
			// $this -> toXlsx(str_slug($title), $title, 'mahasiswa.penelitian.export', $rdata);
			return \Excel::download(new \Siakad\Exports\DataExport('mahasiswa.penelitian.export', $rdata), $title . '.xlsx');
		}
	}
