<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Dosen;
	use Siakad\DosenPendidikan;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class DosenPendidikanController extends Controller
	{		
		public function riwayat($dosen_id)
		{
			if(\Auth::user() -> role_id == 128) $dosen_id = \Auth::user() -> authable_id;
			$dosen = Dosen::whereId($dosen_id) -> first();
			if(!$dosen) abort(404);
			$pendidikan = DosenPendidikan::riwayatPendidikan($dosen_id) -> get();
			
			return view('dosen.pendidikan.riwayat', compact('dosen', 'pendidikan'));
		}
		
		public function index()
		{
			$pendidikan = DosenPendidikan::riwayatPendidikan() -> paginate(30);
			return view('dosen.pendidikan.index', compact('pendidikan'));
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
			return view('dosen.pendidikan.create', compact('dosen'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request, $dosen_id)
		{
			if(\Auth::user() -> role_id == 128) $dosen_id = \Auth::user() -> authable_id;
			$input = $request -> all();
			$input['dosen_id'] = $dosen_id;
			
			DosenPendidikan::create($input);
			
			return Redirect::route('dosen.pendidikan', $dosen_id) -> with('success', 'Data Pendidikan berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($dosen_id, $pendidikan_id)
		{
			if(\Auth::user() -> role_id == 128) $dosen_id = \Auth::user() -> authable_id;
			$dosen = Dosen::find($dosen_id);
			$pendidikan = DosenPendidikan::find($pendidikan_id);
			
			return view('dosen.pendidikan.edit', compact('pendidikan', 'dosen'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $dosen_id, $pendidikan_id)
		{
			if(\Auth::user() -> role_id == 128) $dosen_id = \Auth::user() -> authable_id;
			$input = $request -> except('_method');
			DosenPendidikan::find($pendidikan_id) -> update($input);					
			return Redirect::route('dosen.pendidikan', $dosen_id) -> with('message', 'Data Pendidikan Dosen berhasil diperbarui.');
		}
		
		/* 		public function show($dosen_id, $pendidikan_id)
			{
			$pendidikan = DosenPendidikan::riwayatPendidikan(null, $pendidikan_id) -> first();
			return view('dosen.pendidikan.show', compact('pendidikan'));
		} */
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($dosen_id, $pendidikan_id)
		{
			if(\Auth::user() -> role_id == 128) $dosen_id = \Auth::user() -> authable_id;
			DosenPendidikan::find($pendidikan_id) -> delete();		
			return Redirect::route('dosen.pendidikan', $dosen_id) -> with('message', 'Data Pendidikan Dosen berhasil dihapus.');
		}
	}
