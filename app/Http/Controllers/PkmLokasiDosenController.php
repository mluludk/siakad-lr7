<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Siakad\Pkm;
	use Siakad\PkmLokasi;
	use Siakad\PkmLokasiDosen;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	
	class PkmLokasiDosenController extends Controller
	{		
		use \Siakad\DosenTrait;
		public function create($id, $lokasi_id)
		{
			$pkm = Pkm::find($id);
			$lokasi = PkmLokasi::find($lokasi_id);
			$dosen = $this -> getDosenSelection();
			return view('mahasiswa.pkm.lokasi.pendamping.create', compact('pkm', 'lokasi', 'dosen'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request  $request, $id, $lokasi_id)
		{
			$input = $request -> except('_method', '_token');
			$input['pkm_lokasi_id'] = $lokasi_id;
			
			PkmLokasiDosen::create($input);
			return Redirect::route('mahasiswa.pkm.index') -> with('message', 'Data Pendamping PKM berhasil disimpan.');
			}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			PkmLokasiDosen::find($id) -> delete();
			return Redirect::route('mahasiswa.pkm.index') -> with('message', 'Data Pendamping PKM berhasil dihapus.');
		}
		
	}
		