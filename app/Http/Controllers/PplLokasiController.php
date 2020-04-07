<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Siakad\Ppl;
	use Siakad\PplLokasi;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	
	class PplLokasiController extends Controller
	{		
		use \Siakad\DosenTrait;
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create($id)
		{
			$ppl = Ppl::daftarPpl(null, $id) -> first();
			return view('mahasiswa.ppl.lokasi.create', compact('ppl'));
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
			$input['ppl_id'] = $id;
			
			PplLokasi::create($input);
			return Redirect::route('mahasiswa.ppl.index') -> with('message', 'Data Lokasi PPL berhasil disimpan.');
		}
		
	/**
	* Show the form for editing the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function edit($ppl_id, $lokasi_id)
	{			
	$ppl = Ppl::daftarPpl(null, $ppl_id) -> first();
	$lokasi = PplLokasi::where('id', $lokasi_id) -> first();
	
	return view('mahasiswa.ppl.lokasi.edit', compact('ppl', 'lokasi', 'prodi', 'tapel'));
	}
	
	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function update(Request  $request, $ppl_id, $lokasi_id)
	{
	$input = $request -> except('_method');
	
	PplLokasi::find($lokasi_id) -> update($input);
	
	return Redirect::route('mahasiswa.ppl.index') -> with('message', 'Data Lokasi PPL berhasil diperbarui.');
	}
	
	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function destroy($id)
	{
	PplLokasi::find($id) -> delete();
	return Redirect::route('mahasiswa.ppl.index') -> with('message', 'Data Lokasi PPL berhasil dihapus.');
	}
	
	}
		