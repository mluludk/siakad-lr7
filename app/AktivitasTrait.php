<?php
	namespace Siakad;
	
	trait AktivitasTrait{
		// public function hitungUlangAKM($mahasiswa_id, $nim, $tapel_id, $prodi_id)
		public function hitungUlangAKM($mahasiswa_id, $tapel_id)
		{
			$mahasiswa = \Siakad\Mahasiswa::where('id', $mahasiswa_id) -> first(['id', 'tapelMasuk', 'semesterMhs', 'prodi_id', 'NIM']);	
			$skala = $this -> skala($mahasiswa -> prodi_id);
			$tapel_now = \Siakad\Tapel::whereId($tapel_id) -> first(['id', 'nama2']);
			$tapel = \Cache::get('tapel_list', function(){
				$t = \Siakad\Tapel::orderBy('nama2') -> pluck('id', 'nama2');
				\Cache::put('tapel_list', $t, 60);
				return $t;
			});
			$ipk = $jsks = $jsksn = 0;
			foreach($tapel as $n => $id)
			{
				if($n >= $mahasiswa -> tapelMasuk and $n <= $tapel_now -> nama2)
				{					
					$data = \Siakad\Nilai::dataKHS($mahasiswa -> NIM, $id) -> get();
					if(!$data -> count()) return ['status' => 'warning', 'message' => 'Data Nilai Mahasiswa '. $mahasiswa -> NIM .' tidak ditemukan.'];
					
					$cursks = $cursksn =  $disc =  $ip = 0;
					if(count($data) >= 1)
					{
						foreach($data as $d) 
						{
							if($d -> id == $tapel_id)
							{
								$cursks += $d -> sks;
								$cursksn += array_key_exists($d -> nilai, $skala) ? $skala[$d -> nilai]['angka'] * $d -> sks : 0; 
								$ip = $cursks < 1 ? 0 : number_format($cursksn / $cursks, 2);
							}
							
							if(!array_key_exists($d -> nilai, $skala)) $disc += $d -> sks;
							$jsks += $d -> sks;
							$jsksn += array_key_exists($d -> nilai, $skala) ? $skala[$d -> nilai]['angka'] * $d -> sks : 0;
							
						}
						
						$div = ($jsks - $disc) < 1 ? $jsks : ($jsks - $disc);
						$ipk = $jsks < 1 ? 0 : number_format($jsksn / $div, 2);
					}
				}
			}
			
			if($jsks > 0)
			{
				// $mahasiswa = \Siakad\Mahasiswa::where('id', $mahasiswa_id) -> first(['id', 'tapelMasuk', 'semesterMhs']);		
				
				$smt = hitungSemester($mahasiswa -> tapelMasuk, $tapel_now['nama2']); // Helpers
				
				\Siakad\Aktivitas::where('tapel_id', $tapel_id) 
				-> where('mahasiswa_id', $mahasiswa_id) 
				-> update([
				'semester' => $smt,
				'jsks' => $cursks,
				'total_sks' => $jsks,
				'jsksn' => $jsksn,
				'ips' => $ip,
				'ipk' => $ipk
				]);
			}
			
			return ['status' => 'success', 'message' => 'Data AKM Mahasiswa '. $mahasiswa -> NIM .' berhasil diperbarui.'];
		}
	}						