<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Dosen;
	use Siakad\DosenPenugasan;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class DosenPenugasanController extends Controller
	{		
		//use \Siakad\DataExchangeTrait;	
		public function export()
		{
			$tugas = \Siakad\Jadwal::exportTugasDosen() -> get();			
			$tmp = [];
			$data = [];
			foreach($tugas as $t) 
			{
				if(array_key_exists($t -> dosen_id . $t -> matkul_id, $tmp)) $tmp[$t -> dosen_id . $t -> matkul_id] = $tmp[$t -> dosen_id . $t -> matkul_id] + 1;
				else $tmp[$t -> dosen_id . $t -> matkul_id] = 1;
			}
			$rombel = 1;
			foreach($tugas as $j) {
				$rombel = $tmp[$j -> dosen_id . $j -> matkul_id];
				$homebase = $j -> prodi_id_tugas == $j -> prodi_id_matkul ? 1 : 0;
				$data [] = [$j -> NIP,  $j -> NIDN,  $j -> NIK,  str_replace("'", '`', $j -> dosen) , str_replace("'", '`', $j -> prodi), $homebase,  $j -> jenjang,  
				$rombel,  str_replace("'", '`', $j -> matkul) , $j -> sks,  50,  $j -> hari, $j -> jam_mulai];
			}
			$title = 'Form PTKI Penugasan Dosen';
			// $this -> toXlsx(str_slug($title), $title, 'dosen.penugasan.export', $data);
			return \Excel::download(new \Siakad\Exports\DataExport('dosen.penugasan.export', $data), str_slug($title) . '.xlsx');
		}
		
		public function riwayat($dosen_id)
		{
			if(\Auth::user() -> role_id == 128) $dosen_id = \Auth::user() -> authable_id;
			$dosen = Dosen::whereId($dosen_id) -> first();
			if(!$dosen) abort(404);
			
			$penugasan = DosenPenugasan::riwayatPenugasan($dosen_id) -> get();
			
			return view('dosen.penugasan.riwayat', compact('dosen', 'penugasan'));
		}
		
		public function filter()
		{
			$input = $request -> all();
			
			$dosen_idosen = isset($input['dosen']) && $input['dosen'] !== '-' ?  $input['dosen'] : null;
			$iprodi = isset($input['prodi']) && $input['prodi'] !== '-' ?  $input['prodi'] : null;
			$ita = isset($input['ta']) && $input['ta'] !== '-' ?  $input['ta'] : null;
			
			$penugasan = DosenPenugasan::riwayatPenugasan($dosen_idosen, null, $iprodi, $ita) -> paginate(30);
			
			$prodi_tmp = \Siakad\Prodi::all();
			$prodi['-'] = '-- Semua PRODI --';
			foreach($prodi_tmp as $k) $prodi[$k -> id] = $k -> singkatan;
			
			$dosen_tmp = \Siakad\Dosen::orderBy('nama') -> get();
			$dosen['-'] = '-- Semua Dosen --';
			foreach($dosen_tmp as $k) $dosen[$k -> id] = $k -> nama;
			
			$ta_tmp = \Siakad\Tapel::orderBy('nama2', 'desc') -> get();
			$ta['-'] = '-- Semua Tahun Ajaran --';
			foreach($ta_tmp as $k) $ta[$k -> id] = $k -> nama;
			return view('dosen.penugasan.index', compact('penugasan', 'prodi', 'dosen', 'ta'));
		}
		
		public function index()
		{
			$prodi_tmp = \Siakad\Prodi::all();
			$prodi['-'] = '-- Semua PRODI --';
			foreach($prodi_tmp as $k) $prodi[$k -> id] = $k -> singkatan;
			
			$dosen_tmp = \Siakad\Dosen::orderBy('nama') -> get();
			$dosen['-'] = '-- Semua Dosen --';
			foreach($dosen_tmp as $k) $dosen[$k -> id] = $k -> nama;
			
			$ta_tmp = \Siakad\Tapel::orderBy('nama2', 'desc') -> get();
			$ta['-'] = '-- Semua Tahun Ajaran --';
			foreach($ta_tmp as $k) $ta[$k -> id] = $k -> nama;
			
			$penugasan = DosenPenugasan::riwayatPenugasan() -> paginate(30);
			return view('dosen.penugasan.index', compact('penugasan', 'prodi', 'dosen', 'ta'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$auth = \Auth::user();
			$dosen_list = $auth -> role_id == 128 ? null : Dosen::orderBy('nama') -> pluck('nama', 'id');
			$prodi = \Siakad\Prodi::pluck('nama', 'id');
			$tapel = \Siakad\Tapel::orderBy('nama2', 'desc') -> pluck('nama', 'id');
			//$tmp = \DB::select('select id, left(nama, 9) as ta from tapel group by left(nama2, 4) order by left(nama2, 4) desc');
			//	foreach($tmp as $t) $tapel[$t -> id] = $t -> ta;
			return view('dosen.penugasan.create', compact('dosen_list', 'prodi', 'tapel', 'auth'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$input = $request -> all();			
			DosenPenugasan::create($input);
			
			return Redirect::route('dosen.penugasan', $input['dosen_id']) -> with('success', 'Data Penugasan berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $dosen_id
			* @return \Illuminate\Http\Response
		*/
		public function edit($penugasan_id)
		{
			$auth = \Auth::user();
			$dosen_list = $auth -> role_id == 128 ? null : Dosen::orderBy('nama') -> pluck('nama', 'id');
			$penugasan = DosenPenugasan::find($penugasan_id);
			$prodi = \Siakad\Prodi::pluck('nama', 'id');
			$tmp = \DB::select('select id, left(nama, 9) as ta from tapel group by left(nama2, 4) order by left(nama2, 4) desc');
			foreach($tmp as $t) $tapel[$t -> id] = $t -> ta;
			
			return view('dosen.penugasan.edit', compact('penugasan', 'prodi', 'dosen_list', 'tapel', 'auth'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $dosen_id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $penugasan_id)
		{
			$input = $request -> except('_method');
			DosenPenugasan::find($penugasan_id) -> update($input);					
			return Redirect::route('dosen.penugasan', $input['dosen_id']) -> with('message', 'Data Penugasan Dosen berhasil diperbarui.');
		}
		
		public function show($penugasan_id)
		{
			$penugasan = DosenPenugasan::riwayatPenugasan(null, $penugasan_id) -> first();
			return view('dosen.penugasan.show', compact('penugasan'));
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $dosen_id
			* @return \Illuminate\Http\Response
		*/
		// public function destroy($dosen_id)
		// {
			// DosenPenugasan::find($dosen_id) -> delete();					
			// return Redirect::route('dosen.penugasan.index') -> with('message', 'Data Penugasan Dosen berhasil dihapus.');
		// }
	}
