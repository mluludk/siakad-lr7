<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Mahasiswa;
	use Siakad\MahasiswaBuku;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class MahasiswaBukuController extends Controller
	{		
		//use \Siakad\DataExchangeTrait;	
		
		protected $klasifikasi = [1 => 'Buku Referensi', 2 => 'Buku Monograf'];
		
        public function daftar($mahasiswa_id)
        {
			$auth = \Auth::user();
			
			if($auth -> role_id == 512) $mahasiswa = $auth -> authable;			
			else $mahasiswa = Mahasiswa::find($mahasiswa_id);
			
			if(!$mahasiswa) abort(404);
			
			$buku = MahasiswaBuku::buku($mahasiswa -> id) -> get();
			$klasifikasi = $this -> klasifikasi;
			
			return view('mahasiswa.buku.daftar', compact('mahasiswa', 'buku', 'auth', 'klasifikasi'));
		}
		
		public function index()
		{
			$buku = MahasiswaBuku::buku() -> paginate(30);
			return view('mahasiswa.buku.index', compact('buku'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create($mahasiswa_id)
		{
			$auth = \Auth::user();
			
			if($auth -> role_id == 512) $mahasiswa = $auth -> authable;			
			else $mahasiswa = Mahasiswa::find($mahasiswa_id);
			
			if(!$mahasiswa) abort(404);
			$klasifikasi = $this -> klasifikasi;
			
			return view('mahasiswa.buku.create', compact('mahasiswa', 'auth', 'klasifikasi'));
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
			
			MahasiswaBuku::create($input);			
			return Redirect::route('mahasiswa.buku.daftar', $mahasiswa_id) -> with('success', 'Data Buku berhasil disimpan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($buku_id)
		{
			$auth = \Auth::user();
			$buku = MahasiswaBuku::find($buku_id);
			
			if($auth -> role_id == 512)
			{
				if($buku -> mahasiswa_id != $auth -> authable_id) abort(401);
				$mahasiswa = $auth -> authable;
			}	
			else
			{
				$mahasiswa = Mahasiswa::find($buku -> mahasiswa_id);
			}
			$klasifikasi = $this -> klasifikasi;
			
			return view('mahasiswa.buku.edit', compact('buku', 'auth', 'mahasiswa', 'klasifikasi'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $buku_id)
		{
			$input = $request -> except('_method');
			$buku = MahasiswaBuku::find($buku_id);					
			$buku -> update($input);					
			return Redirect::route('mahasiswa.buku.daftar', $buku -> mahasiswa_id) -> with('success', 'Data Buku berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($buku_id)
		{
			$auth = \Auth::user();
			$buku = MahasiswaBuku::find($buku_id);
			
			if($auth -> role_id == 512)
			{
				if($buku -> mahasiswa_id != $auth -> authable_id) abort(401);
			}	
			
			$buku -> delete();
			
			return Redirect::route('mahasiswa.buku.daftar', $buku -> mahasiswa_id) -> with('success', 'Data Buku Mahasiswa berhasil dihapus.');
		}
		
		public function export()
		{
			$bukus = MahasiswaBuku::buku() -> get();
			$data = [];
			foreach($bukus as $j) 
			{
				$klasifikasi = $this -> klasifikasi[$j -> klasifikasi];
				$rdata [] = [
				$j -> NIP,  
				$j -> NIDN,  
				$j -> NIK,  
				str_replace("'", '`', $j -> nama), 
				str_replace("'", '`', $j -> judul), 
				$klasifikasi, 
				str_replace("'", '`', $j -> penerbit), 
				$j -> isbn, 
				$j -> tahun_terbit
				];
			}
			
			$title = 'Form PTKI Buku Mahasiswa';
			// $this -> toXlsx(str_slug($title), $title, 'mahasiswa.buku.export', $rdata);
			return \Excel::download(new \Siakad\Exports\DataExport('mahasiswa.buku.export', $rdata), str_slug($title) . '.xlsx');	
		}
	}
