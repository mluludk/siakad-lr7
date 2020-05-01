<?php	
	function strPad($str, $n=16)
	{
		$str = intval($str);
		if(strlen($str) < $n) return "".str_pad($str, $n, '0', STR_PAD_LEFT);
		elseif(strlen($str) == $n) return "".$str;
		else return "".substr($str, 0, $n);
	}
	
	function cekTanggungan($mahasiswa_id, $jenis, $batas_semester=null, $jenis_biaya_id=null, $golongan=null)
	{
		$valid = true;
		$mahasiswa = \Siakad\Mahasiswa::find($mahasiswa_id);
		
		if($jenis == 'nil')
		{
			$nilai = \Siakad\Nilai::transkrip($mahasiswa -> id, true) -> get();
			foreach($nilai as $n)
			{
				if($n -> semester >= 1 && $n -> semester <= $batas_semester)
				{
					$nl = strtoupper($n -> nilai);
					if($nl == 'C-' || $nl == 'D' || $nl == '' || $nl == '-') $valid = false;
				}
			}
		}
		
		elseif($jenis == 'sks')
		{
			$sks_wajib = \Cache::get(
			'sks_wajib_' . $mahasiswa -> prodi_id . '_' . $mahasiswa -> angkatan . '_' . $batas_semester,
			function() use ($mahasiswa, $batas_semester){
				$sks = \Siakad\KurikulumMatkul::SKSWajib($mahasiswa -> prodi_id, $mahasiswa -> angkatan, $batas_semester) -> first();
				\Cache::put('sks_wajib_' . $mahasiswa -> prodi_id . '_' . $mahasiswa -> angkatan . '_' . $batas_semester, $sks -> jumlah, 30);
				return $sks -> jumlah;
			});
			$sks = \Siakad\KurikulumMatkul::jumlahSKS($mahasiswa -> id) -> first();
			if($sks -> jumlah < $sks_wajib) $valid = false;
		}
		
		elseif($jenis == 'krs')
		{
			$krs = \DB::select('
			SELECT approved
			FROM krs
			WHERE mahasiswa_id = :mahasiswa_id
			AND tapel_id = (
			SELECT tapel_id
			FROM aktivitas_perkuliahan
			WHERE mahasiswa_id = :mahasiswa_id2 AND semester = :batas_semester
			ORDER BY tapel_id DESC
			LIMIT 1)',
			[
			'mahasiswa_id' => $mahasiswa -> id,
			'mahasiswa_id2' => $mahasiswa -> id,
			'batas_semester' => $batas_semester
			]);
			
			//check for double KRS <<< ???
			// if(!$krs or $krs[0] -> approved != 'y') $valid = false;
			if(!$krs) $valid = false;
		}
		
		elseif($jenis == 'keu')
		{
			if($jenis_biaya_id == null) $valid = false;
			else
			{
				$tagihan = \Siakad\Tagihan::join('setup_biaya', 'setup_biaya.id', '=', 'setup_biaya_id')
				-> where('mahasiswa_id', $mahasiswa -> id)
				-> where('angkatan', $mahasiswa -> angkatan)
				-> where('prodi_id', $mahasiswa -> prodi_id)
				-> where('kelas_id', $mahasiswa -> kelasMhs)
				-> where('jenisPembayaran', $mahasiswa -> jenisPembayaran)
				-> get(['jenis_biaya_id', 'tagihan.id', 'tagihan.jumlah', 'tagihan.bayar', 'tagihan.privilege']);
				if(!$tagihan)
				{
					$valid = false;
				}
				else
				{
					$tanggungan = 0;
					$bayar = 0;
					foreach($tagihan as $t)
					{
						if($t -> jenis_biaya_id == $jenis_biaya_id)
						{
							$tanggungan +=  $t -> jumlah;
							$bayar +=  $t -> bayar;
						}
					}
					if($tanggungan - $bayar > 0) $valid = false;
				}
			}
		}
		
		//filter golongan
		elseif($jenis == 'gol')
		{
			if($golongan == null) $valid = false;
			else
			{
				$tagihan = \Siakad\Tagihan::join('setup_biaya', 'setup_biaya.id', '=', 'setup_biaya_id')
				-> join('jenis_biaya', 'jenis_biaya.id', '=', 'setup_biaya.jenis_biaya_id')
				-> where('mahasiswa_id', $mahasiswa -> id)
				-> where('jenis_biaya.golongan', $golongan)
				-> get(['tagihan.id', 'tagihan.jumlah', 'tagihan.bayar', 'tagihan.privilege']);
				
				if($tagihan)
				{
					$tanggungan = 0;
					$bayar = 0;
					foreach($tagihan as $t)
					{
						if($t -> jumlah - $t -> bayar > 0)
						{
							$valid = false;
							break;
						}
					}
				}
			}
		}
		return $valid;
	}
	
	function rating($rate)
	{
		$s = 1;
		$rating = '';
		switch($rate)
		{
			case 1:
			$cls = 'success';
			break;
			
			case 2:
			case 3:
			case 4:
			$cls = 'warning';
			break;
			
			case 5:
			$cls = 'danger';
			break;
			
			default:
			$cls = 'default';
		}
		
		while($s <= 5)
		{
			if($s <= $rate) $rating .= '<i class="fa fa-star text-'. $cls .' text-xs"></i>';
			else
			$rating .= '<i class="fa fa-star-o text-xs"></i>';
			
			$s++;
		}
		
		return $rating;
	}
	
	// https://www.php.net/manual/en/function.shuffle.php#94697
	function shuffle_assoc(&$array) {
		$keys = array_keys($array);
		
		shuffle($keys);
		
		foreach($keys as $key) {
			$new[$key] = $array[$key];
		}
		
		$array = $new;
		
		return true;
	}
	function isPendamping($lokasi_id, $authable_id, $data)
	{
		if(count($data) > 0)
		{
			if(isset($data[$lokasi_id]))
			{
				foreach($data[$lokasi_id] as $id => $nama)
				{
					if($id == $authable_id) return true;
				}
			}
		}
		
		return false;
	}
	function formatPendamping($key, $data)
	{
		$r = '';
		if(count($data) > 0)
		{
			if(isset($data[$key]))
			{
				$r = '<ol class="tim_dosen">';
				foreach($data[$key] as $d)
				{
					$r .= '<li>' . $d .'</li>';
				}
				$r .= '</ol>';
			}
		}
		
		return $r;
	}
	
	function formatLokasiPkm($data, $user)
	{
		$n = 0;
		$lokasi = [];
		foreach($data as $d)
		{
			$r = '';
			// Hanya menampilkan Lokasi untuk dosen ybs pada login dosen
			if($user -> role_id == 128)
			{
				if($d -> pendamping -> count())
				{
					foreach($d -> pendamping as $p)
					{
						if($p -> dosen_id == $user -> authable_id) 
						{
							$found = true;
							break;
						}
						$found = false;
					}
				}
				if(!$found) continue;
			}
			
			
			$r .= $d -> nama . ' (' . $d -> peserta -> count() . '/' . $d -> kuota . ') ';
			if(in_array($user -> role_id, [1,2,8,257]))
			{
				$r .= '
				<a href="'. route('mahasiswa.pkm.lokasi.pendamping.create', [$d -> pkm_id, $d -> id]) .'" title="Tambah Pendamping" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i></a>
				<a href="'. route('mahasiswa.pkm.lokasi.matkul.create', [$d -> pkm_id, $d -> id]) .'" title="Tambah Mata Kuliah" class="btn btn-info btn-xs btn-flat"><i class="fa fa-plus"></i></a>
				<a href="'. route('mahasiswa.pkm.lokasi.edit', [$d -> pkm_id, $d -> id]) .'" title="Edit lokasi" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>
				<a href="'. route('mahasiswa.pkm.lokasi.delete', $d -> id) .'" title="Hapus lokasi" class="btn btn-danger btn-xs btn-flat has-confirmation"><i class="fa fa-trash"></i></a>
				';
			}
			
			if($d -> pendamping -> count())
			{
				$r .= '<ol class="pendamping">';
				foreach($d -> pendamping as $p)
				{
					$r .= '<li>' . $p -> dosen -> gelar_depan . ' ' . $p -> dosen -> nama . ' ' . $p -> dosen -> gelar_belakang;
					if(in_array($user -> role_id, [1,2,8,257]))
					{
						$r .= '
						<a href="'. route('mahasiswa.pkm.lokasi.pendamping.delete', $p -> id) .'" title="Hapus Pendamping" class="btn btn-danger btn-xs btn-flat has-confirmation"><i class="fa fa-trash"></i></a>
						';
					}
					$r .= '</li>';
				}
				$r .= '</ol>';
			}
			$lokasi[$n]['lokasi'] = $r;
			
			//Matkul
			foreach($d -> matkul as $lm)
			{
				$lokasi[$n]['matkul'][] = [
				'id' => $lm -> id, 
				'prodi' => $lm -> prodi -> strata . ' ' . $lm -> prodi -> singkatan, 
				'nama' => $lm -> mk -> nama . ' (' . $lm -> mk -> kode .')'
				];
			}
			
			$n++;
		}
		
		return $lokasi;
	}
	
	function formatLokasi($data, $user, $mode='pkm')
	{
		$r = '<ol class="lokasi">';
		foreach($data as $d)
		{
			
			// Hanya menampilkan Lokasi untuk dosen ybs pada login dosen
			if($user -> role_id == 128)
			{
				if($d -> pendamping -> count())
				{
					foreach($d -> pendamping as $p)
					{
						if($p -> dosen_id == $user -> authable_id) 
						{
							$found = true;
							break;
						}
						$found = false;
					}
				}
				if(!$found) continue;
			}
			
			
			$r .= '<li>' . $d -> nama . ' (' . $d -> peserta -> count() . '/' . $d -> kuota . ') ';
			if(in_array($user -> role_id, [1,2,8,257]))
			{
				if($mode == 'pkm')
				{
					$r .= '
					<a href="'. route('mahasiswa.pkm.lokasi.pendamping.create', [$d -> pkm_id, $d -> id]) .'" title="Tambah Pendamping" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-user"></i></a>
					<a href="'. route('mahasiswa.pkm.lokasi.edit', [$d -> pkm_id, $d -> id]) .'" title="Edit lokasi" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>
					<a href="'. route('mahasiswa.pkm.lokasi.delete', $d -> id) .'" title="Hapus lokasi" class="btn btn-danger btn-xs btn-flat has-confirmation"><i class="fa fa-trash"></i></a>
					';
				}
				else
				{
					$r .= '
					<a href="'. route('mahasiswa.ppl.lokasi.pendamping.create', [$d -> ppl_id, $d -> id]) .'" title="Tambah Pendamping" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-user"></i></a>
					<a href="'. route('mahasiswa.ppl.lokasi.edit', [$d -> ppl_id, $d -> id]) .'" title="Edit lokasi" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>
					<a href="'. route('mahasiswa.ppl.lokasi.delete', $d -> id) .'" title="Hapus lokasi" class="btn btn-danger btn-xs btn-flat has-confirmation"><i class="fa fa-trash"></i></a>
					';
				}
			}
			
			if($d -> pendamping -> count())
			{
				$r .= '<ol class="pendamping">';
				foreach($d -> pendamping as $p)
				{
					$r .= '<li>' . $p -> dosen -> gelar_depan . ' ' . $p -> dosen -> nama . ' ' . $p -> dosen -> gelar_belakang;
					if(in_array($user -> role_id, [1,2,8,257]))
					{
						if($mode == 'pkm')
						{
							$r .= '
							<a href="'. route('mahasiswa.pkm.lokasi.pendamping.delete', $p -> id) .'" title="Hapus Pendamping" class="btn btn-danger btn-xs btn-flat has-confirmation"><i class="fa fa-trash"></i></a>
							';
						}
						else
						{
							$r .= '
							<a href="'. route('mahasiswa.ppl.lokasi.pendamping.delete', $p -> id) .'" title="Hapus Pendamping" class="btn btn-danger btn-xs btn-flat has-confirmation"><i class="fa fa-trash"></i></a>
							';
						}
					}
					$r .= '</li>';
				}
				$r .= '</ol>';
			}
			
			$r .= '</li>';
		}
		$r .= '</ol>';
		
		return $r;
	}
	
	function getIPK($akm)
	{
		$ipk = 0;
		$smt = 0;
		if(!$akm -> count()) return $ipk;
		foreach($akm as $a)
		{
			if($a -> semester > $smt)
			{
				$ipk = $a -> ipk;
				$smt = $a -> semester;
			}
		}
		
		return $ipk;
	}
	
	function hitungSemester($tapel_masuk, $tapel_sekarang)
	{
		if($tapel_sekarang < $tapel_masuk) return 'Invalid';
		$tapel = \Cache::get('tapel_list', function(){
			$t = \Siakad\Tapel::orderBy('nama2') -> pluck('id', 'nama2');
			\Cache::put('tapel_list', $t, 60);
			return $t;
		});
		$smt = 1;
		foreach($tapel as $n => $id)
		{
			if($n >= $tapel_masuk and $n < $tapel_sekarang)
			{
				$smt++;
			}
		}
		
		return $smt;
	}
	function cekHer($tapel, $tagihan)
	{
		foreach($tagihan as $t)
		{
			if(isset($t -> setup -> jenis_biaya_id) and $t -> setup -> jenis_biaya_id == 2 and $t -> tapel == $tapel)
			{
				if($t -> jumlah - $t -> bayar <= 0) return '<i class="fa fa-check text-success"></i>';
			}
		}
		return '<i class="fa fa-times text-danger"></i>';
	}
	
	function formatRupiah($angka)
	{
		return 'Rp ' . number_format($angka, 0, ',', '.');
	}
	function formatJadwal($data)
	{
		$hari = config('custom.hari');
		
		if($data -> count() > 1)
		{
			$r = '<ol class="tim_dosen">';
			foreach($data as $d)
			{
				if(isset($d -> hari))
				{
					$r .= '<li>';
					$r .= '<strong>' . $hari[$d -> hari] . '</strong>, ' . $d -> jam_mulai . ' - ' . $d -> jam_selesai . ' (' . $d -> ruang -> nama . ')';
					$r .= '</li>';
				}
			}
			$r .= '</ol>';
		}
		else
		{
			if(isset($data[0]))
			$r = '<strong>' . $hari[$data[0] -> hari] . '</strong>, ' . $data[0] -> jam_mulai . ' - ' . $data[0] -> jam_selesai . ' (' . $data[0] -> ruang -> nama . ')';
			else
			$r = 'Jadwal Menyusul';
		}
		
		return $r;
	}
	function formatTimDosen($data)
	{
		if($data -> count() > 1)
		{
			$r = '<ol class="tim_dosen">';
			foreach($data as $d)
			{
				$r .= '<li>' . $d -> gelar_depan . ' ' . $d -> nama . ' ' . $d -> gelar_belakang .'</li>';
			}
			$r .= '</ol>';
		}
		else
		{
			if(isset($data[0]))
			$r = $data[0] -> gelar_depan . ' ' . $data[0] -> nama . ' ' . $data[0]-> gelar_belakang;
			else
			$r = 'Tim Dosen';
		}
		
		return $r;
	}
	
	function getRoleName($json, $roles)
	{
		$json = json_decode($json);
		if(!count($json)) return '';
		foreach($json as $r)
		{
			$tmp[] = $roles[$r];
		}
		return implode(', ', $tmp);
	}
	function checkKrs($aktif, $krs)
	{
		if(count($krs))
		{
			foreach($krs as $k)
			{
				if($k -> tapel_id == $aktif && $k -> approved == 'y') return '<i class="fa fa-check text-success"></i>';
			}
		}
		return '<i class="fa fa-times text-danger"></i>';
	}
	function getPMBKey($no)
	{
		return sha1($no . date('YmdH') . csrf_token());
	}
	
	function convertKuotaProdi($prodi, $kuota)
	{
		foreach(json_decode($kuota) as $k => $v) $tmp[] = $prodi[$k] . ': ' . intval($v);
		return implode(', ', $tmp);
	}
	function isOnMaintenis()
	{
		if(file_exists(storage_path() . '/framework/maintenis'))
		{
			$message = parse_ini_file(storage_path() . '/framework/maintenis');
			if(isset($message))
			{
				$msg = '';
				$div_o = '<div style="width: 100%; height: 30px; padding: 5px 25px; background-color: #fe370e; color: white; z-index: 9999; text-align: center;"><strong>PERHATIAN:</strong> ';
				if($message['mode'] == 1)
				{
					$msg .= 'Aplikasi ' . config('custom.app.abbr') .' '. config('custom.app.version') .' sedang dalam mode <strong>TERBATAS</strong>, ';
					$msg .= ' hanya pengguna tertentu yang bisa masuk.';
					// if(count($message['allowed']) > 0) $msg .= ' hanya pengguna dengan wewenang ['. implode(', ', $message['allowed']) .'] yang bisa masuk.';
					$msg .= 'Beberapa fungsi mungkin tidak dapat digunakan.';
				}
				elseif($message['mode'] == 2)
				{
					$msg .= 'Aplikasi ' . config('custom.app.abbr') .' '. config('custom.app.version') .' sedang dalam perbaikan. Beberapa fungsi mungkin tidak dapat digunakan.';
				}
				$div_c = '</div>';
				
				if($message['message'] != '') $msg = $message['message'];
				return $div_o . $msg . $div_c;
			}
		}
	}
	function maintenisMessage()
	{
		$str = 'Maaf, Aplikasi ' . config('custom.app.abbr') .' '. config('custom.app.version') .' sedang dalam perbaikan.';
		if(file_exists(storage_path() . '/framework/maintenis'))
		{
			$message = parse_ini_file(storage_path() . '/framework/maintenis');
			if(isset($message))
			{
				if($message['message'] != '') $str = $message['message'];
			}
		}
		echo $str;
	}
	
	function terbilang($numb){
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		if($numb < 12)
		return " " . $huruf[$numb];
		elseif ($numb < 20)
		return terbilang($numb - 10) . " belas";
		elseif ($numb < 100)
		return terbilang($numb / 10) . " puluh" . terbilang($numb % 10);
		elseif ($numb < 200)
		return " seratus" . terbilang($numb - 100);
		elseif ($numb < 1000)
		return terbilang($numb / 100) . " ratus" . terbilang($numb % 100);
		elseif ($numb < 2000)
		return " seribu" . terbilang($numb - 1000);
		elseif ($numb < 1000000)
		return terbilang($numb / 1000) . " ribu" . terbilang($numb % 1000);
		elseif ($numb < 1000000000)
		return terbilang($numb / 1000000) . " juta" . terbilang($numb % 1000000);
		elseif ($numb >= 1000000000)
		return false;
	}
	
	/* http://mattsenior.com/2013/08/arabic-to-roman-numerals-conversion-with-PHP-and-regex */
	function arabicToRoman($n)
	{
		$n = str_repeat('I', $n);
		
		foreach (array('/I{5}/ V /V{2}/ X', '/I{4}/ IV /VIV/ IX') as $p) {
			foreach (array('IVX', 'XLC', 'CDM') as $r) {
				$a = explode(' ', strtr($p, 'IVX', $r));
				$n = preg_replace(array($a[0], $a[2]), array($a[1], $a[3]), $n);
			}
		}
		
		return $n;
	}
	
	function cutStr($str, $l=5)
	{
		if(strlen($str) <= $l) return $str;
		return substr($str, 0, $l) . ' ...';
	}
	
	function predikat($skala, $angka){
		if($angka > 4 or $angka < 0) return '';
		foreach($skala as $k => $v)
		if($angka <= floatval($v['max']) and $angka >= floatval($v['min'])) return $v['predikat'];
	}
	
	function numberToLetter($number, $base = 'base_4'){
		return 'Error - Moved to Skala';
		$number = intval($number);
		if($base === 'base_100'){
			switch($number){
				case ($number <= 100 and $number > 90):
				$ret = 'A+';break;
				
				case ($number <= 90 and $number > 85):
				$ret = 'A';break;
				
				case ($number <= 85 and $number > 80):
				$ret = 'A-';break;
				
				case ($number <= 80 and $number > 75):
				$ret = 'B+';break;
				
				case ($number <= 75 and $number > 70):
				$ret = 'B';break;
				
				case ($number <= 70 and $number > 65):
				$ret = 'B-';break;
				
				case ($number <= 65 and $number > 60):
				$ret = 'C+';break;
				
				case ($number <= 60 and $number > 55):
				$ret = 'C';break;
				
				case ($number <= 55 and $number > 50):
				$ret = 'C-';break;
				
				case ($number <= 50 and $number > 0):
				$ret = 'D';break;
				
				default:
				$ret = '-';
			}
			}else{
			if($number <= 4 && $number > 3.75){
				$ret = 'A+';
				}elseif($number <= 3.75 && $number > 3.5){
				$ret = 'A';
				}elseif($number <= 3.5 && $number > 3.25){
				$ret = 'A-';
				}elseif($number <= 3.25 && $number > 3){
				$ret = 'B+';
				}elseif($number <= 3 && $number > 2.75){
				$ret = 'B';
				}elseif($number <= 2.75 && $number > 2.5){
				$ret = 'B-';
				}elseif($number <= 2.5 && $number > 2.25){
				$ret = 'C+';
				}elseif($number <= 2.25 && $number > 2){
				$ret = 'C';
				}elseif($number <= 2 && $number > 1.75){
				$ret = 'C-';
				}elseif($number <= 1.75 && $number > 0){
				$ret = 'D';
				}else{
				$ret = '-';
			}
		}
		return $ret;
	}
	
	function validateDate($date, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	
	function formatTanggal($Ymd)
	{
		if(!validateDate($Ymd, 'Y-m-d')) return '-';
		$date = explode('-', $Ymd);
		$bln = $date[1];
		return $date[2] . ' ' . config('custom.bulan')[$bln] . ' ' . $date[0];
	}
	
	function formatTanggalWaktu($time) //Y-m-d H:i:s
	{
		if(!validateDate($time)) return '-';
		$time = strtotime($time);
		return config('custom.hari')[date('N', $time)] . ', ' . date('d', $time) . ' ' . config('custom.bulan')[date('m', $time)] . ' ' . date('Y', $time) . ' ' . date('H:i:s', $time);
	}
	
	//13-12-2000 -> 2000-12-13
	function toYmd($dmY)
	{
		if(!validateDate($dmY, 'd-m-Y')) return '';
		if($dmY == '' or empty($dmY) or !isset($dmY)) $dmY = '00-00-0000';
		$date = explode('-', $dmY);
		return $date[2] . '-' . $date[1] . '-' . $date[0];
	}
