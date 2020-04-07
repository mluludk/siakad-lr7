<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Siakad\Ppl;
	use Siakad\PplLokasi;
	use Siakad\PplLokasiDosen;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	
	class PplLokasiDosenController extends Controller
	{		
		use \Siakad\DosenTrait;
		public function create($id, $lokasi_id)
		{
			$ppl = Ppl::daftarPpl(null, $id) -> first();
			$lokasi = PplLokasi::find($lokasi_id);
			$dosen = $this -> getDosenSelection();
			return view('mahasiswa.ppl.lokasi.pendamping.create', compact('ppl', 'lokasi', 'dosen'));
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
			$input['ppl_lokasi_id'] = $lokasi_id;
			
			PplLokasiDosen::create($input);
			return Redirect::route('mahasiswa.ppl.index') -> with('message', 'Data Pendamping PPL berhasil disimpan.');
			}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			PplLokasiDosen::find($id) -> delete();
			return Redirect::route('mahasiswa.ppl.index') -> with('message', 'Data Pendamping PPL berhasil dihapus.');
		}
		
	}
		