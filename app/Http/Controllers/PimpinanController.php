<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Pimpinan;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class PimpinanController extends Controller
	{
		use \Siakad\DosenTrait;
		protected $jabatan = [
		'Rektor',
		'Wakil Rektor 1',
		'Wakil Rektor 2',
		'Wakil Rektor 3',
		'Wakil Rektor 4',
		'Sekretaris PT',
		'Kaprodi',
		'Sekretaris Prodi',
		'Dekan',
		'Wakil Dekan I',
		'Wakil Dekan II',
		'Wakil Dekan III',
		'Ketua Jurusan',
		'Wakil Ketua Jurusan',
		'Direktur',
		'Ketua',
		'Pembantu Direktur I',
		'Pembantu Direktur II',
		'Pembantu Direktur III',
		'Wakil Ketua'
		];
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$pimpinan = Pimpinan::with('dosen') -> orderBy('jabatan') -> get();
			$jabatan = $this -> jabatan;
			return view('pimpinan.index', compact('pimpinan', 'jabatan'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$dosen = $this -> getDosenSelection();
			$jabatan = $this -> jabatan;
			return view('pimpinan.create', compact('dosen', 'jabatan'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$input= $request -> except('_token');
			Pimpinan::create($input);
			
			return Redirect::route('pimpinan.index') -> with('message', 'Data Pimpinan berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$pimpinan = Pimpinan::find($id);
			$dosen = $this -> getDosenSelection();
			$jabatan = $this -> jabatan;
			return view('pimpinan.edit', compact('pimpinan', 'dosen', 'jabatan'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $id)
		{
			$input = $request -> except('_method');
			
			Pimpinan::find($id) -> update($input);
			
			return Redirect::route('pimpinan.index') -> with('message', 'Data Pimpinan berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			Pimpinan::find($id) -> delete();
			return Redirect::route('pimpinan.index') -> with('success', 'Data Pimpinan berhasil dihapus.');
		}
	}
