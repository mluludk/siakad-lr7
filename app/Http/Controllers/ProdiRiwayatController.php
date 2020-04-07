<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Prodi;
	use Siakad\ProdiRiwayat;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class ProdiRiwayatController extends Controller
	{
		public function index($prodi)
		{
			$prodi_data = Prodi::find($prodi);
			$riwayat = $prodi_data -> riwayat;
			return view('prodi.riwayat.index', compact('prodi_data', 'riwayat'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create($prodi)
		{
			$prodi_data = Prodi::find($prodi);
			return view('prodi.riwayat.create', compact('prodi_data'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request, $prodi)
		{
			$input= $request -> except('_token');
			$prodi_data = Prodi::find($prodi);
			
			$input['prodi_id'] = $prodi;
			ProdiRiwayat::create($input);			
			return Redirect::route('prodi.riwayat.index', $prodi_data -> id) -> with('message', 'Data Riwayat berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($prodi, $riwayat)
		{
			$prodi_data = Prodi::find($prodi);
			$riwayat = ProdiRiwayat::find($riwayat);
			return view('prodi.riwayat.edit', compact('riwayat', 'prodi_data'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $prodi, $riwayat)
		{
			$input = $request -> except('_method');
			ProdiRiwayat::find($riwayat) -> update($input);			
			return Redirect::route('prodi.riwayat.index', $prodi) -> with('message', 'Data Riwayat berhasil diperbarui.');
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
		