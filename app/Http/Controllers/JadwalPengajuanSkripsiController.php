<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\JadwalPengajuanSkripsi;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class JadwalPengajuanSkripsiController extends Controller
	{
		use \Siakad\ProdiTrait;
		
		public function index()
		{
			$user = \Auth::user();
			$pengajuan = JadwalPengajuanSkripsi::with('gelombang');
			
			if($user -> role_id > 60 and $user -> role_id < 128)
			{
				$prodi_id = \Siakad\Prodi::whereSingkatan($user -> role -> sub) -> pluck('id');
				$pengajuan = $pengajuan -> where('prodi_id', $prodi_id);
			}
			
			$pengajuan = $pengajuan
			-> orderBy('id', 'desc') 
			-> get();
			return view('jadwal.pengajuan.skripsi.index', compact('pengajuan'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$user = \Auth::user();
			$prodi_id = null;
			$prodi = $this -> getProdiSelection('id', 'asc', true);
			$disabled = '';
			
			if($user -> role_id > 60 and $user -> role_id < 128)
			{
				$prodi_id = \Siakad\Prodi::whereSingkatan($user -> role -> sub) -> pluck('id');
				$disabled = 'disabled';
			}
			
			return view('jadwal.pengajuan.skripsi.create', compact('prodi', 'prodi_id', 'disabled'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$input= $request -> except('_token');		
			
			// $user = \Auth::user();
			// if($user -> role_id > 60 and $user -> role_id < 128)
			// {
			// $input['prodi_id'] = \Siakad\Prodi::whereSingkatan($user -> role -> sub) -> pluck('id');
			// }
			
			JadwalPengajuanSkripsi::create($input);
			return Redirect::route('jadwal.pengajuan.skripsi.index') -> with('message', 'Data Jadwal berhasil disimpan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$user = \Auth::user();
			$prodi_id = null;
			$prodi = $this -> getProdiSelection('id', 'asc', true);
			$disabled = '';
			
			if($user -> role_id > 60 and $user -> role_id < 128)
			{
				$prodi_id = \Siakad\Prodi::whereSingkatan($user -> role -> sub) -> pluck('id');
				$disabled = 'disabled';
			}
			$pengajuan = JadwalPengajuanSkripsi::find($id);
			return view('jadwal.pengajuan.skripsi.edit', compact('pengajuan', 'prodi', 'prodi_id', 'disabled'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $id)
		{
			$input = $request -> except('_method');
			JadwalPengajuanSkripsi::find($id) -> update($input);			
			return Redirect::route('jadwal.pengajuan.skripsi.index') -> with('message', 'Data Jadwal berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			JadwalPengajuanSkripsi::find($id) -> delete();			
		return Redirect::route('jadwal.pengajuan.skripsi.index') -> with('message', 'Data Jadwal berhasil dihapus.');
		}
		}
				