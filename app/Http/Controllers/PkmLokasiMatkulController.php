<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Siakad\Pkm;
	use Siakad\PkmLokasi;
	use Siakad\PkmLokasiMatkul;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	
	class PkmLokasiMatkulController extends Controller
	{		
		public function create($id, $lokasi_id)
		{
			$pkm = Pkm::find($id);
			$lokasi = PkmLokasi::find($lokasi_id);
			
			$mlist = [];
			$matkul = \Siakad\MatkulTapel::matkulList() -> get();
			foreach($matkul as $m) $mlist[$m -> matkul_id . '-' . $m -> prodi_id] = $m -> matkul . ' (' . $m -> kode . ') - ' . $m -> strata . ' ' .$m -> singkatan;
			$matkul = $mlist;
			
			return view('mahasiswa.pkm.lokasi.matkul.create', compact('pkm', 'lokasi', 'matkul'));
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
			
			$mt = explode('-', $input['matkul']);
			$input['matkul_id'] = $mt[0];
			$input['prodi_id'] = $mt[1];
			
			unset($input['matkul']);
			
			if(PkmLokasiMatkul::where('pkm_lokasi_id', $lokasi_id) 
			-> where('prodi_id', $mt[1]) 
			-> exists()) return Redirect::route('mahasiswa.pkm.index') -> with('warning', 'Prodi sudah terdaftar dengan Mata Kuliah lain.');
			
			PkmLokasiMatkul::create($input);
			return Redirect::route('mahasiswa.pkm.index') -> with('message', 'Data Mata Kuliah PKM berhasil disimpan.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			PkmLokasiMatkul::find($id) -> delete();
			return Redirect::route('mahasiswa.pkm.index') -> with('message', 'Data Mata Kuliah PKM berhasil dihapus.');
		}
		
	}
