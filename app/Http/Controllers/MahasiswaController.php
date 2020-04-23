<?php
	
	namespace Siakad\Http\Controllers;
	
	use Log;
	use Cache;
	use Redirect;
	
	use Siakad\Mahasiswa;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	
	class MahasiswaController extends Controller
	{
		use \Siakad\TagihanTrait;		
		use \Siakad\TapelTrait;		
		use \Siakad\SkalaTrait;		
		use \Siakad\ProdiTrait;		
		use \Siakad\DosenTrait;		
		use \Siakad\MahasiswaTrait;		
		//use \Siakad\DataExchangeTrait;	
		
		protected $rules = [
		'nama' => ['required', 'valid_name'],
		'tmpLahir' => ['required', 'valid_name'],
		'tglLahir' => ['required', 'date', 'date_format:d-m-Y'],
		'jenisKelamin' => ['required'],
		'NIK' => ['required', 'digits_between:16,17'],
		'agama' => ['required'],
		'wargaNegara' => ['required', 'alpha'],
		'jenisPendaftaran' => ['required', 'numeric'],
		'tapelMasuk' => ['required', 'numeric'],
		'kelurahan' => ['required'],
		'id_wil' => ['required', 'numeric'],
		'kps' => ['required'],
		'namaIbu' => ['required', 'valid_name'],
		];
		// function __construct()
		// {
			// $this -> user = \Auth::user();
			// dd($this -> user);
		// }
		public function statusWisuda($mahasiswa_id)
		{
			$mahasiswa = Mahasiswa::find($mahasiswa_id);
			$status = intval($mahasiswa -> wisuda_id) > 0 ? \Siakad\Wisuda::find($mahasiswa -> wisuda_id) -> first() : false;
			$auth = \Auth::user();
			
			return view('mahasiswa.wisuda.status', compact('mahasiswa', 'status', 'auth'));
		}
		
		//Aktivitas Perkuliahan
		public function aktivitas($mhs_id=null)
		{
			$aktivitas = \Siakad\Aktivitas::AktivitasMahasiswa($mhs_id) -> get();
			$mahasiswa = Mahasiswa::find($mhs_id);
			$nim = Mahasiswa::where('id', '>', 0) -> orderBy('NIM') -> pluck('NIM', 'id');
			return view('mahasiswa.aktivitas', compact('mahasiswa', 'aktivitas', 'nim', 'mhs_id'));
		}
		
		//Validasi
		public function validasi(Request $request)
		{
			$auth = \Auth::user();
			$role = strtolower($auth -> role -> name);
			
			$input = $request -> all();
			
			if($role == 'prodi') 
			{
				$prodi_data = \Siakad\Prodi::where('singkatan', $auth -> role -> sub) -> first();
				$prodi = $prodi_data -> nama;
				$input['prodi'] = $prodi_data -> id;
			}
			else
			{
				$prodi = $this -> getGolongan('prodi');
			}
			
			$semester = ['-' => '-- SEMESTER --', 7 => '7', 8 => '8', 9 => '9', 10 => '10'];
			
			$mahasiswa = Mahasiswa::where('statusMhs', '1');
			
			if(isset($input['q']) and $input['q'] != '') $mahasiswa = $mahasiswa -> where('NIM', 'LIKE', '%' . $input['q'] . '%')  -> orWhere('nama', 'LIKE', '%' . $input['q'] . '%') ;			
			if(isset($input['prodi']) and $input['prodi'] !== '-') $mahasiswa = $mahasiswa -> where('prodi_id', $input['prodi']);			
			if(isset($input['semester']) and $input['semester'] !== '-') $mahasiswa = $mahasiswa -> where('semesterMhs', $input['semester']);
			else $mahasiswa = $mahasiswa -> where('semesterMhs', '>=', 7);
			
			$mahasiswa = $mahasiswa -> orderBy('NIM', 'desc') -> get();
			
			if($role == 'prodi')
			return view('mahasiswa.validasi.prodi', compact('mahasiswa', 'prodi', 'semester'));
			
			abort(404);
		}
		public function postValidasi(Request $request)
		{
			$input = $request -> except(['_token']);
			$auth = \Auth::user();
			$role = strtolower($auth -> role -> name);
			$t = null;
			if(count($input))
			{
				
				// if($role == 'keuangan / administrasi') $t = 'tg_ku';
				if($role == 'prodi') $t = 'tg_pr';
				// if($role == 'akademik') $t = 'tg_ak';
				
				// $tg_array = ['tg_ku', 'tg_pr', 'tg_ak'];
				$tg_array = ['tg_pr'];
				foreach($input as $id => $tg)
				{
					if($auth -> role_id <= 2)
					{
						foreach($tg_array as $tga)
						{
							if(!isset($tg[$tga . '_wis']) or $tg[$tga . '_wis'] != 1) $tg[$tga . '_wis'] = '0';
						}
					}
					else
					{
						if(!isset($tg[$t . '_wis'])) $tg[$t . '_wis'] = '0';
					}
					unset($tg['id']);
					Mahasiswa::find($id) -> update($tg);
				}
			}
			return Redirect::route('mahasiswa.validasi') -> with('success', 'Data telah disimpan');
		}
		
		//Kemajuan Belajar
		public function kemajuan(Request $request, $id)
		{
			$auth = \Auth::user();
			
			$mahasiswa = Mahasiswa::find($id);
			
			$matkul = $ditempuh = [];
			$tmp1 = \DB::select('
			select mk.id, mk.nama as matkul, mk.kode, mk.sks_total, km.semester
			from kurikulum_matkul km
			inner join kurikulum k on km.kurikulum_id = k.id 
			inner join matkul mk on mk.id = km.matkul_id
			inner join mahasiswa m on m.prodi_id = k.prodi_id 
			and m.angkatan = k.angkatan
			where m.id = :id_mhs 
			order by km.semester;', ['id_mhs' => $mahasiswa -> id]);
			
			foreach($tmp1 as $t1) $matkul[$t1 -> id] = ['kode' => $t1 -> kode, 'nama' => $t1 -> matkul, 'sks' => $t1 -> sks_total, 'semester' => $t1 -> semester];	
			
			$tmp2 = \DB::select('select n.nilai, n.matkul_tapel_id, km.matkul_id
			from nilai n
			inner join matkul_tapel mt on n.matkul_tapel_id = mt.id
			inner join kurikulum_matkul km on mt.kurikulum_matkul_id = km.id
			where n.jenis_nilai_id = 0 and n.mahasiswa_id = :id_mhs;', ['id_mhs' => $mahasiswa -> id]);
			
			foreach($tmp2 as $t2) if($t2 -> nilai != null or $t2 -> nilai != '') $ditempuh[$t2 -> matkul_id] = ['nilai' => $t2 -> nilai];	
			
			if($request -> get('act') == 'print') return view('mahasiswa.matkulprint', compact('matkul', 'mahasiswa', 'ditempuh'));
			
			if($auth -> role_id == 128)
			return view('mahasiswa.kemajuan', compact('matkul', 'mahasiswa', 'ditempuh'));
			else
			return view('mahasiswa.matkul', compact('auth', 'matkul', 'mahasiswa', 'ditempuh'));
		}
		
		// Cetak Kartu MAsuk Ujian
		private function cetakKartuMasukUjian($mahasiswa_id, $jenis = '')
		{
			$tapel = \Siakad\Tapel::whereAktif('y') -> first();
			$mhs = Mahasiswa::with('prodi', 'kelas', 'dosenwali') -> whereId($mahasiswa_id) -> first();
			$krs = \Siakad\Krs::getKRS($mahasiswa_id, $tapel -> id) -> get();
			
			$tmp = explode(' ', $tapel -> nama);
			$semester = $tmp[1] . ' ' . str_replace('/', ' - ', $tmp[0]);
			return view('mahasiswa.kartumasukujian', compact('mhs', 'tapel', 'krs', 'semester', 'jenis'));
		}
		
		//Cetak kartu
		public function cetakKartu($mahasiswa_id, $kartu)
		{	
			$auth = \Auth::user();
			if($auth -> role_id == 512) $mahasiswa_id = $auth -> authable_id;
			$data = Mahasiswa::find($mahasiswa_id);
			
			if($kartu == 'ktm')
			return view('mahasiswa.ktm', compact('data'));
			
			$privilege = false;
			//SPP only
			$tapel = \Siakad\Tapel::whereAktif('y') -> first();
			$tagihan = \Siakad\Tagihan::rincian($mahasiswa_id, 1) -> get();
			
			if(!$tagihan && $auth -> role_id == 512) return Redirect::back() -> withErrors(['Tagihan Biaya mahasiswa tidak ditemukan.']);
			$tsemester = $bsemester = $psemester = $tanggungan = $bayar = 0;
			foreach($tagihan as $t)
			{
				if($kartu == 'uts' && $t -> privilege_uts == 'y')
				{
					$privilege = true;
					break;
				}
				if($kartu == 'uas' && $t -> privilege_uas == 'y')
				{
					$privilege = true;
					break;
				}
				
				if($tapel -> nama2 == $t -> tapel) 
				{
					$tsemester = $t -> jumlah;
					$bsemester =  $t -> bayar;
				}
				$tanggungan +=  $t -> jumlah - $t -> bayar;
				$bayar +=  $t -> bayar;
			}
			
			$prosentase = (int) $bayar == 0 || (int) $tanggungan == 0 ? 0 : ((int) $bayar / (int) $tanggungan) * 100;
			$psemester = (int) $bsemester == 0 ? 0 : ((int) $bsemester / (int) $tsemester) * 100;
			
			
			//presentase tanggungan << needs to be ckecked for where UTS / UAS > 0
			$setup_biaya = \Siakad\BiayaKuliah::where('jenis_biaya_id', 1)
			-> where('angkatan', $data -> angkatan)
			-> where('prodi_id', $data -> prodi_id)
			-> where('kelas_id', $data -> kelasMhs)
			-> where('jenisPembayaran', $data -> jenisPembayaran)
			-> first();
			
			if(!$setup_biaya) return Redirect::back() -> withErrors(['ERROR' => 'Maaf, Biaya Kuliah belum diatur. Hubungi Bagian Administrasi/Keuangan.']);
			
			if($kartu == 'uts')
			{
				if(($psemester < $setup_biaya -> uts && $tsemester > 0 && $privilege == false)) 
				{
					return $this -> cetakKartuMasukUjian($data -> id, 'uts');
				}	
				return view('mahasiswa.kartu', compact('data', 'kartu'));
			}
			elseif($kartu == 'uas')
			{
				if(($psemester < $setup_biaya -> uas && $tsemester > 0 && $privilege == false)) 
				{					
					return $this -> cetakKartuMasukUjian($data -> id, 'uas');
				}
				return view('mahasiswa.kartu', compact('data', 'kartu'));
			}
		}
		
		//PDDIKTI
		public function getExportDikti()
		{
			$format = ['xlsx' => 'Microsoft Excel'];
			$prodi = $this -> getGolongan('prodi', false);
			return view('mahasiswa.export.dikti', compact('format', 'prodi'));
		}
		public function postExportDikti(Request $request)
		{
			$input = $request -> all();
			$data = Mahasiswa::with('prodi') -> where('prodi_id', $input['prodi']) -> get();
			$prodi = \Siakad\Prodi::whereId($input['prodi']) -> first();
			
			$title = 'PDDIKTI MAHASISWA '. $prodi -> singkatan;
			// $this -> toXlsx(str_slug($title), $title, 'mahasiswa.export.dikti_tpl', $data);
			
			return \Excel::download(new \Siakad\Exports\DataExport('mahasiswa.export.dikti_tpl', $data), str_slug($title) . '.xlsx');	
		}
		public function getExportKelulusan()
		{
			$format = ['xlsx' => 'Microsoft Excel'];
			$prodi = $this -> getGolongan('prodi', false);
			return view('mahasiswa.export.dikti_kelulusan', compact('format', 'prodi'));
		}
		public function postExportKelulusan(Request $request)
		{
			$input = $request -> all();
			
			$prodi = \Siakad\Prodi::whereId($input['prodi']) -> first();
			
			$mahasiswa = \DB::select("
			SELECT m.*, m.nama as nama_mhs, s.judul, d.NIDN as pembimbing, w.*, kode_dikti,
			date_format(STR_TO_DATE(b.tglBimbingan, '%d-%m-%Y'), '%Y-%m-%d') as bimbingan
			FROM mahasiswa m
			inner join prodi on prodi.id = m.prodi_id
			LEFT JOIN skripsi s ON m.skripsi_id = s.id
			LEFT JOIN dosen_skripsi ds ON ds.skripsi_id = s.id
			LEFT JOIN dosen d ON ds.dosen_id = d.id
			LEFT JOIN bimbingan_skripsi b on b.skripsi_id = s.id
			LEFT JOIN wisuda w ON m.wisuda_id = w.id
			WHERE m.statusMhs = 4 AND m.prodi_id = :id;
			", ['id' => $prodi -> id]);	
			
			$rdata = null;
			foreach($mahasiswa as $m)
			{
				$data = \Siakad\Nilai::dataKHS($m -> NIM) -> get();
				$nilai = true;
				$cursks = $cursksn = $ipk = $sksk = $ip = $sksnk = $semester = 0;
				
				$skala = $this -> skala($m -> prodi_id);
				
				if(count($data) < 1) $nilai = false;
				else
				{
					foreach($data as $d) 
					{
						if($d -> aktif == 'y')
						{
							$cursks += $d -> sks;
							$cursksn += array_key_exists($d -> nilai, $skala) ? $skala[$d -> nilai]['angka'] * $d -> sks : 0; 
						}
						$sksk += $d -> sks;
						$sksnk += array_key_exists($d -> nilai, $skala) ? $skala[$d -> nilai]['angka'] * $d -> sks : 0; 						
					}
					$ip = $cursks < 1 ? 0: round($cursksn / $cursks, 2);
					
					$ipk = $sksk < 1 ? 0: round($sksnk / $sksk, 2);
				}
				
				if(isset($m -> bimbingan))
				{
					if(!isset($awal[$m -> NIM]))
					{
						$awal[$m -> NIM] = $m -> bimbingan;
					}
					else
					{
						if(strtotime($awal[$m -> NIM]) >strtotime( $m -> bimbingan))
						{
							$awal[$m -> NIM] = $m -> bimbingan;
						}
					}
					
					if(!isset($akhir[$m -> NIM]))
					{
						$akhir[$m -> NIM] = $m -> bimbingan;
					}
					else
					{
						if(strtotime($akhir[$m -> NIM]) < strtotime($m -> bimbingan))
						{
							$akhir[$m -> NIM] = $m -> bimbingan;
						}
					}
				}
				else
				{
					$akhir[$m -> NIM] = $awal[$m -> NIM] = '';
				}
				
				if(isset($m -> pembimbing))
				{
					if(!isset($pemb1[$m -> NIM])) 
					{
						$pemb1[$m -> NIM] = $m -> pembimbing;
						$pemb2[$m -> NIM] = '';
					}
					else $pemb2[$m -> NIM] = $m -> pembimbing;
				}
				else
				{
					$pemb1[$m -> NIM] = $pemb2[$m -> NIM] = '';
				}
				
				$status_mhs_to_jenis_keluar = [
				1 => "Z",
				2 => 2,
				3 => 3,
				4 => 1,
				5 => 4,
				6 => 5,
				7 => 6,
				8 => 7,
				9 => "Z",
				10 => "Z",
				11 => "Z",
				12 => "Z",
				];
				
				$rdata[$m -> NIM] = [
				'NIM' => $m -> NIM, 
				'nama' => $m -> nama_mhs, 
				'jenisKeluar' => $status_mhs_to_jenis_keluar[$m -> statusMhs], 
				'tglKeluar' => $m -> tglKeluar, 
				'SKYudisium' => $m -> SKYudisium, 
				'tglSKYudisium' => isset($m -> tglSKYudisium) ? date('Y-m-d', strtotime($m -> tglSKYudisium))  : '', 
				'ipk' => $ipk, 
				'noIjazah' => $m -> noIjazah, 
				'judulSkripsi' => isset($m -> judul) ? $m -> judul : '', 
				'awalBimbingan' => $awal[$m -> NIM], 
				'akhirBimbingan' => $akhir[$m -> NIM], 
				'jalurSkripsi' => 1, 
				'pembimbing1' => $pemb1[$m -> NIM],
				'pembimbing2' => $pemb2[$m -> NIM],
				'kode_dikti' => $m -> kode_dikti
				];
			}
			
			if($rdata == null) return Redirect::route('export.dikti.kelulusan.get') -> with('warning', 'Data tidak ditemukan');
			$title = 'PDDIKTI KELULUSAN '. $prodi -> singkatan;
			// $this -> toXlsx(str_slug($title), $title, 'mahasiswa.export.dikti_kelulusan_tpl', $rdata);
			return \Excel::download(new \Siakad\Exports\DataExport('mahasiswa.export.dikti_kelulusan_tpl', $rdata), str_slug($title) . '.xlsx');	
		}
		
		public function getExportAkm()
		{
			$format = ['xlsx' => 'Microsoft Excel'];
			$prodi = $this -> getGolongan('prodi', false);
			$tapel = $this -> getGolongan('tapel', false);
			return view('mahasiswa.export.dikti_akm', compact('format', 'prodi', 'tapel'));
		}
		public function postExportAkm(Request $request)
		{
			$input = $request -> all();
			$tapel = \Siakad\Tapel::whereId($input['tapel']) -> first();
			$prodi = \Siakad\Prodi::whereId($input['prodi']) -> first();
			
			$mahasiswa = Mahasiswa::where('statusMhs', '1') -> where('prodi_id', $prodi -> id) -> get();					
			foreach($mahasiswa as $m)
			{
				$data = \Siakad\Nilai::dataKHS($m -> NIM) -> get();
				$nilai = true;
				$cursks = $cursksn = $ipk = $sksk = $ip = $sksnk = $semester = $disc = 0;
				
				$skala = $this -> skala($m -> prodi_id);
				
				if(count($data) < 1) $nilai = false;
				else
				{
					foreach($data as $d) 
					{
						if($d -> nama2 == $tapel -> nama2)
						{
							$cursks += $d -> sks;
							$cursksn += array_key_exists($d -> nilai, $skala) ? $skala[$d -> nilai]['angka'] * $d -> sks : 0; 
						}
						// diskon sks jika nilai blm masuk;
						if(!array_key_exists($d -> nilai, $skala)) $disc += $d -> sks;
						$sksk += $d -> sks;
						$sksnk += array_key_exists($d -> nilai, $skala) ? $skala[$d -> nilai]['angka'] * $d -> sks : 0; 
						
					}
					$ip = $cursks < 1 ? 0 : number_format($cursksn / $cursks, 2);
					
					$div = ($sksk - $disc) < 1 ? $sksk : ($sksk - $disc);
					$ipk = $sksk < 1 ? 0 : number_format($sksnk / $div, 2);
				}
				$status = isset(config('custom.pilihan.statusMhs2')[$m -> statusMhs]) ? config('custom.pilihan.statusMhs2')[$m -> statusMhs] : 'K';
				$rdata[$m -> NIM] = ['NIM' => $m -> NIM, 'nama' => $m -> nama, 'semester' => $tapel -> nama2, 'sks' => $cursks, 'ip' => $ip, 'sksk' => $sksk, 'ipk' => $ipk, 
				'status' => $status, 'kode_dikti' => $prodi -> kode_dikti];
			}
			
			$title = 'PDDIKTI AKM '. $prodi -> singkatan . ' ' . $tapel -> nama2;
			// $this -> toXlsx(str_slug($title), $title, 'mahasiswa.export.dikti_akm_tpl', $rdata);
			return \Excel::download(new \Siakad\Exports\DataExport('mahasiswa.export.dikti_akm_tpl', $rdata), str_slug($title) . '.xlsx');	
		}
		
		//EMIS
		public function getExport()
		{
			$format = ['xlsx' => 'Microsoft Excel'];
			$angkatan = $this -> getGolongan('angkatan', false);
			return view('mahasiswa.export.emis', compact('format', 'angkatan'));
		}
		
		public function postExport(Request $request)
		{
			$input = $request -> all();
			$result = $this -> dataMahasiswa($input['angkatan']);
			
			$title = 'Form PTKI (Mahasiswa Reguler '. $input['angkatan'] .' )';
			// $this -> toXlsx(str_slug($title), $title, 'mahasiswa.export.emis_tpl', $result);
			return \Excel::download(new \Siakad\Exports\DataExport('mahasiswa.export.emis_tpl', $result), str_slug($title) . '.xlsx');	
		}
		
		public function getExportLulusan()
		{
			$result = $this -> dataMahasiswa(null, true);
			
			$title = 'Form PTKI (Lulusan)';
			// $this -> toXlsx(str_slug($title), $title, 'mahasiswa.export.emis_lulusan_tpl', $result);
			return \Excel::download(new \Siakad\Exports\DataExport('mahasiswa.export.emis_lulusan_tpl', $result), str_slug($title) . '.xlsx');	
		}
		
		private function dataMahasiswa($angkatan=null, $lulus=false)
		{
			$result = [];
			$penghasilan = [
			1	=> 'Di bawah  Rp 1.000.000',
			2	=> 'Rp 1.000.00 - Rp 2.000.000',			
			3	=> 'Rp 2.000.001 - Rp 4.000.000',		
			4	=> 'Rp 4.000.001 - Rp 6.000.000',		
			5	=> 'Di atas Rp 6.000.000',
			];
			
			$pekerjaan = [
			'01' => 'Tidak Bekerja',
			'02' => 'Pensiunan/Almarhum',
			'03' => 'PNS (selain poin 05 dan 10)',
			'04' => 'TNI/Polisi',
			'05' => 'Guru/Dosen',
			'06' => 'Pegawai Swasta',
			'07' => 'Pengusaha/Wiraswasta',
			'08' => 'Pengacara/Hakim/Jaksa/Notaris',
			'09' => 'Seniman/Pelukis/Artis/Sejenis',
			'10' => 'Dokter/Bidan/Perawat',
			'11' => 'Pilot/Pramugari',
			'12' => 'Pedagang',
			'13' => 'Petani/Peternak',
			'14' => 'Nelayan',
			'15' => 'Buruh (Tani/Pabrik/Bangunan)',
			'16' => 'Sopir/Masinis/Kondektur',
			'17' => 'Politikus',
			'18' => 'Lainnya',			
			];
			
			$pendidikan = [
			0 => 'Tidak berpendidikan formal',
			1 => '<= SLTA',
			2 => 'Diploma',
			3 => 'S1',
			4 => 'S2',
			5 => 'S3'
			];
			
			$mahasiswa = Mahasiswa::exportEMIS($angkatan, $lulus) -> get();
			
			if(!$mahasiswa or null === $mahasiswa) return Redirect::route('mahasiswa.export.emis.get') -> with('warning', 'Data tidak ditemukan');
			
			foreach($mahasiswa as $m)
			{
				$data = \Siakad\Nilai::dataKHS($m -> NIM) -> get();
				$nilai = true;
				$cursks = $cursksn = $ipk = $sksk = $ip = $sksnk = $semester = 0;
				
				$skala = $this -> skala($m -> prodi_id);
				
				if(count($data) < 1) $nilai = false;
				else
				{
					foreach($data as $d) 
					{
						if($d -> aktif == 'y')
						{
							$cursks += $d -> sks;
							$cursksn += array_key_exists($d -> nilai, $skala) ? $skala[$d -> nilai]['angka'] * $d -> sks : 0; 
						}
						$sksk += $d -> sks;
						$sksnk += array_key_exists($d -> nilai, $skala) ? $skala[$d -> nilai]['angka'] * $d -> sks : 0; 						
					}
					$ip = $cursks < 1 ? 0: round($cursksn / $cursks, 2);
					
					$ipk = $sksk < 1 ? 0: round($sksnk / $sksk, 2);
				}	
				
				$tl = isset($m -> tglLahir) ? explode('-', $m -> tglLahir) : [1,1,1970];
				$tl = isset($tl[1]) ? $tl : [1,1,1970];
				
				$ti = isset($m -> tglIjazah) ? explode('-', $m -> tglIjazah) : [1,1,1970];
				$ti = isset($ti[1]) ? $ti : [1,1,1970];
				
				$tm = isset($m -> tglMasuk) ? explode('-', $m -> tglMasuk) : [1,1,1970];
				$jk = $m -> jenisKelamin == 'L' ? 1 : 0;
				
				switch($m -> penghasilanAyah)
				{
					case 1:	
					case 2:
					$ph = 1;
					break;
					
					case 3:
					$ph = 2;
					break;
					
					case 4:
					$ph = 3;
					break;
					
					default:
					$ph = 5;
				}
				
				$pk = [1 => '01', '14', '13', '13', '03', '06', '12', '12', '07', '07', '15', '02', 98 => '02', 99 => '18'];
				$pd = [
				1 => 0, 
				1, 1, 1, 1, 1, 1, 1, 1, 1,
				20 => 2, 21 => 2, 22 => 2, 23 => 2,
				30 => 3,
				35 => 4,
				40 => 5
				];
				$pda = isset($pd[$m -> pendidikanAyah]) ? $pd[$m -> pendidikanAyah] : 0;
				$pdi = isset($pd[$m -> pendidikanIbu]) ? $pd[$m -> pendidikanIbu] : 0;
				$jenjang = array_flip(config('custom.pilihan.emis.jenjang'));
				
				if(!$lulus)
				{
					$provs = array_flip(config('custom.pilihan.emis.provinsi'));
					
					$alamat = '';
					if($m['jalan'] != '') $alamat .= 'Jl. ' . $m['jalan'] . ' ';
					if($m['dusun'] != '') $alamat .= $m['dusun'] . ' ';
					if($m['rt'] != '') $alamat .= 'RT ' . $m['rt'] . ' ';
					if($m['rw'] != '') $alamat .= 'RW ' . $m['rw'] . ' ';
					if($m['kelurahan'] != '') $alamat .= $m['kelurahan'] . ' ';
					if($m['id_wil'] != '') 
					{
						$data = \Siakad\Wilayah::dataKecamatan($m['id_wil']) -> first();
						if($data)
						$alamat .= trim($data -> kec) . ' ';
					}
					if($m['kodePos'] != '') $alamat .= $m['kodePos'];
					
					$kab = isset($data -> kab) ? trim($data -> kab) : 'Malang';
					$nm_prov = isset($data -> prov) ? substr(trim($data -> prov), 6) : 'Jawa Timur';
					$kd_prov = isset($provs[$nm_prov]) ?$provs[$nm_prov] : 35;
					if($m -> semesterMhs == 1)
					{
						$status = 4;
						$ta = isset($m -> tglMasuk) ? explode('-', $m -> tglMasuk) : [1,1,1970];
					}
					else
					{
						if($m -> statusMhs == 1)
						{
							$status = 1;
							$ta = isset($m -> tgl_aktif) ? explode('-', $m -> tgl_aktif) : explode('-', $m -> tglMasuk);						
						}
						elseif($m -> statusMhs == 11)
						{
							$status = 2;
							$ta = isset($m -> tgl_cuti) ? explode('-', $m -> tgl_cuti) : [1,1,1970];						
						}
						elseif($m -> statusMhs == 12)
						{
							$status = 3;
							$ta = isset($m -> tgl_cuti) ? explode('-', $m -> tgl_cuti) : [1,1,1970];
						}
						else
						{
							$status = 3;
							$ta = isset($m -> tgl_cuti) ? explode('-', $m -> tgl_cuti) : explode('-', $m -> tglMasuk);	
						}
					}
					$ta = isset($ta[1]) ? $ta : [1,1,1970];
				}
				
				if(!$lulus)
				{
					$result[] = [
					$m -> nama, $m -> NIM, $m -> tmpLahir, 
					intval($tl[0]), intval($tl[1]), intval($tl[2]),
					$jk, 1, intval($tm[0]), intval($tm[1]), intval($tm[2]),
					$status, intval($ta[0]), intval($ta[1]), intval($ta[2]),
					$jenjang[$m -> strata], 'Tarbiyah', $m -> prodi, $m -> semesterMhs,
					$m -> asal_pendidikan, $ipk,
					$ph, $pk[$m -> pekerjaanAyah], $pk[$m -> pekerjaanIbu],
					$pda, $pdi, 
					0,0,0, 
					$alamat, $kab, $kd_prov, $nm_prov,
					$m -> NIRM, $m -> NISN
					];
				}
				else
				{
					$result[] = [
					$m -> nama, 
					$m -> NIM,
					$m -> tmpLahir, 
					intval($tl[0]), intval($tl[1]), intval($tl[2]),
					$jk, 1, 
					$m -> asal_pendidikan,
					$jenjang[$m -> strata], 'Tarbiyah', $m -> prodi, 
					$sksk,
					$m -> semesterMhs,
					$ipk,
					$m -> noIjazah,
					intval($ti[0]), intval($ti[1]), intval($ti[2]),
					$ph, $pk[$m -> pekerjaanAyah], $pk[$m -> pekerjaanIbu],
					$pda, $pdi, 
					5, 7, 1,
					0, 0, 0
					];
				}
				
			}
			
			return $result;
		}
		
		public function printTranskrip($id)
		{
			$data = \Siakad\Nilai::transkrip($id) -> get();
			$mahasiswa = Mahasiswa::find($id);
			$skala = $this -> skala($mahasiswa -> prodi_id);
			return view('mahasiswa.printtranskrip', compact('data', 'mahasiswa', 'skala'));
		}
		
		public function transkrip($id)
		{
			$data = \Siakad\Nilai::transkrip($id) -> get();
			$mahasiswa = Mahasiswa::find($id);
			return view('mahasiswa.transkrip', compact('data', 'mahasiswa'));
		}
		
		public function adminFundingTypeForm()
		{
			$angkatan = $this -> getGolongan('angkatan');
			$semester = $this -> getGolongan('semester');
			$kelas = $this -> getGolongan('program');
			$jenis = config('custom.pilihan.jenisPembayaran');
			$mahasiswa = Mahasiswa::where('statusMhs', '1') -> orderBy('NIM') -> get();
			return view('mahasiswa.adminpembiayaan', compact('angkatan', 'semester', 'kelas','jenis', 'mahasiswa'));
		}
		
		public function adminFundingTypeMember(Request $request)
		{
			$input = $request -> all();
			$mahasiswa = Mahasiswa::where('statusMhs', '1') -> where('jenisPembayaran', $input['jenis']) -> orderBy('NIM') -> get();;
			return \Response::json(['mahasiswa' => $mahasiswa]);
		}
		public function adminFundingTypeUpdate(Request $request)
		{
			$input = $request -> all();
			$response = [];
			
			$result = Mahasiswa::whereIn('id', $input['id']) -> update(['jenisPembayaran' => $input['jenis']]);
			if(!$result) return \Response::json([]);
			
			$from = Mahasiswa::where('statusMhs', '1');
			if($input['angkatan-from'] !== '-') $from = $from -> where('angkatan', $input['angkatan-from']);
			if($input['semester-from'] !== '-') $from = $from -> where('semesterMhs', $input['semester-from']);
			if($input['kelas-from'] !== '-') $from = $from -> where('kelasMhs', $input['kelas-from']);
			$from = $from -> orderBy('NIM') -> get();
			
			$to = Mahasiswa::where('statusMhs', '1') -> where('jenisPembayaran', $input['jenis']) -> orderBy('NIM') -> get();
			
			return \Response::json(['to' => $to, 'from' => $from]);
		}
		
		// Status
		public function adminStatusAnggota(Request $request)
		{
			$input = $request -> all();
			$mahasiswa = Mahasiswa::where('statusMhs', $input['status']) -> orderBy('NIM') -> get();;
			return \Response::json(['mahasiswa' => $mahasiswa]);
		}
		
		public function adminStatus()
		{
			$angkatan = $this -> getGolongan('angkatan');
			$semester = $this -> getGolongan('semester');
			$kelas = $this -> getGolongan('program');
			foreach(config('custom.pilihan.statusMhs') as $k => $v) if($k <=10) $status[$k] = $v;
			
			$mahasiswa = Mahasiswa::where('statusMhs', '1') -> orderBy('NIM') -> get();
			return view('mahasiswa.adminstatus', compact('angkatan', 'semester', 'kelas','status', 'mahasiswa'));
		}
		
		public function adminStatusUpdate(Request $request)
		{
			$input = $request -> all();
			$response = [];
			
			$result = Mahasiswa::whereIn('id', $input['id']) -> update(['statusMhs' => $input['status']]);
			if(!$result) return \Response::json([]);
			
			$from = Mahasiswa::where('id', '>', 0);
			if($input['angkatan-from'] !== '-') $from = $from -> where('angkatan', $input['angkatan-from']);
			if($input['semester-from'] !== '-') $from = $from -> where('semesterMhs', $input['semester-from']);
			if($input['kelas-from'] !== '-') $from = $from -> where('kelasMhs', $input['kelas-from']);
			$from = $from -> orderBy('NIM') -> get();
			
			$to = Mahasiswa::where('statusMhs', $input['status']) -> orderBy('NIM') -> get();
			
			return \Response::json(['to' => $to, 'from' => $from]);
		}
		
		// Perwalian
		
		public function jumlahMahasiswaPerwalian()
		{
			$id = [];
			$dosen = Mahasiswa::jumlahMahasiswaPerwalian() -> get();
			foreach($dosen as $d) $id[] = $d -> id;
			
			$dosen2 = \Siakad\Dosen::whereNotIn('id', $id) -> where('id', '>', 0) -> orderBy('nama') -> get();
			
			return view('dosen.perwalian.jumlah', compact('dosen', 'dosen2'));
		}
		
		public function adminCustodianAnggota(Request $request)
		{
			$input = $request -> all();
			// $mahasiswa = Mahasiswa::where('statusMhs', '1') -> where('dosen_wali', $input['dosen']) -> orderBy('NIM') -> get();;
			$mahasiswa = Mahasiswa::where('dosen_wali', $input['dosen']) -> orderBy('NIM') -> get();;
			return \Response::json(['mahasiswa' => $mahasiswa]);
		}
		
		public function adminCustodian()
		{
			$angkatan = $this -> getGolongan('angkatan');
			$semester = $this -> getGolongan('semester');
			$kelas = $this -> getGolongan('program');
			$dosen = $this -> getDosenSelection();
			// $mahasiswa = Mahasiswa::where('statusMhs', '1') -> orderBy('NIM') -> get();
			$mahasiswa = Mahasiswa::orderBy('NIM') -> get();
			return view('mahasiswa.adminperwalian', compact('angkatan', 'semester', 'kelas','dosen', 'mahasiswa'));
		}
		
		public function adminCustodianUpdate(Request $request)
		{
			$input = $request -> all();
			$response = [];
			
			$result = Mahasiswa::whereIn('id', $input['id']) -> update(['dosen_wali' => $input['dosen']]);
			if(!$result) return \Response::json([]);
			
			$from = Mahasiswa::where('statusMhs', '1');
			if($input['angkatan-from'] !== '-') $from = $from -> where('angkatan', $input['angkatan-from']);
			if($input['semester-from'] !== '-') $from = $from -> where('semesterMhs', $input['semester-from']);
			if($input['kelas-from'] !== '-') $from = $from -> where('kelasMhs', $input['kelas-from']);
			$from = $from -> orderBy('NIM') -> get();
			
			// $to = Mahasiswa::where('statusMhs', '1') -> where('dosen_wali', $input['dosen']) -> orderBy('NIM') -> get();
			$to = Mahasiswa::where('dosen_wali', $input['dosen']) -> orderBy('NIM') -> get();
			
			return \Response::json(['to' => $to, 'from' => $from]);
		}
		
		public function custodian(Request $request)
		{
			$auth = \Auth::user();
			if($auth -> role_id == 128)
			{
				// $mahasiswa = \Siakad\Dosen::getMahasiswa($auth -> authable_id) -> paginate(30);
				$mahasiswa = \Siakad\Dosen::getMahasiswa($auth -> authable_id) -> get();
				return view('dosen.perwalian', compact('mahasiswa'));
			}
			
			// Prodi
			$input = $request -> all();
			$prodi_data = \Siakad\Prodi::where('singkatan', $auth -> role -> sub) -> first();
			$prodi = $prodi_data -> nama;
			
			$tmp = \Siakad\Dosen::where('id', '>', 0) -> orderBy('nama') -> get();
			
			$dosen = $this -> getDosenSelection();
			
			$mahasiswa = Mahasiswa::with('prodi', 'kelas', 'dosenwali') -> where('prodi_id', $prodi_data -> id);
			if(isset($input['dosen']) and $input['dosen'] > 0) $mahasiswa = $mahasiswa -> where('dosen_wali', $input['dosen']); 
			$mahasiswa = $mahasiswa -> paginate(30);
			// $mahasiswa = $mahasiswa -> get();
			
			return view('mahasiswa.perwalian', compact('mahasiswa', 'prodi', 'dosen'));
		}
		
		public function transfer()
		{
			$angkatan = $this -> getGolongan('angkatan');
			$semester = $this -> getGolongan('semester');
			$kelas = $this -> getGolongan('program');
			
			$mahasiswa = Mahasiswa::orderBy('NIM') -> get();
			return view('mahasiswa.transfer', compact('angkatan', 'semester', 'kelas', 'mahasiswa'));
		}
		
		public function filterMahasiswa(Request $request)
		{
			$input = $request -> all();
			$mahasiswa = Mahasiswa::where('statusMhs', '1');
			
			if($input['angkatan'] !== '-') $mahasiswa = $mahasiswa -> where('angkatan', $input['angkatan']);
			if($input['semester'] !== '-') $mahasiswa = $mahasiswa -> where('semesterMhs', $input['semester']);
			if($input['kelas'] !== '-') $mahasiswa = $mahasiswa -> where('kelasMhs', $input['kelas']);
			$mahasiswa = $mahasiswa -> orderBy('NIM', 'desc') -> get();
			return \Response::json(['mahasiswa' => $mahasiswa]);
		}
		
		public function doTransfer(Request $request)
		{
			$input = $request -> all();
			$response = $mod = [];
			if($input['semester'] == '-' and $input['kelas'] == '-') return \Response::json([]);
			if($input['semester'] !== '-') $mod['semesterMhs'] = $input['semester'];
			if($input['kelas'] !== '-') $mod['kelasMhs'] = $input['kelas'];
			
			$result = Mahasiswa::whereIn('id', $input['id']) -> update($mod);
			if(!$result) return \Response::json([]);
			
			$from = Mahasiswa::where('statusMhs', '1');
			if($input['angkatan-from'] !== '-') $from = $from -> where('angkatan', $input['angkatan-from']);
			if($input['semester-from'] !== '-') $from = $from -> where('semesterMhs', $input['semester-from']);
			if($input['kelas-from'] !== '-') $from = $from -> where('kelasMhs', $input['kelas-from']);
			$from = $from -> orderBy('NIM') -> get();
			
			$to = Mahasiswa::where('statusMhs', '1');
			if($input['semester'] !== '-') $to = $to -> where('semesterMhs', $input['semester']);
			if($input['kelas'] !== '-') $to = $to -> where('kelasMhs', $input['kelas']);
			$to = $to -> orderBy('NIM') -> get();
			
			return \Response::json(['to' => $to, 'from' => $from]);
		}
		
		public function printMyKhs($ta = null)
		{
			$user = \Auth::user();
			if($user -> role_id != '512') abort(404);
			
			$data = $this->khs($user -> authable -> NIM, $ta);
			$skala = $this -> skala($data['mhs'] -> prodi_id);
			$data['skala'] = $skala;
			
			if($ta === null) return view('mahasiswa.printall', $data);
			return view('mahasiswa.print', $data);
		}
		public function viewMyKhs($ta = null)
		{
			$user = \Auth::user();
			if($user -> role_id != '512') abort(404);
			
			$data = $this->khs($user -> authable -> NIM, $ta);
			
			$skala = $this -> skala($data['mhs'] -> prodi_id);
			$data['skala'] = $skala;
			
			return view('mahasiswa.khs', $data);
		}
		
		public function cetakKhs($nim, $ta = null)
		{
			$data = $this->khs($nim, $ta);
			$skala = $this -> skala($data['mhs'] -> prodi_id);
			$data['skala'] = $skala;
			
			if($ta == null) return view('mahasiswa.printall', $data);
			return view('mahasiswa.print', $data);
		}
		
		public function viewKhs($nim, $ta = null)
		{
			$data = $this->khs($nim, $ta);		
			$skala = $this -> skala($data['mhs'] -> prodi_id);
			$data['skala'] = $skala;
			
			return view('mahasiswa.khs', $data);
		}
		
		public function khs($nim, $ta = null)
		{
			$all = true;
			if($ta != null) 
			{
				$all = false;
			}
			
			$mhs = Mahasiswa::with('prodi') -> with('kelas') -> where('NIM', $nim) -> first();
			
			$semesters = [];
			$data = \Siakad\Nilai::dataKHS($nim, $ta) -> get();
			if(count($data) < 1) $nilai = [];
			else
			{
				foreach($data as $d) 
				{					
					$nilai[$d -> smt][] = [
					'ta' => $d -> nama,
					'ta2' => $d -> nama2,
					'taid' => $d -> id,
					'kode' => $d -> kode,
					'kelas' => $d -> kelas2,
					'matkul' => $d -> matkul,
					'mahasiswa_id' => $d -> mhsid,
					'matkul_tapel_id' => $d -> matkul_tapel_id,
					'nilai' => $d -> nilai,
					'sks' => $d -> sks
					];
					$semesters[$d -> smt] = $d -> nama2;
				}
				ksort($nilai);
			}
			
			//CHECK TAGIHAN
			if(count($semesters) > 0)
			{
				foreach($semesters as $t)
				{
					//tagihan
					if(!\Cache::has('tagihan_semester_for_' . $mhs -> id . '_generated'))
					{
						$this -> generateTagihan($mhs -> id, true); //tagihan per-semester
						\Cache::put('tagihan_semester_for_' . $mhs -> id . '_generated', true, 60);
					}
					$locked[$t] = ['uts' => true, 'uas' => true];
					$tagihan = $this -> getTagihan($mhs, 1, $t); // get tagihan SPP semester ini
					
					if($tagihan -> count())
					{
						$tagihan = $tagihan[0];
						$persen_pembayaran = $tagihan -> jumlah > 0 ? $tagihan -> bayar / $tagihan -> jumlah * 100 : 0;
						if($persen_pembayaran >= $tagihan -> persen_syarat_uts) $locked[$t]['uts'] = false;
						if($tagihan -> privilege_uts == 'y') $locked[$t]['uts'] = false;
						
						if($persen_pembayaran >= $tagihan -> persen_syarat_uas) $locked[$t]['uas'] = false;
						if($tagihan -> privilege_uas == 'y') $locked[$t]['uas'] = false;
					}
					else //BEASISWA NO SPP
					{
						$locked[$t] = ['uts' => false, 'uas' => false];						
					} 
				}
			}
			
			return compact('mhs', 'nilai', 'all', 'locked');
		}
		
		public function angkatan(Request $request, $docheck = null)
		{
			$input = $request -> All();
			if($docheck == null) // ANGGOTA KELAS KULIAH -> NILAI
			{
				$where = '';
				$data = ['id' => $input['id'], 'prodi_id' => $input['prodi_id'], 'semester_matkul' => $input['semester']];
				if($input['tahun'] != '-') 
				{
					$where = '`angkatan` = :angkatan AND';
					$data['angkatan'] = $input['tahun'];
				}
				$mahasiswa = \DB::select('
				SELECT `mahasiswa`.* 
				FROM `mahasiswa` 
				WHERE ' . $where . ' `mahasiswa`.`id` NOT IN (
				SELECT `mahasiswa_id`
				FROM `nilai`
				WHERE `nilai`.`matkul_tapel_id` = :id
				) 
				AND `mahasiswa`.`prodi_id` = :prodi_id 	
				AND `mahasiswa`.`semesterMhs` = :semester_matkul 	
				ORDER BY `NIM` ASC
				', compact('data', 'skala'));
			}
			else // MAHASISWA -> SEMESTER
			{
				$mahasiswa = Mahasiswa::where('angkatan', $input['tahun']) -> orderBy('NIM') -> get();
			}
			return \Response::json(['mahasiswa' => $mahasiswa]);
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
				return redirect( '/mahasiswa/search/'.$qclean );
			}
			return Redirect::back() -> withErrors(['q' => 'Isi kata kunci pencarian yang diinginkan terlebih dahulu']);
		} 
		
		/**
			* Search
		**/
 		public function search(Request $request)
		{			
			$aktif = \Siakad\Tapel::whereAktif('y') -> first();
			
			$query = $request -> get('q');
			
			$semester = $this -> getGolongan('semester');			
			$status = $this -> getGolongan('status');
			$prodi = $this -> getGolongan('prodi');
			$program = $this -> getGolongan('program');
			$semester = $this -> getGolongan('semester');
			$angkatan = $this -> getGolongan('angkatan');
			
			$mahasiswa = Mahasiswa::with('prodi', 'kelas', 'authInfo', 'krs');
			
			if(\Auth::user() -> role -> name == 'Prodi')
			{
				$prodi_id = \Siakad\Prodi::where('singkatan', \Auth::user() -> role -> sub) -> pluck('id');
				$mahasiswa = $mahasiswa -> where('prodi_id', $prodi_id);
				$prodi = null;
			}
			
			$mahasiswa = $mahasiswa 
			-> where('nama', 'like', '%' . $query . '%') 
			-> orWhere('NIM', 'like', '%' . $query . '%') 
			-> paginate(50);
			
			$message = 'Ditemukan ' . $mahasiswa -> total() . ' hasil pencarian';
			return view('mahasiswa.index', compact('message', 'mahasiswa', 'semester', 'status', 'prodi', 'program', 'angkatan', 'aktif', 'request'));
		} 
		
		//filter on Daftar mahasiswa
		public function filter(Request $request)
		{
			$input = $request -> all();
			$aktif = \Siakad\Tapel::whereAktif('y') -> first();
			
			$status = $this -> getGolongan('status');
			$prodi = $this -> getGolongan('prodi', true, true);
			$program = $this -> getGolongan('program');
			$semester = $this -> getGolongan('semester');
			$angkatan = $this -> getGolongan('angkatan');
			
			$mahasiswa = Mahasiswa::with('prodi', 'kelas', 'authInfo', 'krs') 
			-> where('NIM', '<>', '');
			if(isset($input['angkatan']) && $input['angkatan'] !== '-') $mahasiswa = $mahasiswa -> where('angkatan', $input['angkatan']);
			if(isset($input['semester']) && $input['semester'] !== '-') $mahasiswa = $mahasiswa -> where('semesterMhs', $input['semester']);
			if(isset($input['status']) && $input['status'] !== '-') $mahasiswa = $mahasiswa -> where('statusMhs', $input['status']);
			if(isset($input['program']) && $input['program'] !== '-') $mahasiswa = $mahasiswa -> where('kelasMhs', $input['program']);
			
			if(\Auth::user() -> role -> name == 'Prodi')
			{
				$prodi_id = \Siakad\Prodi::where('singkatan', \Auth::user() -> role -> sub) -> pluck('id');
				$mahasiswa = $mahasiswa -> where('prodi_id', $prodi_id);
				$prodi = null;
			}
			else
			{
				if(isset($input['prodi']) && $input['prodi'] !== '-') $mahasiswa = $mahasiswa -> where('prodi_id', $input['prodi']);
			}
			
			$perpage = intval($request -> get('perpage')) > 0 ? $request -> get('perpage') : 200;
			$mahasiswa = $mahasiswa 
			-> orderBy('angkatan', 'desc') 
			->orderBy('NIM', 'desc') 
			-> paginate($perpage);
			
			return view('mahasiswa.index', compact('mahasiswa', 'semester', 'status', 'prodi', 'program', 'angkatan', 'aktif', 'request'));
		}
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index(Request $request)
		{
			$aktif = \Siakad\Tapel::whereAktif('y') -> first();
			$status = $this -> getGolongan('status');
			$prodi = $this -> getGolongan('prodi', true, true);
			$program = $this -> getGolongan('program');
			$semester = $this -> getGolongan('semester');
			$angkatan = $this -> getGolongan('angkatan');
			$mahasiswa = Mahasiswa::with('prodi', 'kelas', 'authInfo', 'tagihan.setup', 'krs');
			
			if(\Auth::user() -> role -> name == 'Prodi')
			{
				$prodi_id = \Siakad\Prodi::where('singkatan', \Auth::user() -> role -> sub) -> pluck('id');
				$mahasiswa = $mahasiswa -> where('prodi_id', $prodi_id);
				$prodi = null;
			}
			
			$mahasiswa = $mahasiswa -> orderBy('angkatan', 'desc') -> orderBy('NIM', 'desc') -> paginate(25);
			
			return view('mahasiswa.index', compact('mahasiswa', 'semester', 'status', 'prodi', 'program', 'angkatan', 'aktif', 'request'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$pembiayaan_awal = config('custom.pilihan.jenisPembiayaanAwal');
			$prodi = \Siakad\Prodi::pluck('nama', 'id');
			$kelas = \Siakad\Kelas::pluck('nama', 'id');
			
			$dosen = $this -> getDosenSelection();
			
			$tapel = $this -> getTapelSelection('desc', true, 'nama2');
			
			$negara = Cache::get('negara', function() {
				$negara = \Siakad\Negara::orderBy('nama') -> pluck('nama', 'kode');
				Cache::put('negara', $negara, 60);
				return $negara;
			});
			
			$wilayah = Cache::get('wilayah', function() {
				$wilayah = \Siakad\Wilayah::kecamatan() -> get();
				$tmp[1] = '';
				foreach($wilayah as $kec)
				{
					$tmp[$kec -> id_wil] = $kec['kec'] . ' - ' . $kec['kab'] . ' - ' . $kec['prov'];
				}
				Cache::put('wilayah', $tmp, 60);
				return $tmp;
			});
			
			return view('mahasiswa.create', compact('prodi', 'kelas', 'dosen', 'wilayah', 'negara', 'tapel', 'pembiayaan_awal'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{			
			$this -> rules['NIM'] = ['required', 'unique:mahasiswa,NIM'];
			$this -> validate($request, $this -> rules);
			$all = $request -> all();
			$input = array_except($all, ['username', 'password']);
			
			if(!isset($input['angkatan'])) $input['angkatan'] = substr($input['NIM'], 0, 4);
			
			$tapel = \Siakad\Tapel::where('nama2', $input['tapelMasuk']) -> first();
			$input['tglMasuk'] = isset($tapel -> mulai) ? date('d-m-Y', strtotime($tapel -> mulai)) : date('d-m-Y');
			$mhs = Mahasiswa::create($input);
			
			$authinfo['username'] = (isset($all['username']) AND $all['username'] != '') ? $all['username'] : ((isset($all['NIM']) AND $all['NIM'] != '') ? $all['NIM'] : str_random(6));
			$password = (isset($all['password']) AND $all['password'] != '') ? $all['password'] : str_random(6);
			$authinfo['password'] = bcrypt($password);
			$authinfo['role_id'] = 512;
			$authinfo['authable_id'] = $mhs -> id;
			$authinfo['authable_type'] = 'Siakad\Mahasiswa';
			\Siakad\User::create($authinfo);
			
			//-------------- SENAYAN 7 MEMBER REGISTRATION ----------------//
			$gender = isset($input['jenisKelamin']) AND $input['jenisKelamin'] == 'L' ? 1 : 0;
			
			$today = date('Y-m-d');
			
			$tmp = explode('-', $input['tglLahir']);
			$birth_date = $tmp[2] . '-' . $tmp[1] . '-' . $tmp[0];
			
			$address = '';
			if($input['jalan'] != '') $address .= 'Jl. ' . $input['jalan'] . ' ';
			if($input['dusun'] != '') $address .= $input['dusun'] . ' ';
			if($input['rt'] != '') $address .= 'RT ' . $input['rt'] . ' ';
			if($input['rw'] != '') $address .= 'RW ' . $input['rw'] . ' ';
			if($input['kelurahan'] != '') $address .= $input['kelurahan'] . ' ';
			if($input['id_wil'] != '') 
			{
				$data = \Siakad\Wilayah::dataKecamatan($input['id_wil']) -> first();
				if($data) $address .= trim($data -> kec) . ' ' . trim($data -> kab) . ' ' . trim($data -> prov) . ' ';
			}
			if($input['kodePos'] != '') $address .= $input['kodePos'];
			
			$member = [
			'member_id' => $authinfo['username'],
			'member_name' => $input['nama'],
			'gender' => $gender,
			'birth_date' => $birth_date,
			'member_type_id' => 1,
			'member_address' => $address,
			'member_phone' => $input['telp'],
			'member_since_date' => $today,
			'register_date' => $today,
			'input_date' => $today,
			'last_update' => $today,
			'expire_date' => date('Y-m-d', strtotime("+4 years")),
			'mpasswd' => md5($password)
			];
			
			try {
				\Siakad\SenayanMembership::create($member);
				} catch (\Exception $e) {
				return Redirect::route('mahasiswa.index') 
				-> with('message', 'Data mahasiswa berhasil dimasukkan. Username: ' . $authinfo['username'] . ' Password: ' . $password)
				-> with('warning', 'Pendaftaran keanggotaan perpustakaan Gagal. Harap daftarkan Mahasiswa secara manual.');
			}
			return Redirect::route('mahasiswa.index') 
			-> with('message', 'Data mahasiswa berhasil dimasukkan. Username: ' . $authinfo['username'] . ' Password: ' . $password)
			-> with('success', 'Pendaftaran keanggotaan perpustakaan berhasil. Username: ' . $authinfo['username'] . ' Password: ' . $password);
			//-------------- END OF SENAYAN 7 MEMBER REGISTRATION ----------------//
		}
		
		/**
			* Display the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function show($id)
		{
			$mahasiswa = Mahasiswa::with('dosenwali', 'wisuda', 'cuti')-> find($id);
			if(!$mahasiswa) abort(404);
			
			$kurikulum = \Siakad\Kurikulum::where('angkatan', $mahasiswa -> angkatan)
			-> where('prodi_id', $mahasiswa -> prodi_id) -> first();
			
			$tapel = \Siakad\Tapel::whereNama2($mahasiswa -> tapelMasuk) -> first();
			
			$kec = '';
			{
				$data = \Siakad\Wilayah::dataKecamatan($mahasiswa['id_wil']) -> first();
				if($data)
				$kec = trim($data -> kec) . ' ' . trim($data -> kab) . ' ' . trim($data -> prov) . ' ';
			}
			return view('mahasiswa.show', compact('mahasiswa', 'kec', 'tapel', 'kurikulum'));
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$pembiayaan_awal = config('custom.pilihan.jenisPembiayaanAwal');
			$mahasiswa = Mahasiswa::find($id);
			$hasAccount = \Siakad\User::where('authable_id', $mahasiswa ->id) -> where('authable_type', 'Siakad\\Mahasiswa') -> exists();
			$kelas = \Siakad\Kelas::pluck('nama', 'id');
			
			$prodi = $this -> getProdiSelection('id', 'asc', false);
			$dosen = $this -> getDosenSelection();
			
			// $tapel = \Siakad\Tapel::orderBy('nama2') -> pluck('nama', 'nama2');	
			$tapel = $this -> getTapelSelection('desc', true, 'nama2');
			
			$negara = Cache::get('negara', function() {
				$negara = \Siakad\Negara::orderBy('nama') -> pluck('nama', 'kode');
				Cache::put('negara', $negara, 60);
				return $negara;
			});
			$wilayah = Cache::get('wilayah', function() {
				$wilayah = \Siakad\Wilayah::kecamatan() -> get();
				$tmp[1] = '';
				foreach($wilayah as $kec)
				{
					$tmp[$kec -> id_wil] = $kec['kec'] . ' - ' . $kec['kab'] . ' - ' . $kec['prov'];
				}
				Cache::put('wilayah', $tmp, 60);
				return $tmp;
			});
			return view('mahasiswa.edit', compact('mahasiswa', 'prodi', 'hasAccount', 'kelas', 'dosen', 'wilayah', 'negara', 'tapel', 'pembiayaan_awal'));
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
		$input = array_except($all, ['_method', 'username', 'password']);
		
		$mahasiswa = Mahasiswa::find($id);
		
		if((isset($all['username']) and $all['username'] != '') and (isset($all['password']) and $all['password'] != ''))
		{
		$authinfo['username'] = $all['username'];
		$authinfo['password'] = bcrypt($all['password']);
		$authinfo['role_id'] = 512;
		$authinfo['authable_id'] = $mahasiswa -> id;
		$authinfo['authable_type'] = 'Siakad\Mahasiswa';
		\Siakad\User::create($authinfo);
		}
		
		if(!isset($input['angkatan'])) $input['angkatan'] = substr($input['NIM'], 0, 4);
		
		$tapel = \Siakad\Tapel::where('nama2', $input['tapelMasuk']) -> first();
		$input['tglMasuk'] = isset($tapel -> mulai) ? date('d-m-Y', strtotime($tapel -> mulai)) : date('d-m-Y');
		
		// update AKM
		$tapel_aktif = \Siakad\Tapel::where('aktif', 'y') -> first();
		\Siakad\Aktivitas::where('tapel_id', $tapel_aktif -> id) -> where('mahasiswa_id', $mahasiswa -> id) -> update(['status' => $input['statusMhs']]);
		
		$mahasiswa-> update($input);
		
		return Redirect::route('mahasiswa.index', $mahasiswa->id) -> with('message', 'Data mahasiswa berhasil diperbarui.');
		}
		
		/**
		* Remove the specified resource from storage.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
		$mahasiswa = Mahasiswa::find($id);
		
		if($mahasiswa)
		{
		$mahasiswa -> delete();
		
		\Siakad\User::where('username', $mahasiswa -> NIM) -> delete();
		try {
		\Siakad\SenayanMembership::where('member_id', $mahasiswa -> NIM) -> delete();
		} catch (\Exception $e) {
		return Redirect::route('mahasiswa.index') -> with('success', 'Data mahasiswa berhasil dihapus.');
		}
		
		return Redirect::route('mahasiswa.index') -> with('success', 'Data mahasiswa berhasil dihapus.');
		}
		return Redirect::route('mahasiswa.index') -> with('warning', 'Data mahasiswa tidak ditemukan. Mohon periksa kembali');
		}
		}
		
				