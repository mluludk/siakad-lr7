<?php
	
	namespace Siakad\Http\Controllers;
	
	use Cache;
	use Redirect;
	
	use Siakad\Pkm;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	
	class PkmController extends Controller
	{
		use \Siakad\TapelTrait;
		
		protected $rules = [
		'tanggal_mulai' => ['required', 'date', 'date_format:d-m-Y'],
		'tanggal_selesai' => ['date', 'date_format:d-m-Y', 'after:tanggal_mulai'],
		'tgl_mulai_daftar' => ['required', 'date', 'date_format:d-m-Y'],
		'tgl_selesai_daftar' => ['date', 'date_format:d-m-Y', 'after:tgl_mulai_daftar']
		];
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$user = \Auth::user();
			$prodi_id = $dosen_id = null;
			
			if($user -> role_id == 128) $dosen_id = $user -> authable_id;
			
			if($user -> role_id > 60 and $user -> role_id < 128)
			$prodi_id = \Siakad\Prodi::whereSingkatan($user -> role -> sub) -> pluck('id');
			
			$pkm = Pkm::daftarPkm($prodi_id, null, $dosen_id) -> with('lokasi', 'lokasi.pendamping', 'lokasi.peserta', 'lokasi.matkul.prodi', 'lokasi.matkul.mk') -> get();
			// dd($pkm[0] -> lokasi[2]);
			return view('mahasiswa.pkm.index', compact('pkm', 'user'));
		}
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$user = \Auth::user();
			if($user -> role_id > 60 and $user -> role_id < 128)
			$prodi_id = \Siakad\Prodi::whereSingkatan($user -> role -> sub) -> pluck('id');
			else
			$prodi_id = null;
			
			// $mlist = [];
			// $matkul = \Siakad\MatkulTapel::matkulList($prodi_id) -> get();
			// foreach($matkul as $m) $mlist[$m -> matkul_id . '-' . $m -> prodi_id] = $m -> matkul . ' (' . $m -> kode . ') - ' . $m -> strata . ' ' .$m -> singkatan;
			// $matkul = $mlist;
			
			$tapel = $this -> getTapelSelection('desc');
			$edit = false;
			
			return view('mahasiswa.pkm.create', compact('tapel', 'edit'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request  $request)
		{
			$this -> validate($request, $this -> rules);
			
			$input = $request -> except('_token');
			
			Pkm::create($input);
			return Redirect::route('mahasiswa.pkm.index') -> with('message', 'Data PKM berhasil disimpan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$pkm = Pkm::where('id', $id) -> first();
			$prodi_id = null;
			
			/* $user = \Auth::user();
				if($user -> role_id > 60 and $user -> role_id < 128)
				{
				$prodi_id = \Siakad\Prodi::whereSingkatan($user -> role -> sub) -> pluck('id');
				if($pkm -> prodi_id !== $prodi_id) abort('404');
			} */
			
			$edit = true;
			
			return view('mahasiswa.pkm.edit', compact('pkm', 'edit'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $id)
		{
			$this -> validate($request, $this -> rules);
			$input = $request -> except('_method', 'matkul');
			
			Pkm::find($id) -> update($input);
			
			return Redirect::route('mahasiswa.pkm.index') -> with('message', 'Data PKM berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			Pkm::find($id) -> delete();
			return Redirect::route('mahasiswa.pkm.index') -> with('message', 'Data PKM berhasil dihapus.');
		}
		
		/* public function showPeserta($id, $mhs)
			{
			$pkm = Pkm::whereId($id) -> first();
			$show = $admin = true;
			$data = \Siakad\Mahasiswa::whereId($mhs) -> first();
			
			$alamat = '';
			if($data['jalan'] != '') $alamat .= 'Jl. ' . $data['jalan'] . ' ';
			if($data['dusun'] != '') $alamat .= $data['dusun'] . ' ';
			if($data['rt'] != '') $alamat .= 'RT ' . $data['rt'] . ' ';
			if($data['rw'] != '') $alamat .= 'RW ' . $data['rw'] . ' ';
		if($data['kelurahan'] != '') $alamat .= $data['kelurahan'] . ' ';
		if($data['wilayah_id'] != '')
		{
		$wilayah2 = \Siakad\Wilayah::dataKecamatan($data['wilayah_id']) -> first();
		$alamat .= trim($wilayah2 -> kec) . ' ' . trim($wilayah2 -> kab) . ' ' . trim($wilayah2 -> prov) . ' ';
		}
		if($data['kodePos'] != '') $alamat .= $data['kodePos'];
		
		return view('mahasiswa.pkm.daftar', compact('data', 'alamat', 'show', 'pkm', 'admin'));
		}
		
		public function hapusPeserta($id, $mhs)
		{
		\Siakad\Mahasiswa::find($mhs) -> update(['pkm_id' => '']);
		return Redirect::route('mahasiswa.pkm.peserta', $id) -> with('message', 'Peserta pkm berhasil dihapus.');
		} */
		
		
		}
				