<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Dosen;
	use Siakad\DosenBuku;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class DosenBukuController extends Controller
	{		
		use \Siakad\DosenTrait;		
		
        public function buku($dosen_id)
        {
			$dosen = Dosen::whereId($dosen_id) -> first();
			if(!$dosen) abort(404);
			$buku = DosenBuku::buku($dosen_id) -> get();
			
			return view('dosen.buku.buku', compact('dosen', 'buku'));
		}
		
		public function index()
		{
			$buku = DosenBuku::buku() -> paginate(30);
			return view('dosen.buku.index', compact('buku'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$dosen_list = $this -> getDosenSelection();
			return view('dosen.buku.create', compact('dosen_list'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$input = $request -> all();
			
			DosenBuku::create($input);
			$auth = \Auth::user();
			
			if($auth -> role_id == 128) return Redirect::route('dosen.buku', $auth -> authable_id) -> with('success', 'Data Buku berhasil disimpan.');
			return Redirect::route('dosen.buku.index') -> with('success', 'Data Buku berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($buku_id)
		{
			$dosen_list = $this -> getDosenSelection();
			$buku = DosenBuku::find($buku_id);
			
			return view('dosen.buku.edit', compact('buku', 'dosen_list'));
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
			DosenBuku::find($buku_id) -> update($input);			
			$auth = \Auth::user();
			
			if($auth -> role_id == 128) return Redirect::route('dosen.buku', $auth -> authable_id) -> with('success', 'Data Buku berhasil diperbarui.');
			return Redirect::route('dosen.buku.index') -> with('message', 'Data Buku Dosen berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($dosen_id, $buku_id)
		{
			DosenBuku::find($buku_id) -> delete();		
			return Redirect::route('dosen.buku', $dosen_id) -> with('message', 'Data Buku Dosen berhasil dihapus.');
		}
		
		public function export()
		{
			$title = 'Form PTKI Buku Dosen';
			return \Excel::download(new \Siakad\Exports\DosenBukuExport, str_slug($title) . '.xlsx');
		}
	}
