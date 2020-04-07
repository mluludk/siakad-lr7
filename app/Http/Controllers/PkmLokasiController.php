<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Siakad\Pkm;
	use Siakad\PkmLokasi;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	
	class PkmLokasiController extends Controller
	{		
		use \Siakad\DosenTrait;
		// use \Siakad\ProdiTrait;
		// use \Siakad\TapelTrait;
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		/* public function index()
			{
			$pkm = PkmLokasi::with('tapel', 'prodi', 'lokasi') -> get();
			return view('mahasiswa.pkm.index', compact('pkm'));
		} */
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create($id)
		{
			// $pkm = Pkm::daftarPkm(null, $id) -> first();
			$pkm = Pkm::find($id);
			return view('mahasiswa.pkm.lokasi.create', compact('pkm'));
		}
		
	/**
	* Store a newly created resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
	public function store(Request  $request, $id)
	{
	$input = $request -> except('_method', '_token');
	$input['pkm_id'] = $id;
	
	PkmLokasi::create($input);
	return Redirect::route('mahasiswa.pkm.index') -> with('message', 'Data Lokasi PKM berhasil disimpan.');
	}
	
	/**
	* Show the form for editing the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function edit($pkm_id, $lokasi_id)
	{			
	$pkm = Pkm::find($pkm_id);
	$lokasi = PkmLokasi::where('id', $lokasi_id) -> first();
	
	return view('mahasiswa.pkm.lokasi.edit', compact('pkm', 'lokasi'));
	}
	
	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function update(Request  $request, $pkm_id, $lokasi_id)
	{
	$input = $request -> except('_method');
	
	PkmLokasi::find($lokasi_id) -> update($input);
	
	return Redirect::route('mahasiswa.pkm.index') -> with('message', 'Data Lokasi PKM berhasil diperbarui.');
	}
	
	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function destroy($id)
	{
	PkmLokasi::find($id) -> delete();
	return Redirect::route('mahasiswa.pkm.index') -> with('message', 'Data Lokasi PKM berhasil dihapus.');
	}
	
	}
		