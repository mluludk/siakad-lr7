<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Dosen;
	use Siakad\DosenSertifikasi;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class DosenSertifikasiController extends Controller
	{		
		public function riwayat($dosen_id)
		{
			if(\Auth::user() -> role_id == 128) $dosen_id = \Auth::user() -> authable_id;
			
			$dosen = Dosen::whereId($dosen_id) -> first();
			if(!$dosen) abort(404);
			$sertifikasi = DosenSertifikasi::riwayatSertifikasi($dosen_id) -> get();
			
			return view('dosen.sertifikasi.riwayat', compact('dosen', 'sertifikasi'));
		}
		
		public function index()
		{
			$sertifikasi = DosenSertifikasi::riwayatSertifikasi() -> paginate(30);
			return view('dosen.sertifikasi.index', compact('sertifikasi'));
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
			return view('dosen.sertifikasi.create', compact('dosen'));
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
			
			DosenSertifikasi::create($input);
			
			return Redirect::route('dosen.sertifikasi', $dosen_id) -> with('success', 'Data Sertifikasi berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $dosen_id
			* @return \Illuminate\Http\Response
		*/
		public function edit($dosen_id, $sertifikasi_id)
		{
			if(\Auth::user() -> role_id == 128) $dosen_id = \Auth::user() -> authable_id;
			$dosen = Dosen::find($dosen_id);
			$sertifikasi = DosenSertifikasi::find($sertifikasi_id);
			
			return view('dosen.sertifikasi.edit', compact('sertifikasi', 'dosen'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $dosen_id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $dosen_id, $sertifikasi_id)
		{
			$input = $request -> except('_method');
			if(\Auth::user() -> role_id == 128) $dosen_id = \Auth::user() -> authable_id;
			DosenSertifikasi::find($sertifikasi_id) -> update($input);					
			return Redirect::route('dosen.sertifikasi', $dosen_id) -> with('message', 'Data Sertifikasi Dosen berhasil diperbarui.');
		}
		
		/* 		public function show($dosen_id, $sertifikasi_id)
			{
			$sertifikasi = DosenSertifikasi::riwayatSertifikasi(null, $sertifikasi_id) -> first();
			return view('dosen.sertifikasi.show', compact('sertifikasi'));
		} */
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $dosen_id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($dosen_id, $sertifikasi_id)
		{
			if(\Auth::user() -> role_id == 128) $dosen_id = \Auth::user() -> authable_id;
			DosenSertifikasi::find($sertifikasi_id) -> delete();		
			return Redirect::route('dosen.sertifikasi', $dosen_id) -> with('message', 'Data Sertifikasi Dosen berhasil dihapus.');
		}
	}
