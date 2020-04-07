<?php namespace Siakad\Http\Controllers;
	
	use Cache;
	use Siakad\Tapel;
	use Siakad\Informasi;
	use Siakad\FileEntry;
	use Siakad\Kurikulum;
	use Siakad\Aktivitas;
	use Siakad\Jadwal;
	use Siakad\Mahasiswa;
	
	class HomeController extends Controller
	{
		use \Siakad\MailTrait;
		
		/**
			* Create a new controller instance.
			*
			* @return void
		*/
		public function __construct()
		{
			$this->middleware('auth');
		}
		
		/**
			* Show the application dashboard to the user.
			*
			* @return Response
		*/
		public function index()
		{
			$auth= \Auth::user();
			$tapel_aktif = Tapel::whereAktif('y') -> first();	
			$hari = config('custom.hari');
			
			if($auth -> role_id == 1 or $auth -> role_id == 2) 
			{
				$informasi = Informasi::orderBy('id', 'desc') -> take(5) -> get();
				$files = false;
			}
			else
			{
				$informasi = Informasi::where('akses', 'like', '%"' .$auth -> role_id . '"%') ->  orderBy('id', 'desc') -> take(5) ->get();
				$files = FileEntry::orderBy('created_at', 'desc') -> where('akses', 'LIKE', '%"'. $auth -> role_id .'"%') -> get();	
			}
			
			$kurikulum = $akm = $jadwal = $tagihan_semester = null;
			$jadwal_terdekat = [];
			if($auth -> role_id == 512)
			{
				$kurikulum = Kurikulum::where('angkatan', $auth -> authable -> angkatan)
				-> where('prodi_id', $auth -> authable -> prodi_id) -> first();	
				
				$akm = Aktivitas::where('tapel_id', $tapel_aktif -> id) -> where('mahasiswa_id', $auth -> authable -> id) -> first();
				
				// Jadwal terdekat
				$tmp = Jadwal::JadwalKuliah('mahasiswa') -> with('matkul_tapel.tim_dosen') -> get();
				if($tmp)
				{
					$hariini = date('N');
					$jam = date('Hi');
					
					foreach($tmp as $j)
					{
						$jadwal[$j -> hari][str_replace(':', '', $j -> jam_mulai)] = $j;
					}
					
					if(isset($jadwal[$hariini])) $jadwal_terdekat = $jadwal[$hariini];
					else
					{
						foreach($hari as $i => $n)
						{
							$j = $i > 6 ? 1 : $i;
							if(isset($jadwal[$j]))
							{
								$jadwal_terdekat = $jadwal[$j];
								break;		
							}
						}
					}
				}
				
				//Tagihan Semester ini
				$tagihan_semester = \Siakad\Tagihan::tanggungan($auth -> authable -> id, $tapel_aktif -> nama2) -> get();
			}
			
			$keuangan = [];
			if($auth -> role_id == 4)
			{				
				$keuangan['total_tagihan'] = Cache::get('tagihan', function(){
					
					$tmp = \DB::select('
					SELECT sum(jumlah) as t_jumlah, sum(bayar) as t_bayar 
					from tagihan
					inner join mahasiswa on mahasiswa.id = mahasiswa_id
					where mahasiswa.angkatan >= 2014
					')[0];
					
					Cache::put('tagihan', $tmp, 15);
					return $tmp;
				});
				
				$keuangan['total_tagihan_semester'] = Cache::get('tagihan_semester', function(){
					$tapel = Tapel::whereAktif('y') -> first();
					$tmp = \DB::select('SELECT sum(jumlah) as s_jumlah, sum(bayar) as s_bayar from tagihan WHERE tapel = "' . $tapel -> nama2 . '"')[0];
					Cache::put('tagihan_semester', $tmp, 15);
					return $tmp;
				});
				
				$keuangan['total_pembayaran_bulan'] = Cache::get('pembayaran_bulan', function(){
					$bulan = date('Y-m');
					$tmp = \DB::select('select sum(jumlah) as b_jumlah from pembayaran where created_at like "' . $bulan . '%"')[0];
					Cache::put('pembayaran_bulan', $tmp, 15);
					return $tmp;
				});
			}
			
			$tapel_lalu = $tapel_aktif ? Tapel::where('nama2', '<', $tapel_aktif -> nama2) -> orderBy('nama2', 'desc') -> limit(1) -> first() : false;
			
			$counter['mahasiswa_aktif'] = Cache::get('mahasiswa_aktif', function() use($tapel_aktif, $tapel_lalu) {				
				$ma_ta = $tapel_aktif ? Mahasiswa::where('statusMhs', 1) ->count() : 0;
				$ma_tl = $tapel_lalu ? Aktivitas::where('tapel_id', $tapel_lalu -> id) -> where('status', 1) -> count() : 0;
				$r = [$ma_tl, $ma_ta];
				Cache::put('mahasiswa_aktif', $r, 15);
				return $r;
			});
			
			$counter['mahasiswa_non_aktif'] = Cache::get('mahasiswa_non_aktif', function() use($tapel_aktif, $tapel_lalu) {	
				$mna_ta = $tapel_aktif ? Mahasiswa::where('statusMhs', 9) ->count() : 0;
				$mna_tl = $tapel_lalu ? Aktivitas::where('tapel_id', $tapel_lalu -> id) -> where('status', 9) -> count() : 0;
				$r = [$mna_tl, $mna_ta];
				return $r;
				Cache::put('mahasiswa_non_aktif', $r, 15);
			});
			
			$counter['mahasiswa_baru_pa'] = Cache::get('mahasiswa_baru_pa', function() use($tapel_aktif, $tapel_lalu) {
				$mb_pa_ta = $tapel_aktif ? Mahasiswa::where('tapelMasuk', $tapel_aktif -> nama2) -> where('jenisKelamin', 'L') -> count() : 0;
				$mb_pa_tl = $tapel_lalu ? Mahasiswa::where('tapelMasuk', $tapel_lalu -> nama2) -> where('jenisKelamin', 'L') -> count() : 0;
				Cache::put('mahasiswa_baru_pa', [$mb_pa_tl, $mb_pa_ta], 15);
				return [$mb_pa_tl, $mb_pa_ta];
			});
			
			$counter['mahasiswa_baru_pi'] = Cache::get('mahasiswa_baru_pi', function() use($tapel_aktif, $tapel_lalu) {
				$mb_pi_ta = $tapel_aktif ? Mahasiswa::where('tapelMasuk', $tapel_aktif -> nama2) -> where('jenisKelamin', 'P') -> count() : 0;
				$mb_pi_tl = $tapel_lalu ? Mahasiswa::where('tapelMasuk', $tapel_lalu -> nama2) -> where('jenisKelamin', 'P') -> count() : 0;
				Cache::put('mahasiswa_baru_pi', [$mb_pi_tl, $mb_pi_ta], 15);
				return [$mb_pi_tl, $mb_pi_ta];
			});
			
			
			$counter['mahasiswa_lulus'] = Cache::get('mahasiswa_lulus', function() use($tapel_aktif, $tapel_lalu) {	
				$ml_ta = $tapel_aktif ? Aktivitas::where('tapel_id', $tapel_aktif -> id) -> where('status', 4) -> count() : 0;
				$ml_tl = $tapel_lalu ? Aktivitas::where('tapel_id', $tapel_lalu -> id) -> where('status', 4) -> count() : 0;
				Cache::put('mahasiswa_lulus', [$ml_tl, $ml_ta], 15);
				return [$ml_tl, $ml_ta];
			});
			
			$counter['mahasiswa_blm_lulus'] = Cache::get('mahasiswa_blm_lulus', function() use($tapel_aktif, $tapel_lalu) {	
				$mbl_tl = $tapel_lalu ? Aktivitas::where('tapel_id', $tapel_lalu -> id) -> where('status', '<>', 4)  -> where('semester', '>=', 8) -> count() : 0;
				$mbl_ta = $tapel_aktif ? Aktivitas::where('tapel_id', $tapel_aktif -> id) -> where('status', '<>', 4) -> where('semester', '>=', 8) -> count() : 0;
				Cache::put('mahasiswa_blm_lulus', [$mbl_tl, $mbl_ta], 15);
				return [$mbl_tl, $mbl_ta];
			});
			
			
			//Jumlah per JK per Angkatan
			$jk_angkatan = Cache::get('g_jk_angkatan', function() {
				$tmp = \DB::select("
				SELECT 
				SUM(IF(jenisKelamin = 'L', 1, 0)) AS jlk, 
				SUM(IF(jenisKelamin = 'P', 1, 0)) AS jpr, 
				angkatan 
				FROM mahasiswa 
				GROUP BY angkatan;
				");
				
				$tmp2 = [];
				if($tmp)
				{
					foreach($tmp as $t) 
					{
						$tmp2[$t -> angkatan]['L'] = $t -> jlk;
						$tmp2[$t -> angkatan]['P'] = $t -> jpr;
					}
				}
				Cache::put('g_jk_angkatan', $tmp2, 15);
				return $tmp2;
			});
			
			//Jumlah Mahasiswa per PRODI
			$mhs_prodi = Cache::get('g_mhs_prodi', function() {
				$tmp = \DB::select("
				select count(*) as jumlah, prodi.nama as prodi from mahasiswa left join prodi on prodi.id = mahasiswa.prodi_id group by prodi_id
				");
				$tmp2 = [];
				if($tmp)
				{
					foreach($tmp as $t) 
					{
						$tmp2[$t -> prodi] = $t -> jumlah;
					}
				}
				Cache::put('g_mhs_prodi', $tmp2, 15);
				return $tmp2;
			});
			
			//JK Dosen
			$jk_dosen = Cache::get('g_jk_dosen', function() {
				$tmp = \DB::select("
				select count(*) as jumlah, jenisKelamin from dosen group by jenisKelamin order by jenisKelamin
				");
				$tmp2 = [];
				if($tmp)
				{
					foreach($tmp as $t) 
					{
						$tmp2[$t -> jenisKelamin] = $t -> jumlah;
					}
				}
				Cache::put('g_jk_dosen', $tmp2, 15);
				return $tmp2;
			});
			
			//Status Dosen
			$st_dosen = Cache::get('g_st_dosen', function() {
				$tmp = \DB::select("
				SELECT 
				sum(if(statusDosen = 1 and NIDN = '', 1, 0)) as dttn, 
				sum(if(statusDosen = 1 and NIDN != '', 1, 0)) as dtn, 
				sum(if(statusDosen = 2, 1, 0)) as dtt 
				FROM dosen 
				ORDER BY statusDosen
				");
				
				$tmp2 = [];
				if($tmp)
				{
					$tmp2[] = $tmp[0] -> dttn;
					$tmp2[] = $tmp[0] -> dtn;
					$tmp2[] = $tmp[0] -> dtt;
				}
				Cache::put('g_st_dosen', $tmp2, 15);
				return $tmp2;
			});
			
			//Penugasan Dosen
			$png_dosen = Cache::get('g_png_dosen', function() {
				$tmp = \DB::select("
				select count(*) as jumlah, prodi.singkatan from dosen_penugasan left join prodi on prodi.id = dosen_penugasan.prodi_id group by prodi_id
				");
				
				$tmp2 = [];
				if($tmp)
				{
					foreach($tmp as $t) 
					{
						$tmp2[$t -> singkatan] = $t -> jumlah;
					}
				}
				
				Cache::put('g_png_dosen', $tmp2, 15);
				return $tmp2;
			});
			
			$kec = Cache::get('g_kec', function() {
				$tmp = \DB::select('
				select kec.nm_wil AS kec, kab.nm_wil AS kab, prov.nm_wil AS prov, count(NIM) AS jumlah
				from mahasiswa m
				left join wilayah as kec on kec.id_wil = m.id_wil
				inner join wilayah AS kab on kab.id_wil=kec.id_induk_wilayah
				inner join wilayah AS prov on prov.id_wil=kab.id_induk_wilayah
				group by kec.nm_wil;
				');
				
				$tmp2 = [];
				if($tmp)
				{
					foreach($tmp as $t) $tmp2[$t -> kec] = $t -> jumlah;
				}
				Cache::put('g_kec', $tmp2, 15);
				return $tmp2;
			});		
			
			$pk_ortu = Cache::get('g_pk_ortu', function() {
				$s = \DB::select('SELECT pekerjaanAyah, COUNT(NIM) AS jumlah FROM mahasiswa GROUP BY pekerjaanAyah');
				
				$tmp = [];
				if($s)
				{
					foreach($s as $st) $pk_ortu[$st -> pekerjaanAyah] = $st -> jumlah;
					foreach(config('custom.pilihan.pekerjaanOrtu') as $k => $v) 
					{
						$tmp[$k] = isset($pk_ortu[$k]) ? $pk_ortu[$k] : 0;
					}
					ksort($tmp);
				}
				Cache::put('g_pk_ortu', $tmp, 15);
				return $tmp;
			});
			
			$status = Cache::get('g_status', function() {
				$s = \DB::select('SELECT statusMhs, COUNT(NIM) AS jumlah FROM mahasiswa GROUP BY statusMhs');
				$tmp = [];
				if($s)
				{
					foreach($s as $st) $status[$st -> statusMhs] = $st -> jumlah;
					foreach(config('custom.pilihan.statusMhs') as $k => $v) 
					{
						$tmp[$k] = isset($status[$k]) ? $status[$k] : 0;
					}
					ksort($tmp);
				}
				Cache::put('g_status', $tmp, 15);
				return $tmp;
			});
			
			if(Cache::has('g_per_angkatan'))
			{
				$per_angkatan = Cache::get('g_per_angkatan');
				$lulusan = Cache::get('g_lulusan');
			}
			else
			{		
				$l = \DB::select('SELECT SUBSTR(tglKeluar, 7, 4) as thLulus, COUNT(NIM) AS jumlah FROM mahasiswa WHERE tglKeluar IS NOT NULL and tglKeluar <> "" GROUP BY thLulus');
				$p = \DB::select('SELECT SUBSTR(NIM, 1, 4) AS tahun, COUNT(NIM) AS jumlah FROM mahasiswa GROUP BY tahun');
				
				$per_angkatan = [];
				$lulusan = [];
				if($l) foreach($l as $lu) $lulusan[$lu -> thLulus] = $lu -> jumlah;
				
				if($p)
				{
					foreach($p as $pa) 
					{
						$per_angkatan[$pa -> tahun] = $pa -> jumlah;
						$lulusan[$pa -> tahun] = isset($lulusan[$pa -> tahun]) ? $lulusan[$pa -> tahun] : 0;
					}
				}
				
				if($l) ksort($lulusan);
				
				Cache::put('g_per_angkatan', $per_angkatan, 15);
				Cache::put('g_lulusan', $lulusan, 15);
			}
			
			// birthday
			$birthday = Cache::get('birthday', function(){
				$birthday=[];
				$minutes_to_midnight = ceil((strtotime(date('Y-m-d') . ' 23:59:59') - time()) / 60);
				$b_date = date('d-m');
				$b_dosen = \Siakad\Dosen::where('tglLahir', 'like', $b_date . '%') -> get();
				$b_mhs = \Siakad\Mahasiswa::whereIn('statusMhs', [1, 9, 10, 11, 12]) -> where('tglLahir', 'like', $b_date . '%') -> get();
				if($b_dosen) 
				{
					foreach($b_dosen as $d)
					{						
						if($d -> tgl_mulai_masuk != '')
						{
							$date1 = new \DateTime($d -> tgl_mulai_masuk);
							$date2 = new \DateTime();
							$interval = $date1 -> diff($date2);
							$wtm = $interval -> y . ' tahun ' . $interval -> m . ' bulan ' . $interval -> d . ' hari';
						}
						else
						{
							$wtm = '';
						}
						
						$age = $d -> tglLahir != '' ? \Carbon\Carbon::parse($d -> tglLahir)->diffInYears() : '';
						$birthday[] = [
						'typ' => 'dosen',
						'atyp' => 'Siakad\\Dosen',
						'wtm' => $wtm,
						'id' => $d -> id,
						'nam' => $d -> nama,
						'pct' => $d -> foto,
						'age' => $age
						];			
					}
				}
				if($b_mhs) 
				{
					foreach($b_mhs as $d)
					{
						if($d -> tglMasuk != '')
						{
							$date1 = new \DateTime($d -> tglMasuk);
							$date2 = new \DateTime();
							$interval = $date1 -> diff($date2);
							$wtm = $interval -> y . ' tahun ' . $interval -> m . ' bulan ' . $interval -> d . ' hari';
						}
						else
						{
							$wtm = '';
						}
						
						$age = $d -> tglLahir != '' ? \Carbon\Carbon::parse($d -> tglLahir)->diffInYears() : '';
						$birthday[] = [
						'typ' => 'mahasiswa',
						'atyp' => 'Siakad\\Mahasiswa',
						'wtm' => $wtm,
						'id' => $d -> id,
						'nam' => $d -> nama,
						'pct' => $d -> foto,
						'age' => $age
						];			
					}
				}
				
				if(count($birthday))
				{
					Cache::put('birthday', $birthday, $minutes_to_midnight);
					
					//send mail
					foreach($birthday as $b)
					{
						$user = \Siakad\User::where('authable_type', $b['atyp']) -> where('authable_id', $b['id']) -> first();
						$this -> sendMail(
						0, 
						$user -> id, 
						'Selamat Ulang Tahun dari Tim SIAKAD',
						'Halo '. $b['nam'] .',<br/><br/>
						Kami dari Tim SIAKAD mengucapkan Selamat Ulang Tahun untuk anda di hari yang spesial ini!<br/>
						<em>Baarokallohu fii \'umrik</em>.<br/><br/>
						Tim SIAKAD'
						);
					}
				}
				
				return $birthday;
			});
			
			return view('home', compact(
			'informasi', 'counter', 'per_angkatan', 'lulusan', 'status', 'kec', 'pk_ortu', 'jk_angkatan', 
			'mhs_prodi', 'jk_dosen', 'st_dosen', 'png_dosen', 'files', 'keuangan', 'kurikulum', 'akm',
			'hari', 'jadwal_terdekat', 'tagihan_semester', 'birthday'
			));
		}
	}
