<?php
	
	namespace Siakad\Http\Controllers;
	
	
	use Redirect;
	
	use Siakad\Dosen;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	class DosenController extends Controller
	{		
		//use \Siakad\DataExchangeTrait;		
		use \Siakad\MahasiswaTrait;		
		
		protected $rules = [
		'NIP' => ['digits:18'],		
		'NIDN' => ['digits:10'],		
		'NIK' => ['required', 'digits_between:16,17'],		
		];
		
		protected $homebase = [
		'Non Homebase', 
		'Pendidikan Agama Islam', 
		'Manajemen Pendidikan Islam', 
		'Pendidikan Guru Madrasah Ibtidaiyah',
		'Dosen Luar Biasa'
		];
		
		//Total SKS
		public function sks()
		{
			$data = \Siakad\Jadwal::JadwalKuliah('admin') -> with('matkul_tapel.tim_dosen') -> get();
			
			$config = config('custom');
			$ar = [];
			foreach($data as $d)
			{
				$dosen = $d -> matkul_tapel -> tim_dosen[0];
				$ar[$dosen -> id][] = [
				'nama' => $dosen -> gelar_depan . ' ' . $dosen -> nama . ' ' . $dosen -> gelar_belakang,
				'nidn' => $dosen -> NIDN,
				'niy' => $dosen -> NIY,
				'matkul' => $d -> matkul,
				'ket' => $d -> keterangan,
				'prodi' => $d -> prodi . ' - ' . $d -> program,
				'kelas' => $d -> kelas,
				'semester' => $d -> semester,
				'sks' => $d -> sks,
				'jadwal' => $config['hari'][$d -> hari] . ', ' . $d -> jam_mulai . ' - ' . $d -> jam_selesai
				];
			}
			
			return view('dosen.sks', compact('ar'));
		}
		
		//Keahlian
		public function keahlian()
		{
			$prodi = \Siakad\Prodi::pluck('nama', 'id');
			$dosen = Dosen::where('id', '>', 0) -> with('matkul') -> get();
			return view('dosen.keahlian', compact('dosen', 'prodi'));
		}
		
		//DIKTI
		public function getExportDikti()
		{
			$format = ['xlsx' => 'Microsoft Excel'];
			$prodi = $this -> getGolongan('prodi', false);
			$tapel = $this -> getGolongan('tapel', false);
			return view('dosen.export.dikti', compact('format', 'tapel', 'prodi'));
		}
		
		public function postExportDikti(Request $request)
		{
			$input = $request -> all();
			$tapel = !isset($input['tapel']) ? "t.aktif='y'" : 't.id=' . $input['tapel'];
			
			$data = \DB::select("
			SELECT mt.kelas2 AS kelas, matkul.kode, matkul.nama, dosen.nama AS nama_dosen, dosen.NIDN, t.nama2 AS tapel, km.semester, kode_dikti
			FROM matkul_tapel mt 
			JOIN tim_dosen ON tim_dosen.matkul_tapel_id = mt.id
			JOIN dosen ON dosen.id = tim_dosen.dosen_id
			JOIN kurikulum_matkul km ON km.id = mt.kurikulum_matkul_id
			JOIN matkul ON km.matkul_id = matkul.id
			JOIN tapel t ON t.id = mt.tapel_id
			JOIN prodi p ON p.id = mt.prodi_id
			WHERE " . $tapel . "
			AND mt.prodi_id = ". $input['prodi'] . " AND dosen.id > 0
			");
			
			$prodi = \Siakad\Prodi::whereId($input['prodi']) -> first();
			$tapel = \Siakad\Tapel::whereId($input['tapel']) -> first();
			
			$title = 'PDDIKTI DOSEN AJAR '. $prodi -> singkatan . ' ' . $tapel -> nama2;
			// $this -> toXlsx(str_slug($title), $title, 'dosen.export.dikti_tpl', $data);	
			
			return \Excel::download(new \Siakad\Exports\DataExport('dosen.export.dikti_tpl', $data), str_slug($title) . '.xlsx');
		}
		
		//EXPORT EMIS
		public function getExport()
		{
			$format = ['xlsx' => 'Microsoft Excel'];
			$ta = \Siakad\Tapel::orderBy('nama2', 'desc') -> pluck('nama', 'id');
			return view('dosen.export.emis', compact('format', 'ta'));
		}
		public function postExport(Request $request)
		{
			$input = $request -> all();
			
			if(intval($input['ta']) > 1 ) 
			{
				$tapel = \Siakad\Tapel::whereId($input['ta']) -> first();
			}
			else
			{
				$tapel = \Siakad\Tapel::whereAktif('y') -> first();
			}
			
			
			$data = \DB::select("SELECT NIP, NIDN, dosen.nama AS nama_dosen, gelar_depan, gelar_belakang, jenisKelamin, 
			tmpLahir, tglLahir, NIK, nama_ibu, pns, dk.pangkat, df.jabatan, 
			no_sk_awal, tmt_sk_awal, no_sk_terbaru, tmt_sk_terbaru, instansi, statusDosen, 
			status_keaktifan, prodi.strata, prodi.nama AS nama_prodi, matkul.nama AS nama_matkul, sks_total, jabatan_tambahan,
			dpd.jenjang, dpd.bidangStudi, dpd.tgl_ijasah, ds.tahun AS tahun_sertifikasi,
			tunjangan_profesi, besar_tunjangan_profesi, IFNULL(db.jbuku, 0) AS jbuku, 
			IFNULL(dpn.jpenelitian, 0) AS jpenelitian, IFNULL(dj.jjurnal, 0) AS jjurnal, alamat, 
			kab, provinsi
			FROM dosen
			LEFT JOIN (SELECT dosen_id, jabatan FROM dosen_fungsional order by str_to_date(tmt, '%d-%m-%Y') desc limit 1) AS df on df.dosen_id = dosen.id
			LEFT JOIN (SELECT dosen_id, pangkat FROM dosen_kepangkatan order by str_to_date(tmt, '%d-%m-%Y') desc limit 1) AS dk on dk.dosen_id = dosen.id
			LEFT JOIN (SELECT dosen_id, prodi_id FROM dosen_penugasan group by dosen_id order by str_to_date(tmt_surat_tugas, '%d-%m-%Y') desc) AS  dp on dp.dosen_id = dosen.id
			LEFT JOIN (SELECT dpd1.dosen_id, dpd1.jenjang, dpd1.bidangStudi, dpd1.tahunLulus, dpd1.tgl_ijasah
			FROM dosen_pendidikan dpd1                    
			LEFT JOIN dosen_pendidikan dpd2              
			ON dpd1.dosen_id = dpd2.dosen_id AND (dpd1.tahunLulus < dpd2.tahunLulus or (dpd1.tahunLulus = dpd2.tahunLulus and dpd1.id < dpd2.id))
			WHERE dpd2.tahunLulus is NULL) AS dpd 
			on dpd.dosen_id = dosen.id
			LEFT JOIN (SELECT ds1.dosen_id, ds1.tahun
			FROM dosen_sertifikasi ds1                    
			LEFT JOIN dosen_sertifikasi ds2              
			ON ds1.dosen_id = ds2.dosen_id AND (ds1.tahun < ds2.tahun or (ds1.tahun = ds2.tahun and ds1.id < ds2.id))
			WHERE ds2.tahun is NULL) AS  ds on ds.dosen_id = dosen.id
			LEFT JOIN (
			SELECT dosen_id, count(judul) AS  jbuku
			FROM dosen_buku
			GROUP BY dosen_id
			) db ON dosen.id = db.dosen_id
			LEFT JOIN (
			SELECT dosen_id, count(judul) AS  jpenelitian
			FROM dosen_penelitian
			GROUP BY dosen_id
			) dpn ON dosen.id = dpn.dosen_id
			LEFT JOIN (
			SELECT dosen_id, count(judul_artikel) AS  jjurnal
			FROM dosen_jurnal
			WHERE level_jurnal = 2
			GROUP BY dosen_id
			) dj ON dosen.id = dj.dosen_id
			LEFT JOIN prodi on prodi.id = dp.prodi_id
			LEFT JOIN matkul_tapel on matkul_tapel.dosen_id = dosen.id
			INNER JOIN tapel on tapel.id = matkul_tapel.tapel_id
			INNER JOIN kurikulum_matkul on kurikulum_matkul.id = matkul_tapel.kurikulum_matkul_id
			INNER JOIN matkul on matkul.id = kurikulum_matkul.matkul_id
			WHERE tapel.id=". $tapel -> id ."
			GROUP BY matkul.nama, prodi.id
			ORDER BY dosen.nama");
			
			$title = 'Form PTKI TA '. strtoupper($tapel -> nama) .'(Dosen)';
			// $this -> toXlsx(str_slug($title), $title, 'dosen.export.emis_tpl', $data);
			return \Excel::download(new \Siakad\Exports\DataExport('dosen.export.emis_tpl', $data), str_slug($title) . '.xlsx');
		}
		
		//jurnal
		public function jurnal($dosen_id)
		{
			if(\Auth::user() -> role_id == 128) $dosen_id = \Auth::user() -> authable_id;
			$dosen = Dosen::find($dosen_id);
			$jurnal = \Siakad\DosenJurnal::jurnal($dosen_id) -> get();
			return view('dosen.jurnal.detail', compact('jurnal', 'dosen'));
		}
		
		//aktifitas mengajar
		public function aktifitasMengajarDosen($dosen_id)
		{
			if(\Auth::user() -> role_id == 128) $dosen_id = \Auth::user() -> authable_id;
			$dosen = Dosen::find($dosen_id);
			$data = \Siakad\MatkulTapel::mataKuliahDosen($dosen_id) -> with('mahasiswa')  -> with('program') -> orderBy('tapel.nama', 'desc') -> get();
			return view('dosen.aktifitasmengajar', compact('data', 'dosen'));
		}
		
		public function gaji()
		{
			$dosen = Dosen::orderBy('kode') -> paginate(20);
			return view('dosen.gaji', compact('dosen'));
		}
		/**
			* Query building for search
		**/
		public function preSearch(Request $request)
		{
			$q = strtolower($request -> get('q'));
			$qclean = preg_replace("[^ 0-9a-zA-Z]", " ", $q);
			
			while (strstr($qclean, "  ")) {
				$qclean = str_replace("  ", " ", $qclean);
			}
			
			$qclean = str_replace(" ", "_", $qclean);
			
			if ($q != '') {
				return redirect( '/dosen/search/'.$qclean );
			}
			return Redirect::back() -> withErrors(['q' => 'Isi kata kunci pencarian yang diinginkan terlebih dahulu']);
		}
		
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index(Request $request)
		{
			$public = (\Auth::user() -> role_id == 1 or  \Auth::user() -> role_id == 2) ? false : true;
			
			$input = $request -> all();
			
			$search = isset($input['q']) && $input['q'] != '' ? $input['q'] : null;
			$status1 = isset($input['status1']) && $input['status1'] != '-' ? $input['status1'] : null;
			$status2 = isset($input['status2']) && $input['status2'] != '-' ? $input['status2'] : null;
			
			$dosen_homebase = $dosen_non_homebase = $dosen_lb = [];
			$dd = Dosen::daftarDosen($search, $status1, $status2) -> get();
			foreach($dd as $dsn)
			{
				if($dsn -> homebase == 0) $dosen_non_homebase[] = $dsn;
				elseif($dsn -> homebase == 4) $dosen_lb[] = $dsn;
				else	$dosen_homebase[$this -> homebase[$dsn -> homebase]][] = $dsn;
			}
			
			$status_k = config('custom.pilihan.statusKepegawaian');
			$status_d = config('custom.pilihan.statusDosen');
			
			return view('dosen.index', compact('dosen_homebase', 'dosen_non_homebase','dosen_lb', 'public', 'status_d', 'status_k'));
		}
		
		private function prodiSelection()
		{
			$tmp = \Siakad\Prodi::orderBy('nama') -> get();
			$prodi[0] = '-';
			foreach($tmp as $t) $prodi[$t -> id] = $t -> strata . ' - ' . $t -> nama;
			
			return $prodi;
		}
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{			
			$prodi = $this -> homebase;
			
			//Bidang Matkul
			$bid_matkul = \Cache::get('bid_matkul_cache', function(){
				$tmp = \Siakad\Kurikulum::matkulKurikulum() -> get();
				$bid_matkul = [];
				if($tmp) foreach($tmp as $t) $bid_matkul[$t -> id] = $t -> kode . ' - ' . $t -> nama;
				
				\Cache::put('bid_matkul_cache', $bid_matkul, 30);
				return $bid_matkul;
			});
			
			return view('dosen.create', compact('prodi', 'bid_matkul'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{			
			$this -> validate($request, $this -> rules);
			$all = $request -> all();
			$input = array_except($all, ['username', 'password', 'bid_matkul_sel']);
			
			$authinfo['username'] = (isset($all['username']) AND $all['username'] != '') ? $all['username'] : str_random(6);
			if(\Siakad\User::where('username', $authinfo['username']) -> exists()) return redirect() -> back() -> withInput() -> with('message', 'Username: ' . $authinfo['username'] . ' sudah terdaftar, harap gunakan username yang lain');
			
			$dosen = Dosen::create($input);
			
			//Bidang Matkul
			\Siakad\DosenMatkul::where('dosen_id', $dosen -> id) -> delete();
			if(isset($all['bid_matkul']) and is_array($all['bid_matkul']))
			{
				$error = [];
				foreach($all['bid_matkul'] as $m)
				{
					if(\Siakad\DosenMatkul::where('matkul_id', $m) -> exists())
					$error[] = $m;
					else
					\Siakad\DosenMatkul::create(['dosen_id' => $dosen -> id, 'matkul_id' => $m]);
				}
			}
			
			$password = (isset($all['password']) AND $all['password'] != '') ? $all['password'] : str_random(6);
			$authinfo['password'] = bcrypt($password);
			$authinfo['role_id'] = 128;
			$authinfo['authable_id'] = $dosen -> id;
			$authinfo['authable_type'] = 'Siakad\Dosen';
			
			\Siakad\User::create($authinfo);
			
			return Redirect::route('dosen.index') -> with('message', 'Data Dosen berhasil dimasukkan. Username: ' . $authinfo['username'] . ' Password: ' . $password);
		}
		
		/**
			* Display the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function show(Dosen $dosen)
		{
			$prodi = $this -> homebase;
			return view('dosen.show', compact('dosen', 'prodi'));
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$prodi = $this -> homebase;
			
			$dosen = Dosen::find($id);
			$hasAccount = \Siakad\User::where('authable_id', $dosen ->id) -> where('authable_type', 'Siakad\\Dosen') -> exists();
			
			//Bidang Matkul
			$bid_matkul = \Cache::get('bid_matkul_cache', function(){
				$tmp = \Siakad\Kurikulum::matkulKurikulum() -> get();
				foreach($tmp as $t) $bid_matkul[$t -> id] = $t -> kode . ' - ' . $t -> nama;
				
				\Cache::put('bid_matkul_cache', $bid_matkul, 30);
				return $bid_matkul;
			});
			
			return view('dosen.edit', compact('dosen', 'hasAccount', 'prodi', 'bid_matkul'));
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
			$all = $request -> all();
			$input = array_except($all, ['_method', 'username', 'password', 'bid_matkul_sel', 'bid_matkul']);
			$dosen = Dosen::find($id);
			if((isset($all['username']) and $all['username'] != '') and (isset($all['password']) and $all['password'] != ''))
			{
				$authinfo['username'] = $all['username'];
				$authinfo['password'] = bcrypt($all['password']);
				$authinfo['role_id'] = 128;
				$authinfo['authable_id'] = $dosen -> id;
				$authinfo['authable_type'] = 'Siakad\Dosen';
				\Siakad\User::create($authinfo);
			}
			
			$dosen-> update($input);
			
			//Bidang Matkul
			\Siakad\DosenMatkul::where('dosen_id', $dosen -> id) -> delete();
			if(isset($all['bid_matkul']) and is_array($all['bid_matkul']))
			{
				$error = [];
				foreach($all['bid_matkul'] as $m)
				{
					if(\Siakad\DosenMatkul::where('matkul_id', $m) -> exists())
					$error[] = $m;
					else
					\Siakad\DosenMatkul::create(['dosen_id' => $dosen -> id, 'matkul_id' => $m]);
				}
			}
			
			return Redirect::route('dosen.index') -> with('message', 'Data Dosen berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function delete($id)
		{
			$dosen = Dosen::find($id);
			$dosen -> delete();
			return Redirect::route('dosen.index') -> with('message', 'Data Dosen berhasil dihapus.');
		}
	}
