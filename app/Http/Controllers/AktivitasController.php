<?php
	
	namespace Siakad\Http\Controllers;
	
	use Cache;
	use Redirect;
	
	use Siakad\Aktivitas;
	use Siakad\Nilai;
	use Siakad\Skala;
	use Siakad\Tapel;
	use Siakad\Mahasiswa;
	
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	
	class AktivitasController extends Controller
	{
		use \Siakad\AktivitasTrait;
		use \Siakad\SkalaTrait;
		use \Siakad\TapelTrait;
		use \Siakad\ProdiTrait;
		
		public function hitungUlangAKMMhsAktif($prodi_singkatan=null, $tapel_nama2=null)
		{
			if($prodi_singkatan == null or $tapel_nama2 == null) echo 'Invalid Parameter';
			else
			{
				$ok = true;
				$prodi = \Siakad\Prodi::where('singkatan', $prodi_singkatan) -> first();
				if(!$prodi) $ok = false;
				
				$tapel = \Siakad\Tapel::where('nama2', $tapel_nama2) -> first();
				if(!$tapel) $ok = false;
				
				if($ok)
				{
					$data_mhs = Mahasiswa::where('prodi_id', $prodi -> id) -> where('statusMhs', 1) -> get(['id', 'NIM']);
					if($data_mhs -> count())
					{
						foreach($data_mhs as $m)
						{
							$proses = $this -> hitungUlangAKM($m -> id, $tapel -> id);
							echo $proses['message'] . '<br/>';
						}
						echo 'Done!';
						return;
					}
					echo 'No Data';
				}
				echo 'Invalid Data';
			}
		}
		
		public function recount2($prodi_id, $angkatan, $tapel_id)
		{
			$errors = [];
			$success = 0;
			$mahasiswa = Mahasiswa::join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
			-> where('prodi.kode_dikti', $prodi_id)
			-> where('mahasiswa.angkatan', $angkatan)
			-> select('mahasiswa.id', 'mahasiswa.nama', 'NIM')
			-> get();
			
			if($mahasiswa -> count())
			{
				foreach($mahasiswa as $m)
				{
					$proses = $this -> hitungUlangAKM($m -> id, $tapel_id);
					
					if($proses['status'] == 'warning') $errors[] = $proses['message'];
					if($proses['status'] == 'success') $success++;
				}
				
				$failed_msg = '';
				if(count($errors) > 0)
				{
					$failed_msg = '<ul>';
					foreach($errors as $e)
					{
						$failed_msg .= '<li>' . $e . '</li>';
					}
					$failed_msg .= '</ul>';
				}
			}
			
			
			//with
			$msg_success = $success > 0 ? $success . ' data berhasil diproses.' : null;
			$msg_danger = ($failed_msg != '') ? $failed_msg : null;
			
			return \Redirect::back() 
			-> with('success', $msg_success)
			-> with('danger_raw', $msg_danger);			
		}
		
		public function recount($mahasiswa_id, $tapel_id)
		{
			$proses = $this -> hitungUlangAKM($mahasiswa_id, $tapel_id);
			
			if($proses['status'] == 'warning') return Redirect::back() -> with('warning', $proses['message']);
			if($proses['status'] == 'success') return Redirect::back() -> with('success', $proses['message']);
			
		}
		
		public function update(Request $request)
		{
			$input = $request -> all();
			$c = 0;
			if(count($input['status']) > 0)
			{
				foreach($input['status'] as $mahasiswa_id => $status)
				{
					if(Aktivitas::where('tapel_id', $input['tapel_id']) -> where('mahasiswa_id', $mahasiswa_id) -> update(['status' => $status])) $c++;					
				}
				return Redirect::back() -> with('success', 'Status '. $c .' Mahasiswa berhasil diubah.');
			}
			return Redirect::back();	
		}
		
		public function index(Request $request)
		{;
			$ta_list = $this -> getTapelSelection('desc');
			$pr_list = $this -> getProdiSelection();
			
			if(intval($request -> get('ta') > 0))
			{
				$ta = Tapel::find($request -> get('ta'));
			}
			else
			{
				$ta = Tapel::whereAktif('y') -> first();
			}
			if(!$ta) return Redirect::to('/') -> withErrors(['NOT_FOUND' => 'Data Tahun Akademik belum diisi.']);
			$cur_ta = $ta -> id;
			
			$cur_pr = intval($request -> get('prodi')) > 0 ? $request -> get('prodi') : 1;
			
			$akm = [];
			$tmp = Aktivitas::akm($cur_ta, $cur_pr) -> get();
			foreach($tmp as $a)
			{
				$akm[$a -> angkatan][] = $a;
			}
			ksort($akm);
			
			return view('mahasiswa.akm', compact('ta_list', 'pr_list', 'ta', 'cur_ta', 'cur_pr', 'akm'));
		}
		
		
		
		// cleaning
		public function cleaning($tapel_id = null)
		{
			if($tapel_id == null) return 'Tahun Akademik belum diisi';
			
			$tapel = \Siakad\Tapel::find($tapel_id);
			if(!$tapel) return 'Tahun Akademik tidak ditemukan';
			
			$tapel_aktif = \Siakad\Tapel::whereAktif('y') -> first();
			$aktif = substr($tapel_aktif -> nama2, 0, 4);
			
			$angkatan = [];
			$data = Aktivitas::with('mahasiswa') -> where('tapel_id', $tapel_id) -> get();
			foreach($data as $mhs)
			{
				$angkatan[$mhs -> mahasiswa -> angkatan][] = ['id' => $mhs -> mahasiswa_id, 'status' => $mhs -> status];
			}
			
			$keep = [];
			foreach($angkatan as $k => $v)
			{
				if($k < ($aktif - 4))
				{
					foreach($v as $mhs)
					{
						if(intval($mhs['status']) == 1 or intval($mhs['status']) == 9)
						{
							if(!in_array($k, $keep)) $keep[] = $k;
						}
					}
				}
				else
				$keep[] = $k;
			}
			
			$del = [];
			foreach($angkatan as $k => $v)
			{
				if(in_array($k, $keep)) unset($angkatan[$k]);
				else
				{
					foreach($v as $mhs)
					{
						$del[] = $mhs['id'];
					}
				}
			}
			
			$delete = Aktivitas::where('tapel_id', $tapel_id) -> whereIn('mahasiswa_id', $del) -> delete();
			return 'Berhasil menghapus ' . $delete . ' data';
		}
		
		// GENERATOR
		public function generateData()
		{
			$mahasiswa = Mahasiswa::orderBy('NIM') -> get();
			$tapel = Tapel::orderBy('nama2') -> get();
			
			foreach($mahasiswa as $m)
			{
				$skala = $this -> skala($m -> prodi_id);
				$jsks = $jsksn = $ipk = $disc = 0;
				foreach($tapel as $t)
				{
					$sks = $sksn = $ips = 0;
					$nilai = Nilai::aktivitasPerkuliahan($m -> id, $t -> id) -> get();
					
					if($nilai -> count())
					{
						foreach($nilai as $n) 
						{
							$sks += $n -> sks;
							$sksn += array_key_exists($n -> nilai, $skala) ? $skala[$n -> nilai]['angka'] * $n -> sks : 0; 
							$jsks += $n -> sks;
							$jsksn += array_key_exists($n -> nilai, $skala) ? $skala[$n -> nilai]['angka'] * $n -> sks : 0; 
							
							// diskon sks jika nilai blm masuk;
							if(!array_key_exists($n -> nilai, $skala)) $disc += $n -> sks;
						}
						$ips = $sks < 1 ? 0 : round($sksn / $sks, 2);
						
						$div = ($jsks - $disc) < 1 ? $jsks : ($jsks - $disc);
					$ipk = $jsks < 1 ? 0 : round($jsksn / $div, 2);
					
					$data[] = [
					'tapel_id' => $t -> id,
					'status' => 1,
					'mahasiswa_id' => $m -> id,
					'jsks' => $sks,
					'total_sks' => $jsks,
					'ips' => $ips,
					'ipk' => $ipk,
					];
					}
					}
					}
					
					$query = '';
					$insert = 'INSERT IGNORE INTO `aktivitas_perkuliahan` (`tapel_id`, `status`, `mahasiswa_id`, `jsks`, `total_sks`, `ips`, `ipk`) VALUES ';
					$qc = 0;
					$dc = count($data);
					foreach($data as $d)
					{
					if($qc % 100 == 0 or $qc == 0) $query.= $insert;
					$query .= '(' . implode(', ', $d) . ')';
					if ( (($qc+1)%100==0 && $qc!=0) || $qc+1==$dc) {$query .= ";\n";} else {$query .= ",";} $qc=$qc+1;
					}
					
					ob_get_clean(); 
					header('Content-Type: application/octet-stream'); 
					header("Content-Transfer-Encoding: Binary"); 
					header("Content-disposition: attachment; filename=\"aktivitas_perkuliahan.sql\"");
					echo $query;
					exit;
					}
					
					public function updateSemester()
					{
					// $sql = [];
					$sql = '';
					$tapel = Tapel::orderBy('nama2') -> pluck('id', 'nama2');
					$mahasiswa = Mahasiswa::where('tapelMasuk', '>=', 20121) -> orderBy('NIM') -> get(['id', 'tapelMasuk', 'semesterMhs']);
					foreach($mahasiswa as $m)
					{
					$smt = 1;
					foreach($tapel as $n => $id)
					{
					if($n >= $m -> tapelMasuk && $smt <= $m -> semesterMhs)
					{
					// $sql[$m -> id][] = 'UPDATE aktivitas_perkuliahan SET semester = ' . $smt . ' WHERE mahasiswa_id = ' . $m -> id . ' AND tapel_id = ' . $id . ';';
					$sql .= 'UPDATE aktivitas_perkuliahan SET semester = ' . $smt . ' WHERE mahasiswa_id = ' . $m -> id . ' AND tapel_id = ' . $id . ';' . PHP_EOL;
					$smt++;
					}
					}
					}
					ob_get_clean(); 
					header('Content-Type: application/octet-stream'); 
					header("Content-Transfer-Encoding: Binary"); 
					header("Content-disposition: attachment; filename=\"update_semester_aktivitas_perkuliahan.sql\"");
					echo $sql;
					exit;
					}
					}																																																																																																																					