<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Mahasiswa;
	use Siakad\MahasiswaTulisan;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class MahasiswaTulisanController extends Controller
	{		
		//use \Siakad\DataExchangeTrait;	
		
		protected $jenis = [1 => 'Pribadi', 2 => 'Kelompok'];
		
        public function daftar($mahasiswa_id)
        {
			$auth = \Auth::user();
			
			if($auth -> role_id == 512) $mahasiswa = $auth-> authable;			
			else $mahasiswa = Mahasiswa::find($mahasiswa_id);
			
			if(!$mahasiswa) abort(404);
			
			$tulisan = MahasiswaTulisan::tulisan($mahasiswa -> id) -> get();
			$jenis = $this-> jenis;
			
			return view('mahasiswa.tulisan.daftar', compact('mahasiswa', 'tulisan', 'auth', 'jenis'));
		}
		
		public function index()
		{
			$tulisan = MahasiswaTulisan::tulisan() -> paginate(30);
			return view('mahasiswa.tulisan.index', compact('tulisan'));
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
			
			return view('mahasiswa.tulisan.create', compact('mahasiswa', 'auth', 'jenis'));
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
			
			MahasiswaTulisan::create($input);			
			return Redirect::route('mahasiswa.tulisan.daftar', $mahasiswa_id) -> with('success', 'Data Tulisan berhasil disimpan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($tulisan_id)
		{
			$auth = \Auth::user();
			$tulisan = MahasiswaTulisan::find($tulisan_id);
			
			if($auth -> role_id == 512)
			{
				if($tulisan -> mahasiswa_id != $auth -> authable_id) abort(401);
				$mahasiswa = $auth -> authable;
			}	
			else
			{
				$mahasiswa = Mahasiswa::find($tulisan -> mahasiswa_id);
			}
			$jenis = $this-> jenis;
			
			return view('mahasiswa.tulisan.edit', compact('tulisan', 'auth', 'mahasiswa', 'jenis'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $tulisan_id)
		{
			$input = $request -> except('_method');
			$tulisan = MahasiswaTulisan::find($tulisan_id);					
			$tulisan -> update($input);					
			return Redirect::route('mahasiswa.tulisan.daftar', $tulisan -> mahasiswa_id) -> with('success', 'Data Tulisan berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($tulisan_id)
		{
			$auth = \Auth::user();
			$tulisan = MahasiswaTulisan::find($tulisan_id);
			
			if($auth -> role_id == 512)
			{
				if($tulisan -> mahasiswa_id != $auth -> authable_id) abort(401);
			}	
			
			$tulisan -> delete();
			
			return Redirect::route('mahasiswa.tulisan.daftar', $tulisan -> mahasiswa_id) -> with('success', 'Data Tulisan Mahasiswa berhasil dihapus.');
		}
		
		public function export()
		{
			$tulisans = MahasiswaTulisan::tulisan() -> get();
			$data = [];
			foreach($tulisans as $j) 
			{
				$jenis = $this-> jenis[$j-> jenis_tulisan];
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
			
			$title = 'Form PTKI Tulisan Mahasiswa';
			// $this -> toXlsx(str_slug($title), $title, 'mahasiswa.tulisan.export', $rdata);
			return \Excel::download(new \Siakad\Exports\DataExport('mahasiswa.tulisan.export', $rdata), $title . '.xlsx');
		}
	}
