<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Siakad\MatkulTapel;
	use Illuminate\Http\Request;
	
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class MatkulTapelController extends Controller
	{
		use \Siakad\DosenTrait;
		use \Siakad\FileEntryTrait;
		use \Siakad\MahasiswaTrait;		
		
		public function lock($matkul_tapel_id)
		{	
			\Cache::forget('update_locked_nilai');
			$past = strtotime('yesterday');
			MatkulTapel::find($matkul_tapel_id) -> update(['locked' => 'y', 'tanggal_mulai' => date('Y-m-d', $past), 'tanggal_selesai' => date('Y-m-d', $past)]);
			return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> with('success', 'Nilai telah dikunci');
		}
		public function unlock(Request $request, $matkul_tapel_id)
		{	
			$rules = [
			'tanggal_mulai' => ['required', 'date', 'date_format:Y-m-d'],
			'tanggal_selesai' => ['required', 'date', 'date_format:Y-m-d', 'after:tanggal_mulai'],
			];
			
			$this -> validate($request, $rules);
			
			$input = $request -> except(['_token']);
			
			$input['locked'] = 'n';
			$now = strtotime('now');
			if($now < strtotime($input['tanggal_mulai'] . ' 00:00:00') || $now > strtotime($input['tanggal_selesai'] . ' 23:59:59'))
			{
				$input['locked'] = 'y';
			}
			
			/* $input['locked'] = 'y';
				$now = strtotime('now');
				if($now > strtotime($input['tanggal_mulai'] . ' 00:00:00') AND $now <= strtotime($input['tanggal_selesai'] . ' 23:59:59'))
				{
				$input['locked'] = 'n';
			} */
			
			\Cache::forget('update_locked_nilai');
			MatkulTapel::whereId($matkul_tapel_id) -> update($input);
			return Redirect::back() -> with('success', 'Penilaian dibuka kembali mulai tanggal ' . formatTanggal($input['tanggal_mulai']) . 
			' sampai tanggal ' . formatTanggal($input['tanggal_selesai']));
		}
		
		public function create2($kurikulum_matkul_id, $tapel_id=null)
		{
			$ta = $tapel_id == null ? \Siakad\Tapel::whereAktif('y') -> first() : \Siakad\Tapel::find($tapel_id);
			
			if(!$ta) return Redirect::back() -> with('warning', 'Belum ada Tahun Akademik yang aktif');
			
			$kurikulum_matkul = \Siakad\KurikulumMatkul::with('kurikulum', 'matkul') -> find($kurikulum_matkul_id);
			
			$dosen = $this -> getDosenSelection();
			
			$program = \Siakad\Kelas::orderBy('nama') -> pluck('nama', 'id');
			foreach(range('A', 'Z') as $r) $kelas[$r] = $r;
			
			$ruang = \Siakad\Ruang::pluck('nama', 'id');
			
			return view('matkul.tapel.create2', compact('ta', 'kurikulum_matkul', 'dosen', 'program', 'kelas', 'ruang'));
		}
		
		public function store2(Request $request, $kurikulum_matkul_id)
		{
			$input = $request -> except('_token');
			
			if(isset($input['tim_dosen']))
			{
				$dosen = $input['tim_dosen'];
			}
			elseif(isset($input['dosen_id']) and $input['dosen_id'] > 0)
			{
				$dosen[0] = $input['dosen_id'];
			}
			else
			return Redirect::back() -> with('warning', 'Dosen belum dipilih');
			
			foreach($dosen as $d)
			{
				$check = [
				'dosen_id' => $d,
				'tapel_id' => $input['tapel_id'],
				'hari' => $input['hari'],
				'mulai' => $input['jam_mulai'],
				'selesai' => $input['jam_selesai']
				];
				
				if($this -> crashCheck($check)) return Redirect::back() -> with('warning', 'Jadwal bentrok, Dosen sudah mempunyai jam mengajar pada hari dan jam tersebut. Harap periksa ulang');		
			}
			
			//MatkulTapel
			$mt_data = [
			'kurikulum_matkul_id' => $kurikulum_matkul_id,
			'tapel_id' => $input['tapel_id'],
			'prodi_id' => $input['prodi_id'],
			'bahasan' => $input['bahasan'],
			'kelas' => $input['kelas'],
			'kelas2' => $input['kelas2'],
			'keterangan' => $input['keterangan'],
			'kuota' => $input['kuota']
			];
			if(MatkulTapel::where('kurikulum_matkul_id', $kurikulum_matkul_id) 
			-> where('prodi_id', $input['prodi_id']) 
			-> where('tapel_id', $input['tapel_id']) 
			-> where('kelas', $input['kelas']) 
			-> where('kelas2', $input['kelas2']) 
			-> exists()) return Redirect::back()  -> with('warning', 'Data kelas kuliah sudah terdaftar, mohon periksa kembali');
			$mt = MatkulTapel::create($mt_data);
			
			//Tim Dosen
			foreach($dosen as $d)
			{
				$td_data[] = [
				'dosen_id' => $d,
				'matkul_tapel_id' => $mt -> id
				];
			}
			\Siakad\TimDosen::insert($td_data);
			
			//Jadwal
			$jd_data = [
			'matkul_tapel_id' => $mt -> id,
			'hari' => $input['hari'],
			'jam_mulai' => $input['jam_mulai'],
			'jam_selesai' => $input['jam_selesai'],
			'ruang_id' => $input['ruang_id']			
			];
			\Siakad\Jadwal::create($jd_data);
			
			//Flush Cache
			\Cache::forget('kurikulum_matkul_'. $input['kurikulum_id'] . '_' . $input['semester_id'] . '_' . $input['tapel_id'] . '_' . $input['prodi_id']);
			
			return Redirect::to('/kelasperkuliahan?' .
			'_token=' . csrf_token() .
			'&prodi_id=' . $input['prodi_id'] .
			'&tapel_id=' . $input['tapel_id'] .
			'&kurikulum_id=' . $input['kurikulum_id'] .
			'&semester_id=' . $input['semester_id']
			) -> with('message', 'Data kelas kuliah berhasil dimasukkan');
		}
		
		public function index2(Request $request)
		{
			$input = $request -> all();
			$user = \Auth::user();
			
			$prodi = $this -> filter('prodi');	
			$semester = $this -> filter('semester');
			$periode = $this -> filter('periode');
			
			$prodi_id = (isset($input['prodi_id']) and intval($input['prodi_id']) > 0) ? $input['prodi_id'] : 1;
			$kurikulum_id = (isset($input['kurikulum_id']) and intval($input['kurikulum_id']) > 0) ? $input['kurikulum_id'] : null;
			$tapel_id = (isset($input['tapel_id']) and intval($input['tapel_id']) > 0) ? $input['tapel_id'] : null;
			$semester_id = (isset($input['semester_id']) and intval($input['semester_id']) > 0) ? $input['semester_id'] : 1;
			
			$kurikulum = $this -> filter('kurikulum', $prodi_id);
			
			// \Cache::flush();
			// $data = \Cache::get('kurikulum_matkul_'. $kurikulum_id . '_' . $semester_id . '_' . $tapel_id . '_' . $prodi_id, function() use($kurikulum_id, $semester_id, $tapel_id, $prodi_id){
			$tmp = \Siakad\KurikulumMatkul::detailKurikulum($kurikulum_id, $semester_id) -> get();
			
			$data = [];
			foreach($tmp as $mk)
			{
				$kelas = \Siakad\MatkulTapel::with('jadwal.ruang', 'tim_dosen') 
				-> where('kurikulum_matkul_id', $mk -> kmid) 
				-> where('tapel_id', $tapel_id) 
				-> where('prodi_id', $prodi_id) -> get();
				
				$data[$mk -> matkul_id] = [
				'kmid' => $mk -> kmid, 
				'kode' => $mk -> kode, 
				'nama' => $mk -> nama,
				'sks' => $mk -> sks_total, 
				'semester' => $mk -> semester, 
				'kelas' => $kelas
				];
			}
			
			// \Cache::put('kurikulum_matkul_'. $kurikulum_id . '_' . $semester_id . '_' . $tapel_id . '_' . $prodi_id, $data, 30);
			
			// return $data;
			// });
			
			return view('matkul.tapel.index2', compact('data', 'prodi', 'kurikulum', 'periode', 'semester', 'tapel_id'));
		}
		
		public function edit2($matkul_tapel_id)
		{
			$matkul_tapel = MatkulTapel::find($matkul_tapel_id);
			
			$dosen = $this -> getDosenSelection();
			$prodi = \Siakad\Prodi::where('id', $matkul_tapel -> prodi_id) -> first();
			foreach(range('A', 'Z') as $r) $kelas[$r] = $r;
			$ta = $matkul_tapel -> tapel;
			$program = \Siakad\Kelas::orderBy('nama') -> pluck('nama', 'id');
			$ruang = \Siakad\Ruang::pluck('nama', 'id');
			$kurikulum_matkul = \Siakad\KurikulumMatkul::with('kurikulum', 'matkul') -> find($matkul_tapel -> kurikulum_matkul_id);
			
			$jadwal = \Siakad\Jadwal::where('matkul_tapel_id', $matkul_tapel_id) -> first();
			if($jadwal)
			{
				$matkul_tapel -> hari = $jadwal -> hari ;
				$matkul_tapel -> ruang_id = $jadwal -> ruang_id;
				$matkul_tapel -> jam_mulai = $jadwal -> jam_mulai;
				$matkul_tapel -> jam_selesai = $jadwal -> jam_selesai;
			}
			else
			{
				$matkul_tapel -> hari =null;
				$matkul_tapel -> ruang_id = null;
				$matkul_tapel -> jam_mulai = null;
				$matkul_tapel -> jam_selesai = null;
			}
			$matkul_tapel -> dosen_id = 0;
			
			//Tim Dosen
			$tmp = [];
			$tim_dosen = \Siakad\TimDosen::with('dosen') -> where('matkul_tapel_id', $matkul_tapel_id) -> get();
			
			if($tim_dosen -> count() > 1)
			{
				foreach($tim_dosen as $d)
				{
					$tmp[$d -> dosen_id] = $d -> dosen -> nama;
				}
				$tim_dosen = $tmp;
			}
			elseif($tim_dosen -> count() === 1)
			{
				$matkul_tapel -> dosen_id = $tim_dosen[0] -> dosen -> id;
				$tim_dosen = $tim_dosen[0] -> dosen -> nama;
			}
			else
			{
				$tim_dosen = $tmp;
			}
			
			return view('matkul.tapel.edit2', compact('matkul_tapel', 'dosen', 'prodi', 'kelas', 'ta', 'program', 'ruang', 'kurikulum_matkul', 'tim_dosen'));
		}
		
		public function update2(Request $request, $id)
		{
			$input = array_except($request -> all(), ['_token', '_method']);
			
			// Kelas
			if(MatkulTapel::where('id', '<>', $id)
			-> where('kurikulum_matkul_id', $input['kurikulum_matkul_id']) 
			-> where('prodi_id', $input['prodi_id']) 
			-> where('tapel_id', $input['tapel_id']) 
			-> where('kelas', $input['kelas']) 
			-> where('kelas2', $input['kelas2']) 
			-> exists()) return Redirect::back() -> with('warning', 'Data kelas kuliah sudah terdaftar, mohon periksa kembali');
			
			// check dosen
			if(isset($input['tim_dosen']))
			{
				$dosen = $input['tim_dosen'];
			}
			elseif(isset($input['dosen_id']) and $input['dosen_id'] > 0)
			{
				$dosen[0] = $input['dosen_id'];
			}
			else
			return Redirect::back() -> with('warning', 'Dosen belum dipilih');
			
			foreach($dosen as $d)
			{
				$check = [
				'dosen_id' => $d,
				'tapel_id' => $input['tapel_id'],
				'hari' => $input['hari'],
				'mulai' => $input['jam_mulai'],
				'selesai' => $input['jam_selesai']
				];
				
				if($this -> crashCheck($check, $id)) return Redirect::back() -> with('warning', 'Jadwal bentrok, Dosen sudah mempunyai jam mengajar pada hari dan jam tersebut. Harap periksa ulang');		
			}
			
			$mt_data = [
			'kurikulum_matkul_id' => $input['kurikulum_matkul_id'],
			'tapel_id' => $input['tapel_id'],
			'prodi_id' => $input['prodi_id'],
			'bahasan' => $input['bahasan'],
			'kelas' => $input['kelas'],
			'kelas2' => $input['kelas2'],
			'keterangan' => $input['keterangan'],
			'kuota' => $input['kuota']
			];
			
			MatkulTapel::find($id) -> update($mt_data);
			
			//Tim Dosen
			foreach($dosen as $d)
			{
				$td_data[] = [
				'dosen_id' => $d,
				'matkul_tapel_id' => $id
				];
			}
			\Siakad\TimDosen::where('matkul_tapel_id', $id) -> delete();
			\Siakad\TimDosen::insert($td_data);
			
			//Jadwal
			$jd_data = [
			'hari' => $input['hari'],
			'jam_mulai' => $input['jam_mulai'],
			'jam_selesai' => $input['jam_selesai'],
			'ruang_id' => $input['ruang_id']			
			];
			
			$jadwal = \Siakad\Jadwal::where('matkul_tapel_id', $id);
			
			if($jadwal -> exists())
			$jadwal -> update($jd_data);
			else
			{
				$jd_data['matkul_tapel_id'] = $id;
				\Siakad\Jadwal::create($jd_data);
			}
			
			// return Redirect::route('matkul.tapel.index') -> with('message', 'Data mata kuliah berhasil diubah');
			
			//Flush Cache
			// \Cache::forget('kurikulum_matkul_'. $input['kurikulum_id'] . '_' . $input['semester_id'] . '_' . $input['tapel_id'] . '_' . $input['prodi_id']);
			
			return Redirect::route('kelasperkuliahan.index', [
			'_token' => csrf_token(),
			'prodi_id' => $input['prodi_id'],
			'tapel_id' => $input['tapel_id'],
			'kurikulum_id' => $input['kurikulum_id'],
			'semester_id' => $input['semester_id'],
			]) -> with('message', 'Data kelas kuliah berhasil diubah');
		}
		
		//PDDIKTI
		public function getExportNilaiDikti()
		{
			$format = ['xlsx' => 'Microsoft Excel'];
			$prodi = $this -> getGolongan('prodi', false);
			$tapel = $this -> getGolongan('tapel', false);
			return view('matkul.tapel.export.dikti_nilai', compact('format', 'prodi', 'tapel'));			
		}
		public function postExportNilaiDikti(Request $request)
		{
			$input = $request -> all();
			
			$tapel = !isset($input['tapel']) ? "t.aktif='y'" : 't.id=' . $input['tapel'];
			$data = \DB::select("
			SELECT 
			m.NIM, m.nama as nama_mahasiswa, matkul.kode, matkul.nama as nama_matkul, t.nama2 as tapel, mt.kelas2 as kelas, n.nilai, km.semester, 
			kode_dikti
			FROM nilai n
			JOIN mahasiswa m on m.id = n.mahasiswa_id
			JOIN matkul_tapel mt on n.matkul_tapel_id = mt.id
			JOIN kurikulum_matkul km ON km.id = mt.kurikulum_matkul_id
			JOIN matkul ON km.matkul_id = matkul.id
			JOIN tapel t on t.id = mt.tapel_id
			JOIN prodi on prodi.id = m.prodi_id
			WHERE n.jenis_nilai_id = 0 
			AND " . $tapel . " 
			AND mt.prodi_id = " . $input['prodi']);
			
			$prodi = \Siakad\Prodi::whereId($input['prodi']) -> first();
			$tapel = \Siakad\Tapel::whereId($input['tapel']) -> first();
			
			$title = 'PDDIKTI NILAI '. $prodi -> singkatan . ' ' . $tapel -> nama2;
			
			return \Excel::download(new \Siakad\Exports\DataExport('matkul.tapel.export.dikti_nilai_tpl', $data), $title . '.xlsx');	
		}
		public function getExportDikti()
		{
			$format = ['xlsx' => 'Microsoft Excel'];
			$prodi = $this -> getGolongan('prodi', false);
			$tapel = $this -> getGolongan('tapel', false);
			return view('matkul.tapel.export.dikti_kuliah', compact('format', 'prodi', 'tapel'));			
		}
		public function postExportDikti(Request $request)
		{
			$input = $request -> all();
			$data = MatkulTapel::exportDataKelas($input['prodi'], $input['tapel']) -> get();
			
			$prodi = \Siakad\Prodi::whereId($input['prodi']) -> first();
			$tapel = \Siakad\Tapel::whereId($input['tapel']) -> first();
			
			$title = 'PDDIKTI KELAS KULIAH '. $prodi -> singkatan . ' ' . $tapel -> nama2;
			
			return \Excel::download(new \Siakad\Exports\DataExport('matkul.tapel.export.dikti_kuliah_tpl', $data), $title . '.xlsx');			
		}
		
		public function showFormUploadFile($matkul_tapel_id, $tipe)
		{
			$data = MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			$nama = str_slug($tipe . '-' . $data -> matkul . '-' . $data -> ta . '-' . $data -> dosen, '-');
			return view('matkul.tapel.upload', compact('matkul_tapel_id', 'tipe', 'nama'));
		}
		
		public function uploadFile(Request $request)
		{
			$input = $request -> all();
			$input['akses'] = ["512"]; //Mahasiswa
			switch($input['jenis'])
			{
				case 'rpp':
				$input['tipe'] = '3';
				break;
				
				case 'silabus':
				$input['tipe'] = '4';
			break;
			}
			$result = $this -> upload($input);
			
			if(!$result['success'])
			{
			return \Response::json(['success' => false, 'error' => 'File yang diperbolehkan adalah: PDF, DOC, DOCX']);
			}
			else
			{
			$saved = MatkulTapel::find($input['id']) -> update([$input['jenis'] => $result['id']]);
			if($saved)
			{ 
			return \Response::json(['success' => true, 'filename' => $result['filename']]);
			}
			}
			return \Response::json(['success' => false, 'error' => 'An error occured while trying to save data.']);		
			}
			
			public function pesertaKuliah($matkul_tapel_id)
			{
			$data = MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			if($data -> dosen_id != \Auth::user() -> authable -> id) abort(401);
			$anggota = \Siakad\Nilai::getNilaiAkhirMahasiswa($matkul_tapel_id) -> get();
			
			return view('matkul.tapel.pesertakuliah', compact('data', 'anggota', 'matkul_tapel_id'));
			}
			
			public function mataKuliahDosen()
			{
			$data = MatkulTapel::mataKuliahDosen() -> with('mahasiswa') -> get();
			return view('matkul.tapel.matakuliahdosen', compact('data'));
			}
			
			public function checkNilai($matkul_tapel_id)
			{
			$results = \DB::select('SELECT DISTINCT `jenis_nilai_id` FROM `nilai` WHERE `matkul_tapel_id` = :id', ['id' => $matkul_tapel_id]);
			
			if($results)
			foreach($results as $result) $type[] = $result->jenis_nilai_id;
			else
			$type[0] = 0;
			
			return $type;
			}
			
			public function AddMhsOut(Request $request, $matkul_tapel_id)
			{
			$input = $request -> All();
			$result = \DB::delete('DELETE FROM `nilai` WHERE `mahasiswa_id` IN (' . implode(', ', $input['id']) . ') AND `matkul_tapel_id` = ' . $matkul_tapel_id);
			\DB::delete("DELETE FROM krs_detail WHERE krs_id IN (SELECT id FROM krs WHERE mahasiswa_id IN (" . implode(', ', $input['id']) . ")) AND `matkul_tapel_id` = " . $matkul_tapel_id);
			echo $result ? 'success' : 'failed';
			}
			
			public function AddMhsIn(Request $request, $matkul_tapel_id)
			{
			$types = $this->checkNilai($matkul_tapel_id);
			$input = $request -> All();
			
			$tapel_id = MatkulTapel::whereId($matkul_tapel_id) -> pluck('tapel_id');
			
			foreach($types as $type) foreach($input['id'] as $id) 
			{
			$data[] = '(' . $matkul_tapel_id . ', ' . $id. ', ' . $type . ')';
			
			//update KRS
			// $krs = \Siakad\Krs::firstOrCreate(['mahasiswa_id' => $id, 'tapel_id' => $tapel_id, 'approved' => 'y']);
			
			\DB::insert('INSERT IGNORE INTO krs (mahasiswa_id, tapel_id, approved) VALUES ("'. $id .'", "'. $tapel_id .'", "y")');
			$krs = \Siakad\Krs::where('mahasiswa_id', $id) -> where('tapel_id', $tapel_id) -> first();
			
			\DB::insert("INSERT IGNORE INTO `krs_detail` (krs_id, matkul_tapel_id) VALUES (" . $krs -> id . ", " . $matkul_tapel_id . ")");
			}
			$result = \DB::insert('INSERT IGNORE INTO `nilai` (`matkul_tapel_id`, `mahasiswa_id`, `jenis_nilai_id`) VALUES ' . implode(', ', $data));
			
			echo $result ? 'success' : 'failed';
			}
			
			public function AddMhs($matkul_tapel_id)
			{
			$data = MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			
			$angkatan_raw = \DB::select('
			SELECT DISTINCT angkatan AS `tahun` FROM `mahasiswa`
			');
			$angkatan['-'] = 'semua';
			foreach($angkatan_raw as $k => $v) $angkatan[$v->tahun] = $v->tahun;
			
			//sort by PRODI & semester automatically....
			/*
			$mahasiswa = \DB::select('
			SELECT `mahasiswa`.* 
			FROM `mahasiswa` 
			WHERE `mahasiswa`.`id` NOT IN (
			SELECT `mahasiswa_id`
			FROM `nilai`
			WHERE `nilai`.`matkul_tapel_id` = :id
			AND `nilai`.`jenis_nilai_id` = 0
			) 
			AND `mahasiswa`.`prodi_id` = :prodi_id 			
			AND `mahasiswa`.`kelasMhs` = :program_id 			
			AND `mahasiswa`.`semesterMhs` = :semester_matkul 			
			ORDER BY `NIM` ASC
			', ['id' => $matkul_tapel_id, 'prodi_id' => $data -> prodi_id, 'semester_matkul' => $data -> semester, 'program_id' => $data -> program_id]);
			*/
			/*
			$mahasiswa = \DB::select('
			SELECT `mahasiswa`.* 
			FROM `mahasiswa` 
			WHERE `mahasiswa`.`id` NOT IN (
			SELECT `mahasiswa_id`
			FROM `nilai`
			WHERE `nilai`.`matkul_tapel_id` = :id
			AND `nilai`.`jenis_nilai_id` = 0
			) 		
			AND statusMhs=1
			ORDER BY `NIM` ASC
			', ['id' => $matkul_tapel_id]);
			*/
			
			//sort by PRODI
			$mahasiswa = \DB::select('
			SELECT `mahasiswa`.* 
			FROM `mahasiswa` 
			WHERE `mahasiswa`.`id` NOT IN (
			SELECT `mahasiswa_id`
			FROM `nilai`
			WHERE `nilai`.`matkul_tapel_id` = :id
			AND `nilai`.`jenis_nilai_id` = 0
			) 
			AND statusMhs=1
			AND `mahasiswa`.`prodi_id` = :prodi_id 					
			ORDER BY `NIM` ASC
			', ['id' => $matkul_tapel_id, 'prodi_id' => $data -> prodi_id]);
			
			$anggota = \Siakad\Nilai::getNilaiAkhirMahasiswa($matkul_tapel_id) -> get();			
			return view('matkul.tapel.pesertakuliahproses', compact('data', 'angkatan', 'mahasiswa', 'anggota', 'matkul_tapel_id'));
			}
			
			private function filter($type, $prodi_id=null)
			{
			$data = [];
			switch($type)
			{
			case 'prodi':
			$prodi = \Siakad\Prodi::all();
			$data['-'] = '-- PRODI --';
			foreach($prodi as $k) $data[$k -> id] = $k -> strata . ' ' . $k -> nama;
			break;	
			
			case 'periode':
			$ta = \Siakad\Tapel::orderBy('nama', 'desc') -> get();
			$data['-'] = '-- TAHUN AKADEMIK --';
			foreach($ta as $k) $data[$k -> id] = $k -> nama;
			break;		
			
			case 'semester':
			foreach(range(1, 8) as $r) $data[$r] = $r;
			break;	
			
			case 'ta':
			$ta = \Siakad\Tapel::orderBy('nama', 'desc') -> get();
			$data['-'] = '-- TAHUN AKADEMIK --';
			foreach($ta as $k) $data[$k -> id] = $k -> nama;
			break;	
			
			case 'kurikulum':
			$data['-'] = '-- PILIH KURIKULUM --';
			if($prodi_id !== null) 
			{
			$tmp = \Siakad\Kurikulum::where('prodi_id', $prodi_id) -> get();
			if($tmp) foreach($tmp as $k) $data[$k -> id] = $k -> nama . ' - ' . $k -> angkatan;
			}
			break;
			}
			
			return $data;
			}
			
			public function index(Request $request)
			{
			$user = \Auth::user();
			
			$ta = $this -> filter('ta');
			$ta_id = intval($request -> get('ta')) > 0 ? $request -> get('ta') : null;
			
			$dosen = $this -> getDosenSelection();
			$dosen_id = intval($request -> get('dosen')) > 0 ? $request -> get('dosen') : null;
			
			$perpage = intval($request -> get('perpage')) > 0 ? $request -> get('perpage') : 200;
			
			if($user -> role -> name == 'Prodi')
			{
			$prodi = \Siakad\Prodi::where('singkatan', $user -> role -> sub) -> first();
			$data = MatkulTapel::kelasKuliah($ta_id, $prodi -> id, $dosen_id) -> paginate($perpage);		
			return view('matkul.tapel.perkuliahan', compact('data', 'ta', 'prodi'));				
			}
			
			$prodi = $this -> filter('prodi');			
			$prodi_id = intval($request -> get('prodi')) > 0 ? $request -> get('prodi') : null;
			
			$data = MatkulTapel::kelasKuliah($ta_id, $prodi_id, $dosen_id) -> paginate($perpage);	
			
			return view('matkul.tapel.index', compact('data', 'ta', 'prodi', 'dosen'));
			}
			
			public function edit($matkul_tapel_id)
			{
			$matkul_tapel = MatkulTapel::find($matkul_tapel_id);
			
			$prodi = \Siakad\Prodi::where('id', $matkul_tapel -> prodi_id) -> first();
			$kelas = \Siakad\Kelas::where('id', $matkul_tapel -> kelas) -> first();
			$semester = \Siakad\Tapel::orderBy('nama') -> pluck('nama', 'id');
			$matkuls = \Siakad\Matkul::getMatkulKurikulum($matkul_tapel -> prodi_id) -> get();
			foreach($matkuls as $m) $matkul[$m -> id] = $m -> kode . ' - ' . $m -> matkul . ' (' . $m -> sks_total . ' sks) ' . $m -> kurikulum .' - akt ' . $m -> angkatan;
			
			$aktif = $matkul_tapel -> tapel_id;
			
			$dosen = $this -> getDosenSelection();
			foreach(range('A', 'Z') as $r) $kelas2[$r] = $r;
			
			//Tim Dosen
			$matkul_tapel -> dosen_id = 0;
			$tmp = [];
			$tim_dosen = \Siakad\TimDosen::with('dosen') -> where('matkul_tapel_id', $matkul_tapel_id) -> get();
			
			if($tim_dosen -> count() > 1)
			{
			foreach($tim_dosen as $d)
			{
			$tmp[$d -> dosen_id] = $d -> dosen -> nama;
			}
			$tim_dosen = $tmp;
			}
			elseif($tim_dosen -> count() === 1)
			{
			$matkul_tapel -> dosen_id = $tim_dosen[0] -> dosen -> id;
			$tim_dosen = $tim_dosen[0] -> dosen -> nama;
			}
			else
			{
			$tim_dosen = $tmp;
			}
			
			return view('matkul.tapel.edit', compact('matkul_tapel', 'dosen', 'prodi', 'kelas', 'kelas2', 'semester', 'matkul', 'aktif', 'tim_dosen'));
			}
			
			public function update(Request $request, $id)
			{
			$input = array_except($request -> all(), ['_token', '_method']);
			
			//check penugasan Dosen
			// if(!$this -> cekPenugasan($input)) return Redirect::back()  -> with('warning', 'Penugasan Dosen belum disetting.');
			
			if(MatkulTapel::where('id', '<>', $id)
			-> where('kurikulum_matkul_id', $input['kurikulum_matkul_id']) 
			-> where('prodi_id', $input['prodi_id']) 
			-> where('tapel_id', $input['tapel_id']) 
			-> where('kelas', $input['kelas']) 
			-> where('kelas2', $input['kelas2']) 
			-> exists()) return Redirect::back() -> with('warning', 'Data kelas kuliah sudah terdaftar, mohon periksa kembali');
			
			// check dosen
			if(isset($input['tim_dosen']))
			{
			$dosen = $input['tim_dosen'];
			}
			elseif(isset($input['dosen_id']) and $input['dosen_id'] > 0)
			{
			$dosen[0] = $input['dosen_id'];
			}
			else
			$dosen[0] = 0;
			// return Redirect::back() -> with('warning', 'Dosen belum dipilih');
			
			/* foreach($dosen as $d)
			{
			$check = [
			'dosen_id' => $d,
			'tapel_id' => $input['tapel_id'],
			'hari' => $input['hari'],
			'mulai' => $input['jam_mulai'],
			'selesai' => $input['jam_selesai']
			];
			
			if($this -> crashCheck($check, $id)) return Redirect::back() -> with('warning', 'Jadwal bentrok, Dosen sudah mempunyai jam mengajar pada hari dan jam tersebut. Harap periksa ulang');		
			} */
			
			$mt_data = [
			'prodi_id' => $input['prodi_id'],
			'kurikulum_matkul_id' => $input['kurikulum_matkul_id'],
			'tapel_id' => $input['tapel_id'],
			'bahasan' => $input['bahasan'],
			'kelas' => $input['kelas'],
			'kelas2' => $input['kelas2'],
			'keterangan' => $input['keterangan'],
			'kuota' => $input['kuota']
			];
			
			MatkulTapel::find($id) -> update($mt_data);
			
			//Tim Dosen
			foreach($dosen as $d)
			{
			$td_data[] = [
			'dosen_id' => $d,
			'matkul_tapel_id' => $id
			];
			}
			\Siakad\TimDosen::where('matkul_tapel_id', $id) -> delete();
			\Siakad\TimDosen::insert($td_data);
			
			return Redirect::route('matkul.tapel.index') -> with('message', 'Data mata kuliah berhasil diubah');
			}
			
			/* ////////TO BE DELETED ////// */
			/* 	public function getMatkulList(Request $request)
			{
			$prodi = $request -> get('prodi');
			$angkatan = $request -> get('angkatan');
			$options = '';
			$matkuls = \Siakad\Matkul::getMatkulKurikulum($prodi, $angkatan) -> get();
			foreach($matkuls as $m) $options .= '<option value="'. $m -> id .'">' . $m -> kode . ' - ' . $m -> matkul . ' (' . $m -> sks_total . ' sks) ' . $m -> kurikulum .' - akt ' . $m -> angkatan .'</option>';
			return $options;
			}
			public function getAngkatanList(Request $request)
			{
			$prodi = $request -> get('prodi');
			$options = '<option value="0"> --ANGKATAN-- </option>';
			$kurikulums = \DB::select("
			SELECT DISTINCT k.`angkatan` FROM kurikulum k
			WHERE k.prodi_id = :prodi
			ORDER BY k.`angkatan`
			", ['prodi' => $prodi]);
			foreach($kurikulums as $k) $options .= '<option value="'. $k -> angkatan .'">' . $k -> angkatan .'</option>';
			return $options;
			}  */
			/* /////////// */
			
			// tambah kelas dari kurikulum
			private function crashCheck($data, $id=null)
			{
			$q = $id != null ? ' AND mt.id <> ' . $id : '';
			$crash = \DB::select('
			SELECT j.id
			FROM jadwal j
			INNER JOIN matkul_tapel mt ON mt.id = j.matkul_tapel_id
			INNER JOIN tim_dosen td ON td.matkul_tapel_id = j.matkul_tapel_id
			INNER JOIN tapel tp ON tp.id = mt.tapel_id
			WHERE td.dosen_id = :dosen_id ' . $q . ' 
			AND tp.id = :tapel_id
			AND hari = :hari 
			AND jam_mulai BETWEEN TIME(:mulai) AND TIME(:selesai)
			',
			$data);
			
			if(count($crash)) return true;
			return false;
			}
			
			public function create()
			{
			$prodi = $this -> filter('prodi');	
			$tmp = [];
			$aktif = null;
			
			$semester = \Siakad\Tapel::orderBy('nama') -> get();
			if($semester)
			{
			foreach($semester as $s)
			{
			if($s -> aktif == 'y') $aktif = $s -> id;
			$tmp[$s -> id] = $s -> nama;
			}
			}
			$semester = $tmp;
			
			$matkul = [];
			$dosen = $this -> getDosenSelection();
			
			$kelas = \Siakad\Kelas::pluck('nama', 'id');
			foreach(range('A', 'Z') as $r) $kelas2[$r] = $r;
			
			return view('matkul.tapel.create', compact('semester', 'matkul', 'dosen', 'prodi', 'aktif', 'kelas', 'kelas2'));
			} 
			
			public function store(Request $request)
			{
			$input = array_except($request -> all(), '_token');
			
			//check penugasan Dosen
			//if(!$this -> cekPenugasan($input)) return Redirect::back()  -> with('warning', 'Penugasan Dosen belum disetting.');
			
			if(MatkulTapel::where('kurikulum_matkul_id', $input['kurikulum_matkul_id']) 
			-> where('prodi_id', $input['prodi_id']) 
			// -> where('dosen_id', $input['dosen_id']) 
			// -> where('sks', $input['sks']) 
			// -> where('semester', $input['semester']) 
			-> where('tapel_id', $input['tapel_id']) 
			-> where('kelas', $input['kelas']) 
			-> where('kelas2', $input['kelas2']) 
			-> exists()) return Redirect::back()  -> with('warning', 'Data kelas kuliah sudah terdaftar, mohon periksa kembali');
			
			unset($input['angkatan']);
			$mt = MatkulTapel::create($input);
			
			//tim dosen
			\Siakad\TimDosen::insert([
			'dosen_id' => $input['dosen_id'],
			'matkul_tapel_id' => $mt -> id
			]);
			
			return Redirect::route('matkul.tapel.index') -> with('message', 'Data kelas kuliah berhasil dimasukkan');
			}
			
			public function destroy($id)
			{
			MatkulTapel::find($id) -> delete();
			return Redirect::route('matkul.tapel.index') -> with('message', 'Data kelas kuliah berhasil dihapus');
			}
			
			public function cetakFormAbsensi($id)
			{
			$data = MatkulTapel::getDataMataKuliah($id) -> first();
			$anggota = \Siakad\Nilai::getNilaiAkhirMahasiswa($id) -> get();
			
			return view('matkul.tapel.formabsensi', compact('data', 'anggota', 'id'));
			}
			}
						