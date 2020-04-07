<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Illuminate\Http\Request;
	
	
	use Siakad\Jadwal;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class JadwalController extends Controller
	{
		use \Siakad\TapelTrait;
		use \Siakad\ProdiTrait;
		
		public function formPrintCover()
		{
			$program = [];
			
			$prodi = $this -> getProdiSelection();
			
			$tmp = \Siakad\Kelas::orderBy('nama') -> get();
			if($tmp)
			foreach($tmp as $t) $program[$t -> id] = $t -> nama;
			
			$tapel = $this -> getTapelSelection('desc');
			
			foreach(range(1, 10) as $r) $semester[$r] = $r;
			
			return view('jadwal.cover', compact('prodi', 'program', 'tapel', 'semester'));
		}
		public function printCover(Request $request)
		{
			$input = $request -> except('_token');
			$data = Jadwal::JadwalKuliah('admin', $input['prodi_id'], $input['tapel_id'], $input['kelas_id'], $input['semester']) -> with('matkul_tapel.tim_dosen') -> get();
			
			$prodi = \Siakad\Prodi::find($input['prodi_id']);
			
			$tapel = \Siakad\Tapel::find($input['tapel_id']);
			$tapel = explode(' ', $tapel -> nama);
			
			$c_data = 0;
			$x = null;
			$smt = null;
			if($data)
			{
				$c_data = $data -> count();
				foreach($data as $j)
				{
					$da = [];
					$dosen = $j -> matkul_tapel -> tim_dosen;
					foreach($dosen as $d) $da[] = $d -> gelar_depan . ' ' . $d -> nama . ' ' . $d -> gelar_belakang;
					
					$smt = $j -> semester;
					$prg = $j -> program;
					$kls = $j -> kelas;
					$jam = $j -> jam_mulai . ' - ' . $j -> jam_selesai;
					$x[$j -> hari][$jam] = ['ruang' => $j -> ruang, 'matkul' => $j -> matkul, 'dosen' => implode(', ', $da)];
				}
			}
			$data = $x;
			return view('jadwal.cover_print', compact('data', 'tapel', 'prodi', 'c_data', 'smt', 'prg', 'kls'));
		}
		
		public function mahasiswa()
		{
			$data = Jadwal::JadwalKuliah('mahasiswa') -> with('matkul_tapel.tim_dosen') -> get();
			return view('jadwal.mahasiswa', compact('data'));
		}
		
		public function index(Request $request)
		{
			$p = ($request -> get('prodi') != '' and  $request -> get('prodi') != 0) ? $request -> get('prodi') : null;
			$ta_id = ($request -> get('ta') != '' and  $request -> get('ta') != 0) ? $request -> get('ta') : null;
			$aktif = $ta_id == null ? explode(' ', \Siakad\Tapel::whereAktif('y') -> first() -> nama) : explode(' ', \Siakad\Tapel::whereId($ta_id) -> first() -> nama);
			$prodi = $ta = null;
			if(isset($aktif[1]) && $aktif[1] == 'Genap') $smt = range(2, 8, 2);
			else $smt = range(1, 7, 2);
			
			//show option if admin
			if(\Auth::user() -> role_id <= 2) 
			{
				$prodi = $this -> getProdiSelection();
				
				$ta = $this -> getTapelSelection('desc');
			}
			
			$data = Jadwal::JadwalKuliah('admin', $p, $ta_id) -> with('matkul_tapel.tim_dosen') -> get();
			$x = null;
			if($data)
			{
				foreach($data as $j)
				{
					$da = [];
					$dosen = $j -> matkul_tapel -> tim_dosen;
					foreach($dosen as $d) $da[] = $d -> gelar_depan . ' ' . $d -> nama . ' ' . $d -> gelar_belakang;
					
					$jam = $j -> jam_mulai . ' - ' . $j -> jam_selesai;
					$x[$j -> nama_prodi . '|' . $j->program . '|' . $j->kelas][$j -> hari][$jam][$j->semester] = [
					'id' => $j -> id, 
					'ruang' => $j -> ruang, 
					'matkul' => $j -> matkul, 
					'kelas' => $j -> kelas, 
					'dosen' => implode(', ', $da), 
					'sks' => $j -> sks, 
					'mtid' => $j -> mtid
					];
				}
			}
			$data = $x;
			
			return view('jadwal.index', compact('data', 'prodi', 'smt', 'aktif', 'ta'));
		}
		
		public function dosen()
		{
			$data = Jadwal::JadwalKuliah('dosen') -> with('matkul_tapel.tim_dosen') -> get();
			return view('jadwal.dosen', compact('data'));
		}
		
		public function dosenAllJadwal(Request $request)
		{
			$prodi = $this -> getProdiSelection();
			$ta = $this -> getTapelSelection('desc');
			
			$p = ($request -> get('prodi') != '' and  $request -> get('prodi') != 0) ? $request -> get('prodi') : null;
			$ta_id = ($request -> get('ta') != '' and  $request -> get('ta') != 0) ? $request -> get('ta') : null;
			$aktif = $ta_id == null ? explode(' ', \Siakad\Tapel::whereAktif('y') -> first() -> nama) : explode(' ', \Siakad\Tapel::whereId($ta_id) -> first() -> nama);
			
			if(isset($aktif[1]) && $aktif[1] == 'Genap') $smt = range(2, 8, 2);
			else $smt = range(1, 7, 2);
			
			$data = Jadwal::JadwalKuliah('admin', $p, $ta_id) 
			-> with('matkul_tapel.tim_dosen') 
			-> get();
			$x = null;
			if($data)
			{
				foreach($data as $j)
				{
					$da = [];
					$dosen = $j -> matkul_tapel -> tim_dosen;
					foreach($dosen as $d) $da[] = $d -> gelar_depan . ' ' . $d -> nama . ' ' . $d -> gelar_belakang;
					
					$jam = $j -> jam_mulai . ' - ' . $j -> jam_selesai;
					$x[$j -> nama_prodi . '|' . $j->program][$j -> hari][$jam][$j->semester] = [
					'id' => $j -> id, 
					'ruang' => $j -> ruang, 
					'matkul' => $j -> matkul, 
					'kelas' => $j -> kelas, 
					'dosen' => implode(', ', $da), 
					'sks' => $j -> sks, 
					'mtid' => $j -> mtid
					];
				}
			}
			$data = $x;
			
			return view('jadwal.dosen_all', compact('data', 'prodi', 'smt', 'aktif', 'ta'));
		}
		
		public function index2(Request $request)
		{		
			if(\Auth::user() -> role_id <= 2) 
			{
				$prodi = $this -> getProdiSelection();
				
				$ta = $this -> getTapelSelection('desc');
			}
			
			$p = ($request -> get('prodi') != '' and  $request -> get('prodi') != 0) ? $request -> get('prodi') : null;
			$ta_id = ($request -> get('ta') != '' and  $request -> get('ta') != 0) ? $request -> get('ta') : null;
			$data = Jadwal::JadwalKuliah('admin', $p, $ta_id) -> with('matkul_tapel.tim_dosen') -> get();
			
			return view('jadwal.index2', compact('data', 'prodi','ta'));
		}
		
		public function create(Request $request)
		{
			$matkul = [];
			
			$tapel_id = false !== $request -> get('tapel_id') ? $request -> get('tapel_id') : null;
			$prodi = false !== $request -> get('prodi') ? $request -> get('prodi') : null;
			
			$data = \Siakad\MatkulTapel::kelasKuliah($tapel_id, $prodi) -> get();
			foreach($data as $mk) 
			{
				$dosen = [];
				foreach($mk -> tim_dosen as $d)
				{
					$dosen[] = $d -> gelar_depan . ' ' . $d -> nama . ' ' . $d -> gelar_belakang;
				}
				if(count($dosen)) $dosen = implode(', ', $dosen); else $dosen = 'belum ada Dosen Pengampu';
				
				$matkul[$mk -> mtid] = $mk -> prodi .  ' - '  . $mk -> program . ' Kelas ' . $mk -> kelas . ' - ' . 
				$mk -> kode . ' - ' . $mk -> matkul .' - ' . $dosen . ' - Smt ' . $mk -> semester . ' - '. $mk -> sks . ' SKS';
			}
			
			$ta = $this -> getTapelSelection('desc');
			
			$ruang = \Siakad\Ruang::pluck('nama', 'id');
			return view('jadwal.create', compact('matkul', 'ruang', 'ta'));	
		}
		
		private function crashCheck($data)
		{
			$crash = \DB::select('
			SELECT j.id
			FROM jadwal j
			INNER JOIN matkul_tapel mt ON mt.id = j.matkul_tapel_id
			INNER JOIN tapel tp ON tp.id = mt.tapel_id
			WHERE dosen_id IN (
			SELECT tim_dosen.dosen_id 
			FROM matkul_tapel
			INNER JOIN tim_dosen on tim_dosen.matkul_tapel_id = matkul_tapel.id
			WHERE matkul_tapel.id = :matkul_tapel_id
			) 
			AND tp.id = :tapel_id
			AND hari = :hari 
			AND jam_mulai BETWEEN TIME(:mulai) AND TIME(:selesai)
			',
			$data);
			
			if(count($crash)) return true;
			return false;
		} 
		
		public function store(Request $request)
		{
			$input = $request -> except( '_token');
			
			if($this -> crashCheck([
			'matkul_tapel_id' => $input['matkul_tapel_id'],
			'tapel_id' => $input['tapel_id'],
			'hari' => $input['hari'],
			'mulai' => $input['jam_mulai'],
			'selesai' => $input['jam_selesai']
			])) return Redirect::back() -> with('warning', 'Jadwal bentrok, Dosen sudah mempunyai jam mengajar pada hari dan jam tersebut. Harap periksa ulang');
			
			unset($input['tapel_id']);
			Jadwal::create($input);
			return Redirect::route('matkul.tapel.jadwal') -> with('message', 'Jadwal berhasil disimpan');
		}
		
		public function delete($id)
		{
			Jadwal::find($id) -> delete();
			return Redirect::route('matkul.tapel.jadwal') -> with('message', 'Jadwal berhasil dihapus');
		}
		
		public function edit($id)
		{
			$jadwal = Jadwal::find($id);
			$matkul_data = \Siakad\MatkulTapel::getDataMataKuliah($jadwal -> matkul_tapel_id) -> first();
			
			$ruang = \Siakad\Ruang::pluck('nama', 'id');	
			
			return view('jadwal.edit', compact('jadwal', 'ruang', 'matkul_data'));	
		}
		
		public function update(Request $request, $id)
		{
			$input = $request -> except('_token', '_method');
			$jadwal = Jadwal::find($id);
			
			if($this -> crashCheck([
			'tapel_id' => $input['tapel_id'],
			'matkul_tapel_id' => $jadwal -> matkul_tapel_id,
			'hari' => $input['hari'],
			'mulai' => $input['jam_mulai'],
			'selesai' => $input['jam_selesai']
			]))
			{
				if($jadwal -> ruang_id == $input['ruang_id'])
				return Redirect::back() -> with('warning', 'Jadwal bentrok, Dosen sudah mempunyai jam mengajar pada hari dan jam tersebut. Harap periksa ulang');
			}
			
			unset($input['tapel_id']);
			$jadwal -> update($input);
			
			return Redirect::route('matkul.tapel.jadwal') -> with('message', 'Jadwal berhasil diperbarui');
		}
	}
