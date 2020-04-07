<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Krs;
	use Siakad\Tapel;
	use Siakad\Matkul;
	use Siakad\KrsDetail;
	use Siakad\Mahasiswa;
	use Siakad\MatkulTapel;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class KrsController extends Controller
	{		
		use \Siakad\AktivitasTrait;
		use \Siakad\MahasiswaTrait;		
		use \Siakad\SkalaTrait;		
		//use \Siakad\DataExchangeTrait;
		use \Siakad\TagihanTrait;		
		
		//Validasi KRS
		public function validasiKrsPost(Request $request)
		{
			$input = $request -> all();
			
			if(isset($input['check']) && is_array($input['check']))
			{
				
				Krs::where('tapel_id', $input['tapel_id']) -> whereIn('mahasiswa_id', $input['check']) -> update(['approved' => 'y']);
				Mahasiswa::whereIn('id', $input['check']) -> update(['statusMhs' => '1']);
				
				// update AKM
				\Siakad\Aktivitas::where('tapel_id', $input['tapel_id']) -> whereIn('mahasiswa_id', $input['check']) -> update(['status' => '1']);
				
				return Redirect::route('krs.validasi') -> with('success', 'Proses Validasi masal berhasil');
			}
			return Redirect::back();
		}
		
		public function validasiKrs(Request $request)
		{
			$auth = \Auth::user();
			$input = $request -> all();
			
			$tapel_id = isset($input['tapel_id']) ? $input['tapel_id'] : null;
			if($tapel_id == null)
			{
				$tapel = \Siakad\Tapel::whereAktif('y') -> first();
				if(!$tapel) return Redirect::to('/') -> withErrors(['NOT_FOUND' => 'Data Tahun Akademik belum diisi.']);
				$tapel_id = $tapel -> id;
			}
			else
			{
				$tapel = \Siakad\Tapel::find($tapel_id);
			}
			
			$dosen_wali = $auth -> role_id == 128 ? $auth -> authable_id : null;
			$q = isset($input['q']) ? $input['q'] : '';	
			
			$mahasiswa = Krs::validasiKrs($tapel_id, $dosen_wali, $q) -> get();
			
			$mahasiswa2 =null;
			if($mahasiswa -> count() > 0 && $dosen_wali != null) 
			{
				foreach($mahasiswa as $m) $mhs2[] = $m -> id;
				
				$mahasiswa2 = Mahasiswa::with('prodi') -> where('dosen_wali', $dosen_wali) -> whereIn('statusMhs', [1, 9]) -> whereNotIn('id', $mhs2) -> get();
			}
			
			$tapel_sel = \Siakad\Tapel::orderBy('nama2', 'desc') -> pluck('nama', 'id');
			
			return view('dosen.perwalian.krs', compact('mahasiswa', 'mahasiswa2', 'tapel_sel', 'tapel', 'request'));
		}
		
		//PDDIKTI
		public function getExportDikti()
		{
			$format = ['xlsx' => 'Microsoft Excel'];
			$prodi = $this -> getGolongan('prodi', false);
			$tapel = $this -> getGolongan('tapel', false);
			return view('krs.export.dikti', compact('format', 'prodi', 'tapel'));			
		}
		public function postExportDikti()
		{
			$input = $request -> all();
			$data =  \Siakad\KrsDetail::exportData($input['prodi'], $input['tapel']) -> get();
			
			$prodi = \Siakad\Prodi::whereId($input['prodi']) -> first();
			$tapel = \Siakad\Tapel::whereId($input['tapel']) -> first();
			
			$title = 'PDDIKTI KRS '. $prodi -> singkatan . ' ' . $tapel -> nama2;
			// $this -> toXlsx(str_slug($title), $title, 'krs.export.dikti_tpl', $data);	
			
			return \Excel::download(new \Siakad\Exports\DataExport('krs.export.dikti_tpl', $data), str_slug($title) . '.xlsx');	
		}
		
		public function printKrs()
		{
			$tapel = Tapel::whereAktif('y') -> first();
			$mhs = Mahasiswa::with('prodi', 'kelas', 'dosenwali') -> whereId(\Auth::user() -> authable -> id) -> first();
			$krs = Krs::getKRS($mhs -> id, $tapel -> id) -> get();
			
			$status = Krs::where('mahasiswa_id', $mhs -> id) -> where('tapel_id', $tapel -> id) -> first();
			return view('krs.print', compact('mhs', 'tapel', 'krs', 'status'));
		}
		
		public function adminKrs($nim, $action = null, $tapel = null)
		{
			$mhs = Mahasiswa::where('NIM', $nim) -> first();
			
			if($action == 'histori') 
			{
				$krs = \Siakad\Nilai::riwayatKRS($mhs -> id) -> get();
				return view('krs.riwayat', compact('krs', 'mhs'));
			}
			
			$tapel = $tapel == null ? Tapel::whereAktif('y') -> first() : Tapel::whereId($tapel) -> first();
			// Krs::firstOrCreate(['mahasiswa_id' => $mhs -> id, 'tapel_id' => $tapel -> id]);
			
			if(!Krs::where('mahasiswa_id', $mhs -> id) -> where('tapel_id', $tapel -> id) -> exists())
			{
				Krs::Create(['mahasiswa_id' => $mhs -> id, 'tapel_id' => $tapel -> id]);
			}
			
			if($action == 'approve') 
			{
				Krs::where('mahasiswa_id', $mhs -> id) -> where('tapel_id', $tapel -> id) -> update(['approved' => 'y']);
				
				$mhs -> update(['statusMhs' => 1]);
				\Siakad\Aktivitas::where('tapel_id', $tapel -> id) -> where('mahasiswa_id', $mhs -> id) -> update(['status' => '1']);
				
				//Update AKM
				$this -> hitungUlangAKM($mhs -> id, $tapel -> id);
				
				return Redirect::route('krs.validasi') -> with('success', 'Proses Validasi berhasil');
			}
			elseif($action == 'review') 
			{
				Krs::where('mahasiswa_id', $mhs -> id) -> where('tapel_id', $tapel -> id) -> update(['approved' => 'n']);
				$mhs -> update(['statusMhs' => 9]);
				\Siakad\Aktivitas::where('tapel_id', $tapel -> id) -> where('mahasiswa_id', $mhs -> id) -> update(['status' => '9']);
				return Redirect::route('krs.validasi') -> with('success', 'Proses Pembatalan Validasi berhasil');
			}
			elseif($action == 'print')
			{
				$krs = Krs::getKRS($mhs -> id, $tapel -> id) -> get();
				$status = Krs::where('mahasiswa_id', $mhs -> id) -> where('tapel_id', $tapel -> id) -> first();
				return view('krs.print', compact('mhs', 'tapel', 'krs', 'status'));
			}
			
			$status = Krs::where('mahasiswa_id', $mhs -> id) -> where('tapel_id', $tapel -> id) -> first();
			
			$krs = Krs::getKRS($mhs -> id, $tapel -> id) -> get();
			
			$sksm = \DB::select("
			select km.semester, sum(m.sks_total) as sksn from nilai n
			left join matkul_tapel mt on n.matkul_tapel_id = mt.id
			left join kurikulum_matkul km on km.id = mt.kurikulum_matkul_id
			left join matkul m on km.matkul_id = m.id
			where n.jenis_nilai_id = 0 AND n.mahasiswa_id = :id
			group by km.semester
			", ['id' => $mhs -> id]);
			
			$nilaim = \DB::select("
			select nilai, count(*) as jumlah from nilai
			where jenis_nilai_id = 0 AND mahasiswa_id = :id AND nilai IS NOT NULL AND nilai <> ''
			group by nilai
			", ['id' => $mhs -> id]);
			
			$ip = \DB::select("
			select km.semester, n.nilai, m.sks_total as sks from nilai n
			left join matkul_tapel mt on n.matkul_tapel_id = mt.id
			left join kurikulum_matkul km on km.id = mt.kurikulum_matkul_id
			left join matkul m on km.matkul_id = m.id
			where n.jenis_nilai_id = 0 AND n.mahasiswa_id = :id
			order by km.semester
			", ['id' => $mhs -> id]);
			
			$skala = $this -> skala($mhs -> prodi_id);
			foreach($ip as $i)
			{
				if(!isset($tmp[$i -> semester]['sksn'])) $tmp[$i -> semester]['sksn'] = 0;
				if(!isset($tmp[$i -> semester]['jsks'])) $tmp[$i -> semester]['jsks'] = 0;
				
				if(isset($skala[$i -> nilai]['angka']))
				{
					$tmp[$i -> semester]['sksn'] += $skala[$i -> nilai]['angka'] * $i -> sks;
				}
				
				$tmp[$i -> semester]['jsks'] += $i -> sks;				
			}
			$tmp2 = [];
			if(isset($tmp))
			{
				foreach($tmp as $smt => $x)
				{
					$tmp2[$smt] = $x['jsks'] < 1 ? 0: number_format($x['sksn'] / $x['jsks'], 2);
				}
			}
			$ipm = $tmp2;
			
			return view('krs.admin', compact('krs', 'mhs', 'tapel', 'status', 'sksm', 'nilaim', 'ipm'));	
		}
		
		public function index()
		{
			$open = false;
			$tapel = Tapel::whereAktif('y') -> first();
			$mhs = Mahasiswa::with('prodi') -> with('kelas') -> whereId(\Auth::user() -> authable -> id) -> first();
			if(!$mhs) return Redirect::to('/home') -> withErrors(['NOT_FOUND' => 'Data tidak ditemukan, Pastikan anda Login sebagai Mahasiswa']);
			
			//cek tanggunga keuangan
			$status = $this -> cekTanggunganKeuangan($tapel, $mhs);	
			if($status['result'] == 'FAILED') return Redirect::to('home') -> withErrors([$status['result'] => $status['message']]);
			
			$status = Krs::where('mahasiswa_id', $mhs -> id) -> where('tapel_id', $tapel -> id) -> first();
			
			if(strtotime(date('Y-m-d H:i:s')) < strtotime($tapel -> selesaiKrs . ' 23:59:59')) $open = true;
			
			if(!Krs::where('mahasiswa_id', $mhs -> id) -> where( 'tapel_id', $tapel -> id) -> exists())
			{
				Krs::create(['mahasiswa_id' => $mhs -> id, 'tapel_id' => $tapel -> id]);
				return Redirect::route('krs.create');	
			}
			
			$krs = Krs::getKRS($mhs -> id, $tapel -> id) -> get();
			
			return view('krs.index', compact('mhs', 'krs', 'tapel', 'status', 'open'));	
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		// TAWARAN MATKUL
		public function create($mahasiswa_id=null)
		{
			$open = false;
			
			$user= \Auth::user();
			
			if($user -> role_id == '512')
			{
				$mhs = Mahasiswa::with('dosenwali') 
				-> whereIn('statusMhs', [1]) 
				-> whereId($user -> authable -> id) -> first();		
				$admin = false;
			}
			else
			{
				$mhs = Mahasiswa::with('dosenwali') 
				-> whereIn('statusMhs', [1, 9]) 
				-> whereId($mahasiswa_id) 
				-> first();	
				$admin = true;
				$open = true;
			}
			
			if(!$mhs) return Redirect::to('home') -> withErrors(['NOT_FOUND' => 'Data tidak ditemukan']);
			
			$id = $user -> role_id == '512' ? $user -> authable -> id : $mahasiswa_id;
			$role = strtolower($user -> role -> name);
			if(!in_array($role, ['root', 'administrator', 'kaprodi', 'prodi', 'mahasiswa'])) return abort(401);
			
			$tapel = Tapel::whereAktif('y') -> first();
			
			if(!$admin)
			{
				//Cek Berkas Ijasah
				if($mhs -> berkas_ijasah == 0) return Redirect::to('home') -> withErrors(['ERROR' => 'Maaf, Anda tidak bisa melakukan KRS karena persyaratan 
				administrasi ijazah belum terselesaikan. Silahkan hubungi Bagian Akademik atau Admin SIAKAD']);			
				
				//cek tanggungan keuangan
				$status = $this -> cekTanggunganKeuangan($tapel, $mhs);		
				if($status['result'] == 'FAILED') return Redirect::to('home') -> withErrors([$status['result'] => $status['message']]);
				
				//cek waktu KRS
				if(strtotime(date('Y-m-d H:i:s')) < strtotime($tapel -> selesaiKrs . ' 23:59:59')) $open = true;
			}
			
			$krs = Krs::where('mahasiswa_id', $mhs -> id) -> where('tapel_id', $tapel -> id) -> first();
			if(!$krs)
			{
				// Krs::Create(['mahasiswa_id' => $mhs -> id, 'tapel_id' => $tapel -> id]);
				\DB::insert('INSERT IGNORE INTO krs (mahasiswa_id, tapel_id) VALUES ("'. $mhs -> id .'", "'. $tapel -> id .'")');
				$krs = Krs::where('mahasiswa_id', $mhs -> id) -> where('tapel_id', $tapel -> id) -> first();
			}
			
			$matkul = MatkulTapel::tawaranMatkul(
			$mhs -> prodi_id, 
			$mhs -> kelasMhs, 
			$mhs -> semesterMhs, 
			$mhs -> id
			) -> with('tim_dosen', 'jadwal.ruang') -> get();
			
			$matkul2 = MatkulTapel::tawaranMatkul(
			$mhs -> prodi_id, 
			$mhs -> kelasMhs, 
			$mhs -> semesterMhs, $mhs -> id,
			false
			) -> with('tim_dosen', 'jadwal.ruang') -> get();
			
			return view('krs.tawaran', compact('matkul', 'matkul2', 'mhs', 'krs', 'open', 'admin'));
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
			if(!isset($input['matkul_tapel_id'])) return Redirect::back() -> with('warning', 'Mata Kuliah belum dipilih.');
			
			$user = \Auth::user();
			
			// mata kuliah yang sudah diambil
			// 19 07 2018
			$matkuls = [];
			
			foreach($input['matkul_tapel_id'] as $id) 
			{
				//cek matkul sudah diambil
				$matkul_id = \Cache::get('matkul_id_for_' . $id, function() use($id){
					$tmp = MatkulTapel::getMatkulId($id) -> first();
					
					\Cache::put('matkul_id_for_' . $id, $tmp -> matkul_id, 60);
					return $tmp -> matkul_id;
				});
				
				if(in_array($matkul_id, $matkuls)) 
				{
					$matkul = Matkul::find($matkul_id);
					return Redirect::back() -> with('warning', 'Mata Kuliah '. $matkul -> nama . ' ('. $matkul -> kode .') hanya dapat diambil 1 kali.');
					
				}
				$matkuls[] = $matkul_id;
				
				$data[] = '(' . $input['krs_id'] . ', ' . $id .')';
				$nilai[] = '(' . $id . ', ' . $input['mahasiswa_id'] . ', 0)';
			}
			
			\DB::insert('INSERT IGNORE INTO krs_detail (krs_id, matkul_tapel_id) VALUES ' . implode(', ', $data));
			//insert into nilai
			\DB::insert('INSERT IGNORE INTO `nilai` (`matkul_tapel_id`, `mahasiswa_id`, `jenis_nilai_id`) VALUES ' . implode(', ', $nilai));
			
			if($user -> role_id == '512')
			{
				return Redirect::route('krs.index') -> with('message', 'KRS berhasil disimpan.');
			}
			else
			{
				return Redirect::route('mahasiswa.krs', $input['mahasiswa_nim']) -> with('message', 'KRS berhasil disimpan.');			
			}
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function destroy()
		{
		$input = $request -> except('_method');
		if(!isset($input['matkul_tapel_id'])) return Redirect::back() -> with('warning', 'Mata Kuliah belum dipilih.');
		foreach($input['matkul_tapel_id'] as $id) KrsDetail::where('krs_id', $input['krs_id']) -> where('matkul_tapel_id', $id) -> delete();
		
		\DB::delete('DELETE FROM `nilai` WHERE `mahasiswa_id` = ' . $input['mahasiswa_id'] . ' AND `matkul_tapel_id` IN ('. implode(',', $input['matkul_tapel_id'] ) .')');
		return Redirect::route('krs.index') -> with('message', 'Mata Kuliah berhasil dihapus.');
		}
		
		
		
		private function cekTanggunganKeuangan($tapel, $mhs)		
		{
		//cek Keuangan where KRS > 0
		// $setup_biaya = \Siakad\BiayaKuliah::where('krs', '>', 0)
		$setup_biaya = \Siakad\BiayaKuliah::where('prodi_id', $mhs -> prodi_id)
		-> where('angkatan', $mhs -> angkatan)
		-> where('kelas_id', $mhs -> kelasMhs)
		-> where('jenisPembayaran', $mhs -> jenisPembayaran)
		-> get();
		
		if(!$setup_biaya) return [
		'result' => 'FAILED',
		'message' => 'Maaf, Biaya Kuliah belum diatur. Hubungi Bagian Administrasi/Keuangan.'
		];
		
		$setup = [];
		foreach($setup_biaya as $s)
		{
		if($s -> krs > 0) $setup[$s -> jenis_biaya_id] = ['persen_krs' => $s -> krs, 'jumlah' => $s -> jumlah, 'privileged' => false];
		}
		
		// not checked
		if(count($setup) < 1) return ['result' => 'SUCCESS'];
		
		//just to make sure Tagihan is already generated
		$this -> generateTagihan($mhs -> id);
		
		$tagihan = \Siakad\Tagihan::rincian($mhs -> id, null, $tapel -> nama2) -> get();
		
		if($tagihan)
		{
		$tanggungan = 0;
		$bayar = 0;
		foreach($tagihan as $t)
		{
		foreach($setup as $k => $v)
		{				
		if($k == $t -> jenis_biaya_id && $t -> privilege_krs == 'n')
		{
		$persen = (int) $t -> bayar == 0 ? 0 : ((int) $t -> bayar / (int) $t -> jumlah) * 100;
		if($persen >= $v['persen_krs']) $setup[$k]['privileged'] = true;
		}
		elseif($t -> privilege_krs == 'y')
		$setup[$k]['privileged'] = true;					
		}
		}
		
		//cek akhir
		$privileged = true;
		foreach($setup as $s) 
		{
		if($s['privileged'] == false) // if there are unprivileged item, all is lost, break it
		{
		$privileged = false;
		break;
		}
		}
		
		if($privileged === false)
		return [
		'result' => 'FAILED',
		'message' => 'Anda masih mempunyai Tanggungan Keuangan. Harap selesaikan terlebih dahulu sebelum melakukan KRS.'
		];
		}
		
		return ['result' => 'SUCCESS'];
		}
		}
				