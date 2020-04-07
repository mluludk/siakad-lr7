<?php
	
	namespace Siakad\Http\Controllers;
	
	
	use Redirect;
	
	use Illuminate\Http\Request;
	
	use Siakad\JenisGaji;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class JenisGajiController extends Controller
	{
		protected $rules = ['nama' => ['required', 'min:2']];
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$jbiaya = JenisGaji::all();
			return view('jenisgaji.index', compact('jbiaya'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			return view('jenisgaji.create');
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$this -> validate($request, $this -> rules);
			$input = $request -> except('_token');
			
			JenisGaji::create($input);
			return Redirect::route('jenisgaji.index') -> with('message', 'Jenis gaji berhasil disimpan');
		}
		
		/**
			* Display the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function show($id)
		{
			//
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$jbiaya = JenisGaji::find($id);
			return view('jenisgaji.edit', compact('jbiaya'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $id)
		{
			$this -> validate($request, $this -> rules);
			$input = $request -> except('_token', '_method');
			JenisGaji::find($id) -> update($input);
			return Redirect::route('jenisgaji.index') -> with('message', 'Jenis pembayaran berhasil diperbarui');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			JenisGaji::find($id) -> delete();
			return Redirect::route('jenisgaji.index') -> with('message', 'Jenis pembayaran berhasil dihapus');
		}
	}
