<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Kelas;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class KelasController extends Controller
	{
		public function index()
		{
			$kelas = Kelas::orderBy('nama') -> get();
			return view('kelas.index', compact('kelas'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			return view('kelas.create');
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
			Kelas::create($input);			
			return Redirect::route('kelas.index') -> with('message', 'Data kelas berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$kelas = Kelas::find($id);
			return view('kelas.edit', compact('kelas'));
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
			Kelas::find($id) -> update($input);			
			return Redirect::route('kelas.index') -> with('message', 'Data kelas berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			Kelas::find($id) -> delete($input);			
			return Redirect::route('kelas.index') -> with('message', 'Data kelas berhasil dihapus.');
		}
	}
