<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	use Siakad\JadwalPengajuanSkripsi;
	use Siakad\JadwalPengajuanSkripsiGelombang;
	
	class JadwalPengajuanSkripsiGelombangController extends Controller
	{
		use \Siakad\ProdiTrait;
		public function peserta($id)
		{
			$prodi = null;
			
			$gelombang = $id == 'semua' ? null : JadwalPengajuanSkripsiGelombang::with('jadwal') -> find($id);	
			$mahasiswa = JadwalPengajuanSkripsiGelombang::mahasiswa($id) -> get();
			
			return view('jadwal.pengajuan.skripsi.gelombang.peserta.index', compact('gelombang', 'mahasiswa', 'prodi'));
		}
		
		public function create($id)
		{
			$pengajuan = JadwalPengajuanSkripsi::find($id);
			return view('jadwal.pengajuan.skripsi.gelombang.create', compact('pengajuan'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
			*/
		public function store(Request  $request, $id)
		{
			$input= $request -> except('_token');
			$input['jadwal_pengajuan_skripsi_id'] = $id;
			JadwalPengajuanSkripsiGelombang::create($input);
			return Redirect::route('jadwal.pengajuan.skripsi.index') -> with('message', 'Data Gelombang berhasil disimpan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$gelombang = JadwalPengajuanSkripsiGelombang::with('jadwal') -> find($id);
			$pengajuan = $gelombang -> jadwal;
			return view('jadwal.pengajuan.skripsi.gelombang.edit', compact('pengajuan', 'gelombang'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request  $request, $id)
		{
			$input = $request -> except('_method');
			JadwalPengajuanSkripsiGelombang::find($id) -> update($input);			
			return Redirect::route('jadwal.pengajuan.skripsi.index') -> with('message', 'Data Gelombang berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			JadwalPengajuanSkripsiGelombang::find($id) -> delete();			
			return Redirect::route('jadwal.pengajuan.skripsi.index') -> with('message', 'Data Gelombang berhasil dihapus.');
		}
	}
