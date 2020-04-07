<?php namespace Siakad\Http\Controllers;
	
	use Input;
	use Session;
	use Redirect;
	use Response;
	use Excel;
	
	use Siakad\Nilai;
	use Siakad\Krs;
	use Siakad\KrsDetail;
	use Siakad\MatkulTapel;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;	
	
	use Illuminate\Http\Request;
	
	class NilaiController extends Controller {
		
		use \Siakad\SkalaTrait;		
		use \Siakad\TagihanTrait;		
		
		public function deleteKelas($mahasiswa_id, $matkul_tapel_id)
		{
			Nilai::where('mahasiswa_id', $mahasiswa_id)
			-> where('matkul_tapel_id', $matkul_tapel_id)
			-> delete();
			
			//delete KRS detail
			KrsDetail::join('krs', 'krs.id', '=', 'krs_detail.krs_id') 
			-> where('krs.mahasiswa_id', $mahasiswa_id)
			-> where('krs_detail.matkul_tapel_id', $matkul_tapel_id)
			-> delete();
			//purge "empty" KRS record
			\DB::delete('DELETE FROM krs WHERE id NOT IN(SELECT DISTINCT krs_id FROM krs_detail)');
			
			return Redirect::back() -> with('message', 'Kelas Kuliah berhasil dihapus.');
		}
		
		public function import(Request $request)
		{
			$tmp = null;
			$data = json_decode($request -> get('data'), true);
			if(!isset($data['ver'])) return Redirect::back() -> with('warning', 'Format Nilai salah');
			
			$matkul_tapel_id = $request -> get('matkul_tapel_id');
			
			if(!isset($data['nil']) or count($data['nil']) < 1) return Redirect::back() -> with('warning', 'Format Nilai salah');
			
			$skala = $this -> skala($data['pro']);
			foreach($data['nil'] as $id => $n) 
			{
				$akhir = 0;
				if(is_array($n)){
					foreach($n as $j => $d)
					{
						if($j > 0) 
						{
							$d = intval($d);
							if($d >= 0) 
							{
								$akhir += $d * $data['bbt'][$j] / 100;
								$tmp[] = [
								'mahasiswa_id' => $id,
								'jenis_nilai_id' => $j,
								'nilai' => $d,
								'matkul_tapel_id' => $matkul_tapel_id
								];
							}
						}
					}
				}
				
				if($akhir >= 0) 
				{
					$huruf = $akhir == 0 ? '' : $this -> toHuruf($skala, $akhir);
					$tmp[] = [
					'mahasiswa_id' => $id,
					'jenis_nilai_id' => 0,
					'nilai' => $huruf,
					'matkul_tapel_id' => $matkul_tapel_id
					];
				}
				//update KRS
				$krs = \Siakad\Krs::firstOrCreate(['mahasiswa_id' => $id, 'tapel_id' => $data['tak']]);
				\DB::insert("INSERT IGNORE INTO `krs_detail` (krs_id, matkul_tapel_id) VALUES (" . $krs -> id . ", " . $matkul_tapel_id . ")");
			}
			
			/* if($jenis_nilai[0] === 'NILAI_V3')   										// V3
				{
				$count = count($jenis_nilai) - 1;
				foreach($data as $d) 
				{
				$t = explode(';', $d);
				if($t[0] === 'NILAI_V3') continue;
				for($i = 1; $i <= $count; $i++)
				{
				$tmp[] = [
				'mahasiswa_id' => $t[0],
				'jenis_nilai_id' => $jenis_nilai[$i],
				'nilai' => $t[$i],
				'matkul_tapel_id' => $matkul_tapel_id
				];
				}
				}
				}
				else																					// V2
				{
				foreach($data as $d) 
				{
				$t = explode(';', $d);
				$tmp[] = [
				'mahasiswa_id' => $t[0],
				'jenis_nilai_id' => $t[1],
				'nilai' => $t[2],
				'matkul_tapel_id' => $matkul_tapel_id
				];
				}
			} */
			
			if($tmp === null) return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> withErrors(['IMPORT_FAILED' => 'Impor nilai GAGAL.']);	
			
			Nilai::where('matkul_tapel_id', $matkul_tapel_id) -> delete();
			if(Nilai::insert($tmp))
			{
				// $this -> hitungNilaiAkhir($matkul_tapel_id);
				return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> with('message', 'Impor nilai berhasil.');	
			}
			
			return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> withErrors(['IMPORT_FAILED' => 'Impor nilai GAGAL.']);	
		} 
		
		public function index($matkul_tapel_id)
		{
			$data = MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			
			if(!in_array(\Auth::user() -> role_id, [1,2,8]) and $data -> dosen_id != \Auth::user() -> authable -> id) abort(401);
			
			$jenis = $jns = [];
			$jenis = [
			['__FINAL__', 100],
			['__AKHIR__', 100],
			];
			$tmp = \DB::select('select * from jenis_nilai where id IN (select distinct jenis_nilai_id from nilai where matkul_tapel_id = '. $matkul_tapel_id .')');
			if($tmp) foreach($tmp as $t) $jenis[$t -> id] = [$t -> nama, $t -> bobot]; 
			
			if(count($jenis) <= 2)
			{
				$tmp = \Siakad\JenisNilai::where('aktif', 'y') -> where('prodi_id', $data -> prodi_id) -> get(['id', 'nama', 'bobot']);
				foreach($tmp as $t) $jenis[$t -> id] = [$t -> nama, $t -> bobot]; 
			}
			
			//jenis nilai
			foreach($jenis as $k => $v)
			{
				if($k > 0) 
				{
					$jns[] = $k . ';' . $v[1];
					$ejn[$k] =  $v[1];
				}
			}
			$jns = implode('|', $jns);
			
			$skala = $this -> skala($data -> prodi_id);
			
			$mhs = \DB::select('
			SELECT 
			`jenis_nilai`.`nama` AS `nama_nilai`, `jenis_nilai`.`bobot` AS `bobot_nilai`, 
			`matkul_tapel_id`, `nilai`, `jenis_nilai_id`, 
			`mahasiswa`.`id`, `mahasiswa`.`nama`, `mahasiswa`.`NIM` , `mahasiswa`.`angkatan`, `mahasiswa`.`telp`, `mahasiswa`.`hp`,
			`mahasiswa`.`prodi_id`, kelasMhs, jenisPembayaran
			FROM `matkul_tapel` `mt`
			INNER JOIN `nilai` ON `nilai`.`matkul_tapel_id` = `mt`.`id` 
			INNER JOIN `mahasiswa` ON `nilai`.`mahasiswa_id` = `mahasiswa`.`id`
			INNER JOIN `jenis_nilai` ON `nilai`.`jenis_nilai_id` = `jenis_nilai`.`id`
			WHERE `mt`.`id` = :id
			ORDER BY `mahasiswa`.`NIM` ASC, `jenis_nilai_id` DESC
			', 
			['id' => $matkul_tapel_id]
			);
			
			//ekspor nilai (04032017)
			//V3B 13102018
			// $ejn = [];
			$edata = [];
			$emhs = [];
			
			if(count($mhs) > 0)
			{
				foreach($mhs as $s)
				{
					//generate tagihan, and cache the result for 1 hour
					
					if(!\Cache::has('tagihan_for_' . $s -> id . '_generated'))
					{
						$this -> generateTagihan($s -> id);
						\Cache::put('tagihan_for_' . $s -> id . '_generated', true, 60);
					}
					$locked = ['uts' => true, 'uas' => true];
					$tagihan = $this -> getTagihan($s, 1, $data -> ta2); // get tagihan SPP semester ini
					
					if($tagihan -> count())
					{
						$tagihan = $tagihan[0];
						$persen_pembayaran = $tagihan -> jumlah > 0 ? $tagihan -> bayar / $tagihan -> jumlah * 100 : 0;
						if($persen_pembayaran >= $tagihan -> persen_syarat_uts) $locked['uts'] = false;
						if($tagihan -> privilege_uts == 'y') $locked['uts'] = false;
						
						if($persen_pembayaran >= $tagihan -> persen_syarat_uas) $locked['uas'] = false;
						if($tagihan -> privilege_uas == 'y') $locked['uas'] = false;
					}
					else //BEASISWA NO SPP
					{
						$locked = ['uts' => false, 'uas' => false];						
					} 
					
					
					//ekspor nilai 
					// if(!in_array($s -> jenis_nilai_id, $ejn)) $ejn[] = $s -> jenis_nilai_id;
					/* if($s -> jenis_nilai_id > 0)  */
					$emhs[$s -> id][$s -> jenis_nilai_id] = $s -> nilai;
					
					$telp = intval($s -> telp) == 0 ? $s -> hp : $s -> telp;
					$peserta[$s -> id] = [
					'nim' => $s -> NIM,
					'angkatan' => $s -> angkatan,
					'nama' => $s -> nama,
					'telp' => $telp,
					'lock' => $locked
					];
					
					$nilai[$s -> id][$s -> jenis_nilai_id] = $s -> nilai;
					$nilai[$s -> id][$s -> jenis_nilai_id . 'h'] = $this -> toHuruf($skala, $s -> nilai);
				}
				
				//ekspor nilai
				$edata = [
				'ver' => 'V3B',
				'pro' => $data -> prodi_id,
				'tak' => $data -> tapel_id,
				'bbt' => $ejn,
				'nil' => $emhs
				];
				
				$edata = json_encode($edata, JSON_NUMERIC_CHECK);				
			}
			else
			{
				$peserta = [];	
				$edata = '';
			}
			
			return view('nilai.index', compact('data', 'matkul_tapel_id', 'peserta', 'nilai', 'jenis', 'skala', 'edata', 'jns'));
		}
		
		public function store(Request $request)
		{						
			$input = $request -> except('_token');
			
			if(!isset($input['nilai']) || (isset($input['nilai']) && count($input['nilai']) < 1)) return Redirect::back() -> with('warning', 'Nilai kosong.');
			
			if(!isset($input['jns']) or $input['jns'] == '') return Redirect::route('matkul.tapel.nilai', $input['mt_id']) -> with('warning', 'Nilai gagal disimpan.');
			$tmp = explode('|', $input['jns']);
			foreach($tmp as $t) 
			{
				$x = explode(';', $t);
				$jenis[$x[0]] = $x[1]; 
			}
			
			$skala = $this -> skala($input['prodi_id']);
			
			$data = [];
			foreach($input['nilai'] as $mhs => $nilai)
			{
				$akhir = 0;
				foreach($jenis as $k => $v)
				{
					if($k > 1)
					{
						// $n = intval($nilai[$k]);
						$n = ($nilai[$k] == '' or !isset($nilai[$k])) ? 0 : intval($nilai[$k]);
						// if($n > 0) 
						// {
						$akhir += $n * $v / 100;
						$data[] = 
						[
						'mahasiswa_id' => $mhs,
						'matkul_tapel_id' => $input['mt_id'],
						'jenis_nilai_id' => $k,
						// 'nilai' => $n
						'nilai' => $nilai[$k]
						];
						// }
					}
				}
				
				if($akhir <= 0 and intval($nilai['__AKHIR__']) > 0) $akhir = intval($nilai['__AKHIR__']);
				
				if($akhir >= 0)
				{
					$huruf = $this -> toHuruf($skala, $akhir);
					$data[] = 
					[
					'mahasiswa_id' => $mhs,
					'matkul_tapel_id' => $input['mt_id'],
					'jenis_nilai_id' => 0,
					'nilai' => $huruf
					];
					$data[] = 
					[
					'mahasiswa_id' => $mhs,
					'matkul_tapel_id' => $input['mt_id'],
					'jenis_nilai_id' => 1,
					'nilai' => $akhir
					];
				}
			}
			
			if(count($data) > 0)
			{
				Nilai::where('matkul_tapel_id', $input['mt_id']) -> delete();
				
				Nilai::insert($data);
				
				return Redirect::route('matkul.tapel.nilai', $input['mt_id']) -> with('message', 'Nilai berhasil disimpan.');	
			}
			return Redirect::route('matkul.tapel.nilai', $input['mt_id']) -> with('warning', 'Nilai gagal disimpan.');	
		}
		
		public function export($matkul_tapel_id)
		{
			$this -> data = MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			$title = 'Nilai - ' . $this -> data -> matkul . ' - ' . $this -> data -> singkatan . ' - ' . $this -> data -> ta;
			
			$this -> nilai = \DB::select('
			SELECT 
			`nilai`, `mahasiswa`.`nama`, `mahasiswa`.`NIM` 
			FROM `matkul_tapel` `mt`
			INNER JOIN `nilai` ON `nilai`.`matkul_tapel_id` = `mt`.`id` 
			INNER JOIN `mahasiswa` ON `nilai`.`mahasiswa_id` = `mahasiswa`.`id`
			WHERE `mt`.`id` = :id AND `nilai`.`jenis_nilai_id` = 0
			ORDER BY `mahasiswa`.`NIM`
			', 
			['id' => $matkul_tapel_id]
			);
			if(count($this -> nilai) < 1) return back() -> withErrors(['EMPTY' => 'Nilai tidak ditemukan']);
			
			Excel::create(str_slug($title), function($excel) use ($title){
				$excel
				->setTitle($title)
				->setCreator('schz')
				->setLastModifiedBy('Schz')
				->setManager('Schz')
				->setCompany('Schz. Co')
				->setKeywords('Al-Hikam, STAIMA, SIAKAD, STAIMA Al-Hikam Malang, '. $title)
				->setDescription($title);
				$excel->sheet('title', function($sheet){
					$nilai = $this -> nilai;
					$data = $this -> data;
					$sheet
					-> setFontSize(12)
					-> loadView('nilai.export') -> with(compact('nilai', 'data'));
				});
				
			})->download('xlsx');
		}
		
		public function formNilai($matkul_tapel_id)
		{
			$data = MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			$jenis = \Siakad\JenisNilai::where('prodi_id', $data -> prodi_id) -> where('aktif', 'y') -> pluck('nama', 'id');
			
			return view('nilai.form1', compact('matkul_tapel_id', 'jenis'));
		}
		public function cetakFormNilai(Request $request, $matkul_tapel_id)
		{
			$komponen = $request -> get('komponen');
			if(!isset($komponen) or count($komponen) < 1) $komponen[1] = 'Akhir';
			$data = MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			
			if(!In_array(\Auth::user() -> role_id, [1, 2, 8]) and $data -> dosen_id != \Auth::user() -> authable -> id) abort(401);
			$skala = $this -> skala($data -> prodi_id);
			
			$mhs = \DB::select('
			SELECT 
			`jenis_nilai`.`nama` AS `nama_nilai`, 
			`jenis_nilai`.`bobot` AS `bobot_nilai`, 
			`matkul_tapel_id`, `nilai`, `jenis_nilai_id`, `mahasiswa`.`id`, `mahasiswa`.`nama`, `mahasiswa`.`NIM` 
			FROM `matkul_tapel` `mt`
			INNER JOIN `nilai` ON `nilai`.`matkul_tapel_id` = `mt`.`id` 
			INNER JOIN `mahasiswa` ON `nilai`.`mahasiswa_id` = `mahasiswa`.`id`
			INNER JOIN `jenis_nilai` ON `nilai`.`jenis_nilai_id` = `jenis_nilai`.`id`
			WHERE `mt`.`id` = :id
			ORDER BY `mahasiswa`.`NIM` ASC
			', 
			['id' => $matkul_tapel_id]
			);
			
			if(count($mhs) > 0)
			{
				foreach($mhs as $s)
				{
					// if($s -> jenis_nilai_id != 1) { $n_list[$s -> jenis_nilai_id] = $s -> jenis_nilai_id == 0 ? 'Akhir' : $s -> nama_nilai; }
					$jenis_nilai[$s -> id][$s -> jenis_nilai_id] = [
					'nama_nilai' => $s -> nama_nilai,
					'mt_id' => $s -> matkul_tapel_id,
					'nilai' => $s -> nilai,
					'huruf' => $this -> toHuruf($skala, $s -> nilai),
					'jenis_nilai' => $s -> jenis_nilai_id,
					'id_mhs' => $s -> id,
					'nim' => $s -> NIM,
					'nama' => $s -> nama
					];
				}
			}
			else
			{
				$jenis_nilai = [];	
				return back() -> withErrors(['no_data' => 'Belum ada Mahasiswa yang terdaftar']);
			}
			
			return view('nilai.formnilai', compact('data', 'matkul_tapel_id', 'jenis_nilai', 'komponen'));
		}
		
		public function destroy(Request $request)
		{
			Nilai::where('jenis_nilai_id', $request -> get('jenis_nilai_id'))
			-> where('matkul_tapel_id', $request -> get('matkul_tapel_id'))
			-> delete();
			
			Nilai::where('jenis_nilai_id', '0')
			-> where('matkul_tapel_id', $request -> get('matkul_tapel_id'))
			-> update(['nilai' => null]);
			
			return Response::json(['success' => true]);
		}
		
		private function checkBobotNilai($matkul_tapel_id)
		{
			$ag = \DB::select('
			SELECT 
			SUM(`bobot`) AS aggregate 
			FROM `jenis_nilai` 
			WHERE `id` IN(
			SELECT DISTINCT `nilai`.`jenis_nilai_id` 
			FROM `nilai` 
			WHERE `nilai`.`matkul_tapel_id` = :id AND `nilai`.`jenis_nilai_id` <> 0
			);', 
			['id' => $matkul_tapel_id]);
			if(!isset($ag[0] -> aggregate) or $ag[0] -> aggregate == null) return 0; else return intval($ag[0] -> aggregate);
		} 
		
		public function hitungNilaiAkhir($matkul_tapel_id)
		{
			// Add checking routine: 22 Feb 2017
			$check = Nilai::where('jenis_nilai_id', '<>',  '0') -> where('matkul_tapel_id', $matkul_tapel_id) -> exists();
			if(!$check) return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> with('warning', 'Nilai belum dimasukkan.');	
			
			$check = $this -> checkBobotNilai($matkul_tapel_id);
			if($check < 100) return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> with('warning_raw', 'Nilai belum lengkap. Pilih / input Komponen Penilaian');	
			
			Nilai::where('jenis_nilai_id', '0')
			-> where('matkul_tapel_id', $matkul_tapel_id)
			-> delete();
			
			$query = "
			SELECT n.matkul_tapel_id, n.mahasiswa_id, numberToLetter(SUM(n.nilai * j.bobot /100)) AS `__FINAL__`, 0, now(), now()
			FROM nilai n 
			LEFT JOIN jenis_nilai j ON j.id = n.jenis_nilai_id
			WHERE matkul_tapel_id = :matkul_tapel_id 
			GROUP BY mahasiswa_id 
			ORDER BY mahasiswa_id
			";
			
			\DB::insert('
			INSERT INTO `nilai` (matkul_tapel_id, mahasiswa_id, nilai, jenis_nilai_id, updated_at, created_at) ' . $query . '
			', 
			['matkul_tapel_id' => $matkul_tapel_id]
			);
			
			//add flag: 22 Feb 2017
			MatkulTapel::where('id', $matkul_tapel_id) -> update(['sync' => 'y']);
			
			return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> with('message', 'Penghitungan nilai akhir berhasil.');	
		} 
	}																																																																																																																																																																																																																																																																				