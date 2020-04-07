<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	use Siakad\MatkulTapel;
	use Siakad\SesiPembelajaran;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class SesiPembelajaranController extends Controller
	{
		
		protected $rules = [
		'judul' => 'required',
		'tanggal' => 'required|date|date_format:d-m-Y'
		];
		
		public function index(MatkulTapel $kelas)
		{
			$sesi =SesiPembelajaran::where('matkul_tapel_id', $kelas -> id) -> orderBy('sesi_ke') -> get();
			return view('matkul.tapel.sesi.index', compact('kelas', 'sesi'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create(MatkulTapel $kelas)
		{
			$hari = config('custom.hari');
			$jadwal = $kelas -> jadwal[0];
			return view('matkul.tapel.sesi.create',  compact('kelas', 'hari', 'jadwal'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request, MatkulTapel $kelas)
		{
			$this -> validate($request, $this -> rules);
			$input= $request -> except('_token', 'files');
			$input['matkul_tapel_id'] = $kelas -> id;
			SesiPembelajaran::create($input);			
			return Redirect::route('matkul.tapel.sesi.index', $kelas -> id) -> with('success', 'Data Sesi Pembelajaran berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit(MatkulTapel $kelas, SesiPembelajaran $sesi)
		{
			$hari = config('custom.hari');
			$jadwal = $kelas -> jadwal[0];
			return view('matkul.tapel.sesi.edit', compact('sesi', 'kelas', 'hari', 'jadwal'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, MatkulTapel $kelas, SesiPembelajaran $sesi)
		{
			$this -> validate($request, $this -> rules);
			$input = $request -> except('_method', 'files');
			$sesi -> update($input);			
			return Redirect::route('matkul.tapel.sesi.index', $kelas -> id) -> with('success', 'Data Sesi Pembelajaran berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy(MatkulTapel $kelas, SesiPembelajaran $sesi)
		{
			$sesi -> delete();			
			return Redirect::route('matkul.tapel.sesi.index', $kelas -> id) -> with('success', 'Data Sesi Pembelajaran berhasil dihapus.');
		}
	}
