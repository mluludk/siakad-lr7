<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\JadwalUjianSkripsi;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class JadwalUjianSkripsiController extends Controller
	{
		
		use \Siakad\TapelTrait;
		use \Siakad\ProdiTrait;
		
		protected $jenis = ['proposal' => 0, 'komprehensif' => 1];
		
		public function index($j)
		{
			$user = \Auth::user();
			$jenis = $this -> jenis;
			$ujian = JadwalUjianSkripsi::with('gelombang');
			
			if($user -> role_id > 60 and $user -> role_id < 128)
			{
				$prodi_id = \Siakad\Prodi::whereSingkatan($user -> role -> sub) -> pluck('id');
				$ujian = $ujian -> where('prodi_id', $prodi_id);
			}
			
			$ujian = $ujian
			-> whereJenis($jenis[$j]) -> orderBy('id', 'desc') -> get();
			
			return view('jadwal.ujian.skripsi.index', compact('ujian', 'j'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create($j)
		{
			$user = \Auth::user();
			$prodi_id = null;
			$tapel = $this -> getTapelSelection('desc');
			$prodi = $this -> getProdiSelection('id', 'asc', true);
			$disabled = '';
			
			if($user -> role_id > 60 and $user -> role_id < 128)
			{
				$prodi_id = \Siakad\Prodi::whereSingkatan($user -> role -> sub) -> pluck('id');
				$disabled = 'disabled';
			}
			return view('jadwal.ujian.skripsi.create', compact('j', 'prodi', 'prodi_id', 'disabled', 'tapel'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request  $request, $j)
		{
			$input= $request -> except('_token');
			$jenis = $this -> jenis;
			$input['jenis'] = $jenis[$j];
			JadwalUjianSkripsi::create($input);
			return Redirect::route('jadwal.ujian.skripsi.index', $j) -> with('message', 'Data Jadwal berhasil disimpan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($j, $id)
		{
			$ujian = JadwalUjianSkripsi::find($id);
			
			$user = \Auth::user();
			$prodi_id = null;
			$tapel = $this -> getTapelSelection('desc');
			$prodi = $this -> getProdiSelection('id', 'asc', true);
			$disabled = '';
			
			if($user -> role_id > 60 and $user -> role_id < 128)
			{
				$prodi_id = \Siakad\Prodi::whereSingkatan($user -> role -> sub) -> pluck('id');
				$disabled = 'disabled';
			}
			
			return view('jadwal.ujian.skripsi.edit', compact('ujian', 'j', 'prodi', 'prodi_id', 'disabled', 'tapel'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request  $request, $j, $id)
		{
			$input = $request -> except('_method');
			JadwalUjianSkripsi::find($id) -> update($input);			
			return Redirect::route('jadwal.ujian.skripsi.index', $j) -> with('message', 'Data Jadwal berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($j, $id)
		{
			JadwalUjianSkripsi::find($id) -> delete();			
			return Redirect::route('jadwal.ujian.skripsi.index', $j) -> with('message', 'Data Jadwal berhasil dihapus.');
		}
	}
