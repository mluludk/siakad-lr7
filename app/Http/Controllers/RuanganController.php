<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Ruang;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class RuanganController extends Controller
	{
		public function index()
		{
			$ruangan = Ruang::all();
			return view('ruangan.index', compact('ruangan'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			return view('ruangan.create');
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
			Ruang::create($input);			
			return Redirect::route('ruangan.index') -> with('message', 'Data ruang kuliah berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$ruangan = Ruang::find($id);
			return view('ruangan.edit', compact('ruangan'));
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
			Ruang::find($id) -> update($input);			
			return Redirect::route('ruangan.index') -> with('message', 'Data ruang kuliah berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			//
		}
	}
