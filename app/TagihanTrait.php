<?php
	namespace Siakad;
	
	trait TagihanTrait{
		
		function getTagihan($mahasiswa, $jenis_biaya_id=null, $tapel=null)
		{
			$tagihan = \Siakad\Tagihan::join('setup_biaya', 'setup_biaya.id', '=', 'setup_biaya_id')
			-> where('mahasiswa_id', $mahasiswa -> id)
			-> where('angkatan', $mahasiswa -> angkatan)
			-> where('prodi_id', $mahasiswa -> prodi_id)
			-> where('kelas_id', $mahasiswa -> kelasMhs)
			-> where('jenisPembayaran', $mahasiswa -> jenisPembayaran);
			
			if($jenis_biaya_id !== null)
			{
				$tagihan = $tagihan -> where('jenis_biaya_id', $jenis_biaya_id);	
			}
			if($tapel !== null)
			{
				$tagihan = $tagihan -> where('tapel', $tapel);	
			}
			
			$tagihan = $tagihan -> get([
			'jenis_biaya_id', 
			'tagihan.id', 'tagihan.jumlah', 'tagihan.bayar', 'tagihan.tapel',
			'tagihan.privilege',
			'tagihan.privilege_krs', 
			'tagihan.privilege_uts', 
			'tagihan.privilege_uas', 
			'tagihan.privilege_wis',
			'setup_biaya.krs as persen_syarat_krs', 
			'setup_biaya.uts as persen_syarat_uts',
			'setup_biaya.uas as persen_syarat_uas'
			]);
			
			return $tagihan;
		}	
		
		function generateTagihan($mahasiswa_id, $persemester_only=false, $nama_ta=null)
		{	
			date_default_timezone_set('Asia/Jakarta');
			
			$mahasiswa = \Siakad\Mahasiswa::find($mahasiswa_id);
			$tg = \Siakad\BiayaKuliah::jenisPembayaran([
			'angkatan' => $mahasiswa -> angkatan, 
			'prodi_id' => $mahasiswa -> prodi_id, 
			'program_id' => $mahasiswa -> kelasMhs, 
			'jenisPembayaran' => $mahasiswa -> jenisPembayaran
			]) -> get();
			
			if(!$tg -> count()) return false;
			
			// Cek Tagihan Terdaftar;
			$tdf = [];
			$terdaftar = \Siakad\Tagihan::rincian($mahasiswa_id) -> get();
			foreach($terdaftar as $d)
			{
				switch($d -> periode)
				{
					case 2:
					$tdf[2][] = '' . $d -> jenis_biaya_id . '-' . $d -> bulan;
					break;
					
					case 3:
					$tdf[3][] = '' . $d -> jenis_biaya_id . '-' . $d -> tapel;
					break;
					
					case 4:
					$tdf[4][] = '' . $d -> jenis_biaya_id . '-' . $d -> tahun;
					break;
					
					default:
					$tdf[$d -> periode][] = '' . $d -> jenis_biaya_id;
				}				
			}
			
			$query_ta = $nama_ta != null ? $nama_ta : '(SELECT nama2 FROM tapel WHERE aktif = "y")';
			$tapel = \Siakad\Tapel::where('nama2', '>=', $mahasiswa -> tapelMasuk) 
			-> whereRaw('nama2 <= ' . $query_ta)
			-> orderBy('nama2') 
			-> pluck('nama2');
			
			$masuk = date_create($mahasiswa -> angkatan . '-07-01');
			$sekarang = date_create();
			$interval = date_diff($masuk, $sekarang);
			
			$jbulan = $interval -> m + ($interval -> y * 12);
			$jtahun =  $interval -> y;
			
			$data = [];
			foreach($tg as $t)
			{
				if($t -> tanggungan >= 0)
				{
					if($persemester_only) // Penyesuaian SPP BRIS
					{
						if($t -> periode == 3) //persemester
						{
							if(!isset($tdf[3])) $tdf[3] = [];
							// for($semester = 1; $semester <= $mahasiswa -> semesterMhs; $semester++) 
							// Kasus ada mhs yg semester lebih rendah dari seharusnya, 
							// sehingga tagihan tidak ter-input secara penuh jika berpatokan pada semester
							foreach($tapel as $ta)
							{
								$jml_cicilan = json_decode($t -> cicilan, true);
								
								if(is_array($jml_cicilan) && count($jml_cicilan) > 0)
								{
									foreach($jml_cicilan as $k => $v)
									{
										//is_terdaftar?
										if(!in_array('' . $t -> jenis_biaya_id . '-' . $ta, $tdf[3]))
										{
											$data[] = [
											'setup_biaya_id' 	=> $t -> setup_biaya_id,
											'nama' 					=> $t -> nama . ' ' . $ta . ' Cicilan ' . $k,
											'tgl_cicilan_awal' 	=> $v['tgla'],
											'tgl_cicilan_akhir' 	=> $v['tglb'],
											'override' 				=> 'n',
											'mahasiswa_id' 	=> $mahasiswa_id,
											'tapel' 					=> $ta,
											'jumlah' 				=> $v['jml'],
											'bayar' 					=> 0,
											'bank_id' 				=> $t -> bank_id,
											'bulan'					=> '',
											'tahun' 					=> ''
											];
										}									
									}									
								}		
								else
								{
									if(!in_array('' . $t -> jenis_biaya_id . '-' . $ta, $tdf[3]))
									{
										$data[] = [
										'setup_biaya_id' 	=> $t -> setup_biaya_id,
										'nama' 					=> $t -> nama . ' ' . $ta,
										'tgl_cicilan_awal' 	=> null,
										'tgl_cicilan_akhir' 	=> null,
										'override' 				=> 'n',
										'mahasiswa_id' 	=> $mahasiswa_id,
										'tapel' 					=> $ta,
										'jumlah' 				=> $t -> tanggungan,
										'bayar' 					=> 0,
										'bank_id' 				=> $t -> bank_id,
										'bulan'					=> '',
										'tahun' 					=> ''
										];
									}
								}
							}
						}
					}
					else
					{
						if($t -> periode == 2) // perbulan
						{
							if(!isset($tdf[2])) $tdf[2] = [];
							for($b = 0; $b < $jbulan; $b++)
							{
								$bulan = $masuk -> add(new \DateInterval('P1M'));
								$bln = $bulan -> format('M Y');
								if(!in_array('' . $t -> jenis_biaya_id . '-' . $bln, $tdf[2]))
								{
									$data[] = [
									'setup_biaya_id' 	=> $t -> setup_biaya_id,
									'nama' 	=> $t -> nama . ' ' . $bln,
									'tgl_cicilan_awal' 	=> null,
									'tgl_cicilan_akhir' 	=> null,
									'override' 	=> 'n',
									'mahasiswa_id' 	=> $mahasiswa_id,
									'tapel' 				=> '',
									'jumlah' 			=> $t -> tanggungan,
									'bayar' 			=> 0,
									'bank_id' 			=> $t -> bank_id,
									'bulan' 				=> $bln,
									'tahun'				=> ''
									];
								}
							}
						}
						
						elseif($t -> periode == 3) //persemester
						{
							
							/*	
								DO NOTHING
								// for($semester = 1; $semester <= $mahasiswa -> semesterMhs; $semester++) 
								//Kasus ada mhs yg semester lebih rendah dari seharusnya, 
								//sehingga tagihan tidak ter-input secara penuh jika berpatokan pada semester
								foreach($tapel as $ta)
								{
								$data[] = [
								'setup_biaya_id' 	=> $t -> setup_biaya_id,
								'mahasiswa_id' 	=> $mahasiswa -> id,
								'bulan'				=> '',
								'tapel' 				=> $ta,
								'tahun' 				=> '',
								'jumlah' 			=> $t -> tanggungan
								];
								}
							*/
						}			
						
						elseif($t -> periode == 4) //pertahun
						{
							if(!isset($tdf[4])) $tdf[4] = [];
							$tahun = $mahasiswa -> angkatan;
							for($b = 0; $b <= $jtahun; $b++)
							{
								$thn = $tahun + $b;
								if(!in_array('' . $t -> jenis_biaya_id . '-' . $thn, $tdf[4]))
								{
									$data[] = [
									'setup_biaya_id' 	=> $t -> setup_biaya_id,
									'nama' 					=> $t -> nama . ' ' . $thn,
									'tgl_cicilan_awal' 	=> null,
									'tgl_cicilan_akhir' 	=> null,
									'override' 	=> 'n',
									'mahasiswa_id' 	=> $mahasiswa_id,
									'tapel' 					=> '',
									'jumlah' 				=> $t -> tanggungan,
									'bayar' 			=> 0,
									'bank_id' 			=> $t -> bank_id,
									'bulan' 				=> '',
									'tahun' 				=>  $thn
									];
								}
							}
						}
						else
						{
							if(!isset($tdf[$t -> periode])) $tdf[$t -> periode] = [];
							
							if(!in_array('' . $t -> jenis_biaya_id, $tdf[$t -> periode]))
							{
								$data[] = [
								'setup_biaya_id' 	=> $t -> setup_biaya_id,
								'nama' 	=> $t -> nama,
								'tgl_cicilan_awal' 	=> null,
								'tgl_cicilan_akhir' 	=> null,
								'override' 	=> 'n',
								'mahasiswa_id' 	=> $mahasiswa_id,
								'tapel' 				=> '',
								'jumlah' 			=> $t -> tanggungan,
								'bayar' 			=> 0,
								'bank_id' 			=> $t -> bank_id,
								'bulan' 				=> '',
								'tahun' 				=> ''
								];
							}
						}
					}
					
				}
			}
			
			// dd($data);
			if(count($data) > 0) Tagihan::insertIgnore($data);
			
			return true;
		}
	}																																															