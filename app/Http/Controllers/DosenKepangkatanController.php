<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Dosen;
	use Siakad\DosenKepangkatan;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class DosenKepangkatanController extends Controller
	{		
		public function riwayat($dosen_id)
		{
			if(\Auth::user() -> role_id == 128) $dosen_id = \Auth::user() -> authable_id;
			$dosen = Dosen::whereId($dosen_id) -> first();
			if(!$dosen) abort(404);
			$kepangkatan = DosenKepangkatan::riwayatKepangkatan($dosen_id) -> get();
			
			return view('dosen.kepangkatan.riwayat', compact('dosen', 'kepangkatan'));
		}
		
		public function index()
		{
			$kepangkatan = DosenKepangkatan::riwayatKepangkatan() -> paginate(30);
			return view('dosen.kepangkatan.index', compact('kepangkatan'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create($dosen_id)
		{
			if(\Auth::user() -> role_id == 128) $dosen_id = \Auth::user() -> authable_id;
			$dosen = Dosen::find($dosen_id);
			return view('dosen.kepangkatan.create', compact('dosen'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request, $dosen_id)
		{
			$input = $request -> all();
			if(\Auth::user() -> role_id == 128) $dosen_id = \Auth::user() -> authable_id;
			$input['dosen_id'] = $dosen_id;
			
			DosenKepangkatan::create($input);
			
			return Redirect::route('dosen.kepangkatan', $dosen_id) -> with('success', 'Data Kepangkatan berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $dosen_id
			* @return \Illuminate\Http\Response
		*/
		public function edit($dosen_id, $kepangkatan_id)
		{
			if(\Auth::user() -> role_id == 128) $dosen_id = \Auth::user() -> authable_id;
			$dosen = Dosen::find($dosen_id);
			$kepangkatan = DosenKepangkatan::find($kepangkatan_id);
			
			return view('dosen.kepangkatan.edit', compact('kepangkatan', 'dosen'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $dosen_id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $dosen_id, $kepangkatan_id)
		{
			if(\Auth::user() -> role_id == 128) $dosen_id = \Auth::user() -> authable_id;
			$input = $request -> except('_method');
			DosenKepangkatan::find($kepangkatan_id) -> update($input);					
			return Redirect::route('dosen.kepangkatan', $dosen_id) -> with('message', 'Data Kepangkatan Dosen berhasil diperbarui.');
		}
		
		/* 		public function show($dosen_id, $kepangkatan_id)
			{
			$kepangkatan = DosenKepangkatan::riwayatKepangkatan(null, $kepangkatan_id) -> first();
			return view('dosen.kepangkatan.show', compact('kepangkatan'));
		} */
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $dosen_id
			* @return \Illuminate\Http\Response
		*/
		// public function destroy($dosen_id, $kepangkatan_id)
		// {
		// DosenKepangkatan::find($kepangkatan_id) -> delete();		
		// return Redirect::route('dosen.kepangkatan', $dosen_id) -> with('message', 'Data Kepangkatan Dosen berhasil dihapus.');
		// }
	}
