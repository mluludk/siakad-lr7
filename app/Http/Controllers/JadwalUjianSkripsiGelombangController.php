<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\JadwalUjianSkripsi;
	use Siakad\JadwalUjianSkripsiGelombang;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class JadwalUjianSkripsiGelombangController extends Controller
	{
		
		protected $jenis = ['proposal' => 0, 'komprehensif' => 1];
		
		public function create($j, $id)
		{
			$jenis = $this -> jenis;
			$ujian = JadwalUjianSkripsi::whereJenis($jenis[$j]) -> whereId($id) -> first();
			return view('jadwal.ujian.skripsi.gelombang.create', compact('ujian', 'j'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request  $request, $j, $id)
		{
			$input= $request -> except('_token');
			
			$jenis = $this -> jenis;
			
			if(JadwalUjianSkripsiGelombang::cekGelombang($jenis[$j], $id, $input['tgl_mulai'], $input['tgl_selesai']) -> count())
			return Redirect::back() -> with('warning', 'Sudah ada gelombang Ujian pada tanggal tersebut');
			
			$input['jadwal_ujian_skripsi_id'] = $id;
			JadwalUjianSkripsiGelombang::create($input);
			return Redirect::route('jadwal.ujian.skripsi.index', $j) -> with('message', 'Data Gelombang berhasil disimpan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($j, $id)
		{
			$gelombang = JadwalUjianSkripsiGelombang::with('ujian') -> find($id);
			$ujian = $gelombang -> ujian;
			return view('jadwal.ujian.skripsi.gelombang.edit', compact('ujian', 'gelombang', 'j'));
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
			$jenis = $this -> jenis;
			
			$gelombang = JadwalUjianSkripsiGelombang::find($id);
			$ujian = $gelombang -> ujian;
			
			if(JadwalUjianSkripsiGelombang::cekGelombang($jenis[$j], $ujian -> id, $input['tgl_mulai'], $input['tgl_selesai'], $id) -> count())
			return Redirect::back() -> with('warning', 'Sudah ada gelombang Ujian pada tanggal tersebut');
			
			$gelombang -> update($input);			
			return Redirect::route('jadwal.ujian.skripsi.index', $j) -> with('message', 'Data Gelombang berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($j, $id)
		{
			JadwalUjianSkripsiGelombang::find($id) -> delete();			
			return Redirect::route('jadwal.ujian.skripsi.index', $j) -> with('message', 'Data Gelombang berhasil dihapus.');
		}
	}
