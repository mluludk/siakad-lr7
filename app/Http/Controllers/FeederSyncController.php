<?php
	
	namespace Siakad\Http\Controllers;
	
	use Cache;
	use Redirect;
	
	use Siakad\Skala;
	use Siakad\Dosen;
	use Siakad\DosenSkripsi;
	use Siakad\MahasiswaPrestasi;
	use Siakad\Mahasiswa;
	use Siakad\Aktivitas;
	use Siakad\SettingTapel;
	use Siakad\MatkulTapel;
	use Siakad\KrsDetail;
	use Siakad\Nilai;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	
	class FeederSyncController extends Controller
	{			
		use \Siakad\TapelTrait;		
		use \Siakad\ProdiTrait;		
		use \Siakad\MahasiswaTrait;	
		use \Siakad\WebServiceTrait;	
		use \Siakad\WebServiceSoapTrait;	
		
		//DEBUG
		public function wsDebugger(Request $request)
		{
			$input = $request -> all();
			
			$token = $this -> getToken();
			$result = null;
			switch($input['mode'])
			{
				case 'list':
				$result = $this -> ListTable($token);
				break;
				
				case 'dict':
				$result = $this -> getDictionary($token, $input['table']);
				break;
			}
			dd($result);
		}
		
		//AKM
		public function getAKM(Request $request)
		{
			$input = $request -> all();
			if(
			(isset($input['kode_dikti']) and $input['kode_dikti'] == '-') or 
			(isset($input['tapel_id']) and $input['tapel_id'] == '-') or 
			(isset($input['angkatan']) and $input['angkatan'] == '-')
			) 
			return \Redirect::back() -> with('warning', 'Prodi dan/atau Angkatan belum dipilih');
			
			$prodi_select = $this -> getProdiSelection('id', 'asc', true, 'kode_dikti');
			$angkatan_select = $this -> getGolongan('angkatan', true);
			$tapel_select = $this -> getTapelSelection('desc', true, 'id');
			
			$kode_dikti = $tapel_id = $angkatan = $mahasiswa = $mahasiswa_feeder = null;
			$lokal = $feeder = [];
			if(isset($input['kode_dikti']) and isset($input['angkatan']) and isset($input['tapel_id']))
			{
				$kode_dikti = $input['kode_dikti'];
				$tapel_id = $input['tapel_id'];
				$angkatan = $input['angkatan'];
				
				$mahasiswa = Aktivitas::join('mahasiswa', 'mahasiswa.id', '=', 'aktivitas_perkuliahan.mahasiswa_id')
				-> join('tapel', 'aktivitas_perkuliahan.tapel_id', '=', 'tapel.id')
				-> join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
				-> leftJoin('setup_biaya', function($j){
					$j
					-> on('setup_biaya.angkatan', '=', 'mahasiswa.angkatan')
					-> on('setup_biaya.prodi_id', '=', 'mahasiswa.prodi_id')
					-> on('setup_biaya.kelas_id', '=', 'mahasiswa.kelasMhs')
					-> on('setup_biaya.jenisPembayaran', '=', 'mahasiswa.jenisPembayaran');
				})
				-> where('status', 1)
				-> where('jenis_biaya_id', 1)
				-> where('aktivitas_perkuliahan.tapel_id', $input['tapel_id'])
				-> where('prodi.kode_dikti', $input['kode_dikti'])
				-> where('mahasiswa.angkatan', $input['angkatan'])
				-> select(
				'NIM', 'mahasiswa.nama', 
				'tapel.nama as nama_tapel', 'nama2',
				'ips', 'ipk', 'jsks', 'total_sks', 'status',
				'jumlah as spp'
				)
				-> get();	
				if($mahasiswa -> count())
				{
					foreach($mahasiswa as $m)
					{
						if(!isset($id_smt)) $id_smt = trim($m -> nama_tapel);
						$nim[] = $m -> NIM;
					}
					
					$filter = 'trim(nipd) IN (' . "'" . implode ( "','", $nim ) . "'" . ') AND nm_smt=\''.$id_smt .'\'';
					$feeder = $this -> getAKMFeeder($filter);
					
					$filter = 'trim(nim) IN (' . "'" . implode ( "','", $nim ) . "'" . ')';
					$mahasiswa_feeder  = $this -> getListMahasiswaFeeder(null, null, $filter); 
				}
			}
			return view('feeder.akm', compact('prodi_select', 'angkatan_select', 'tapel_select', 'feeder', 'mahasiswa_feeder', 'kode_dikti', 'angkatan', 'mahasiswa', 'tapel_id'));
		}
		public function postAKM(Request $request)
		{
			$input = $request -> all();
			$update = $insert = $ins_nim = $upd_nim = [];
			$count['failed']['insert'] = ['msg' => 'Terdapat kesalahan pada proses Input data: ','desc' => []];
			$count['failed']['update'] = ['msg' => 'Terdapat kesalahan pada proses Update: ','desc' => []];
			$count['success'] = 0;
			
			if(isset($input['dtt']))
			{
				foreach($input['dtt'] as $r)
				{
					$data = explode(':', $r);
					$insert[] = [
					'id_reg_pd' => $data[0],
					'id_smt' => $data[1],
					// 'ips' => $data[2],
					'sks_smt' => $data[3],
					// 'ipk' => $data[4],
					// 'sks_total' => $data[5],
					'id_stat_mhs' => $data[6],
					'biaya_smt' => $data[7]
					];
					
					$ins_nim[] = $data[8];
				}
				if(count($insert) > 0)
				{
					$token = $this -> getToken();
					$soap = $this -> insertRecord($token, 'kuliah_mahasiswa', json_encode($insert));
					foreach($soap -> result as $k => $r)
					{
						if($r -> error_code > 0)
						{
							if($r -> error_code == 730)
							{
								$count['failed']['insert']['desc'][] = 'NIM ' . $ins_nim[$k] . '. ' . 'Data AKM sudah terdaftar di FEEDER';
							}
							// elseif($r -> error_code == 737)
							// {
							// $count['failed']['insert']['desc'][] = $r -> error_desc;
							// }
							elseif($r -> error_code == 738)
							{
								$count['failed']['insert']['desc'][] = 'NIM ' . $ins_nim[$k] . '. ' . $r -> error_desc . ' Pastikan data KRS sudah di-input';
							}
							else
							{
								$count['failed']['insert']['desc'][] = 'NIM ' . $ins_nim[$k] . '. ' .  $r -> error_desc;
								// $failed[] = "('" . $insert[$k]['id_reg_pd'] . "','" . $insert[$k]['id_smt'] . "')";
							}
						}
						else
						{
							$count['success'] ++;
						}
					}
				}
				
				// if(count($failed) > 0)
				// {
					
				// }
			}
			
			if(isset($input['dt']))
			{
				foreach($input['dt'] as $r)
				{
					$data = explode(':', $r);
					$update[] = [
					'key' => [
					'id_smt' => $data[1],
					'id_reg_pd' => $data[0]
					],
					'data' => [
					// 'ips' => $data[2],
					'sks_smt' => $data[3],
					// 'ipk' => $data[4],
					// 'sks_total' => $data[5],
					'id_stat_mhs' => $data[6],
					'biaya_smt' => $data[7]
					]
					];
					$upd_nim[] = $data[8];
				}
				
				if(count($update) > 0)
				{
					$token = $this -> getToken();
					$soap = $this -> updateRecord($token, 'kuliah_mahasiswa', json_encode($update));
					foreach($soap -> result as $k => $r)
					{
						if($r -> error_code == 0) 
						{
							$count['success'] ++;
						}
						else
						{
							$count['failed']['update']['desc'][] = 'NIM ' . $upd_nim[$k] . '. ' .  $r -> error_desc;
						}
					}
					
				}
			}
			
			$failed_msg = '';
			foreach($count['failed'] as $failed_type)
			{
				if(count($failed_type['desc']) > 0)
				{
					$failed_msg .= $failed_type['msg'] . '<br/><ul>';
					foreach($failed_type['desc'] as $msg)
					{
						$failed_msg .= '<li>' . $msg . '</li>';
					}
					$failed_msg .= '</ul>';
				}
			}
			//with
			$success = $count['success'] > 0 ? $count['success'] . ' data berhasil diproses.' : null;
			$danger = (isset($failed_msg) and $failed_msg != '') ? $failed_msg : null;
			
			return \Redirect::to('/export/feeder/akm/?kode_dikti=' . $input['kode_dikti'] . '&angkatan=' . $input['angkatan'] . '&tapel_id=' . $input['tapel_id']) 
			-> with('success', $success)
			-> with('danger_raw', $danger);
		}
		private function getAKMFeeder($filter='')
		{
			$token = $this -> getToken();
			$soap = $this -> getRecord($token, 'kuliah_mahasiswa', $filter);	
			if (!empty($soap['data'])) 
			{
				foreach ($soap['data'] as $reg) 
				{
					$akm[$reg -> nipd] = [
					'id_smt' => $reg -> id_smt,
					'id_reg_pd'=>$reg -> id_reg_pd,
					'nm_pd' => $reg -> nm_pd,
					'nm_smt' => $reg -> nm_smt,
					'nipd' => $reg -> nipd,
					'ips' => $reg -> ips,
					'ipk' => $reg -> ipk,
					'sks_smt' => $reg -> sks_smt,
					'sks_total' => $reg -> sks_total,
					'biaya_smt' => $reg -> biaya_smt,
					'id_stat_mhs' => $reg -> id_stat_mhs
					];
				} 
				return $akm;
			}
			
			return false;
		}
		
		//RIWAYAT Pendidikan
		public function getRiwayatPendidikan(Request $request)
		{
			$input = $request -> all();
			if(
			(isset($input['kode_dikti']) and $input['kode_dikti'] == '-') or 
			(isset($input['angkatan']) and $input['angkatan'] == '-')
			) 
			return \Redirect::back() -> with('warning', 'Prodi dan/atau Angkatan belum dipilih');
			
			$prodi_select = $this -> getProdiSelection('id', 'asc', true, 'kode_dikti');
			$angkatan_select = $this -> getGolongan('angkatan', true);
			
			$jenis = config('custom.pilihan.jenisPendaftaranPDDIKTI');
			$jalur = config('custom.pilihan.jalurMasuk');
			$biaya = config('custom.pilihan.jenisPembiayaanAwal');
			
			$mahasiswa = $id_prodi_feeder = $kode_dikti = $angkatan = null;
			$mhs_terdaftar = $pre_terdaftar = $lokal = $riwayat = [];
			
			if(isset($input['kode_dikti']) and isset($input['angkatan']))
			{
				$mahasiswa = Mahasiswa::join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
				-> leftJoin('setup_biaya', function($j){
					$j
					-> on('setup_biaya.angkatan', '=', 'mahasiswa.angkatan')
					-> on('setup_biaya.prodi_id', '=', 'mahasiswa.prodi_id')
					-> on('setup_biaya.kelas_id', '=', 'mahasiswa.kelasMhs')
					-> on('setup_biaya.jenisPembayaran', '=', 'mahasiswa.jenisPembayaran');
				})
				-> join('jenis_biaya', 'jenis_biaya.id', '=','setup_biaya.jenis_biaya_id')
				-> where('golongan', 1)
				-> where('prodi.kode_dikti', $input['kode_dikti'])
				-> where('mahasiswa.angkatan', $input['angkatan'])
				-> groupBy('mahasiswa.id')
				-> orderBy('NIM')
				-> select(\DB::raw('
				NIM, mahasiswa.nama as nama_mahasiswa, mahasiswa.jenisPendaftaran, jalurMasuk, pembiayaan_awal,
				strata, singkatan, sum(jumlah) as BAMK
				'))
				-> get();
				
				if($mahasiswa -> count())
				{
					foreach($mahasiswa as $m)
					{
						$nim[] = $m -> NIM;
						$lokal[$m -> NIM] = [
						'id_jenis_daftar' => $m -> jenisPendaftaran,
						'id_jalur_daftar' => $m -> jalurMasuk,
						'id_pembiayaan' => $m -> pembiayaan_awal,
						'biaya_masuk' => $m -> BAMK
						];
					}
					$filter = 'trim(nim) IN (' . "'" . implode ( "','", $nim ) . "'" . ')';
					$riwayat = $this -> getRiwayatPendidikanFeeder($filter);
				}
				$kode_dikti = $input['kode_dikti'];
				$angkatan = $input['angkatan'];
			}
			
			return view('feeder.riwayat', compact('prodi_select', 'angkatan_select', 'riwayat', 'kode_dikti', 'angkatan', 'jenis', 'jalur', 'biaya', 'lokal'));
		}
		
		public function postRiwayatPendidikan(Request $request)
		{
			$input = $request -> all();
			$update_failed = [];
			$count['failed']['update'] = ['msg' => 'Terdapat kesalahan pada proses Update: ','desc' => []];
			$count['success'] = 0;
			
			foreach($input['dt'] as $r)
			{
				$data = explode(':', $r);
				$result = $this -> updateRiwayatPendidikanFeeder($data);
				if($result == 'success') 
				{
					$count['success'] ++;
				}
				else
				{
					$count['failed']['update']['desc'][] = $result;
					$update_failed[] = $r;
				}
			}
			
			$failed_msg = '';
			foreach($count['failed'] as $failed_type)
			{
				if(count($failed_type['desc']) > 0)
				{
					$failed_msg .= $failed_type['msg'] . '<br/><ul>';
					foreach($failed_type['desc'] as $msg)
					{
						$failed_msg .= '<li>' . $msg . '</li>';
					}
					$failed_msg .= '</ul>';
				}
			}
			//with
			$success = $count['success'] > 0 ? $count['success'] . ' data berhasil diproses.' : null;
			$danger = (isset($failed_msg) and $failed_msg != '') ? $failed_msg : null;
			
			return \Redirect::to('/update/feeder/riwayat/?kode_dikti=' . $input['kode_dikti'] . '&angkatan=' . $input['angkatan']) 
			-> with('success', $success)
			-> with('danger_raw', $danger);
		}
		
		private function updateRiwayatPendidikanFeeder($data)
		{
			$ws = $this -> getWSData([
			'act' => 'UpdateRiwayatPendidikanMahasiswa',
			'key' => ['id_registrasi_mahasiswa' => $data[0]],
			'record' => [
			'id_jenis_daftar' => $data[1],
			'id_jalur_daftar' => $data[2],
			'id_pembiayaan' => $data[3],
			'biaya_masuk' => $data[4]
			]
			]);
			
			if($ws['success'])
			{
				return 'success';
			}
			return $ws['error_desc'];
		}
		private function getRiwayatPendidikanFeeder($filter)
		{
			$ws = $this -> getWSData([
			'act' => 'GetListRiwayatPendidikanMahasiswa',
			'filter' => $filter
			]);
			
			if($ws['success'])
			{
				return $ws['data'];
			}
			else
			{
				return \Redirect::to('/') -> with('warning', $ws['error_desc']); 	
			}
		}
		
		//DELETE KELULUSAN
		public function feederKelulusanDelete(Request $request)
		{
			$input = $request -> all();
			if($input['type'] == 'check')
			{
				$result['type'] = 'Kelulusan';
				$result['success'] = $result['failed'] = 0;
				if(is_array($input['id']))
				{					
					foreach($input['id'] as $i)
					{
						if($this -> deleteKelulusan($i)) $result['success'] ++;
						else $result['failed'] ++;
					}	
				}	
				return \Response::json($result);
			}
			elseif($input['type'] == 'data')
			{
				if($this -> deleteKelulusan($input['id_reg_pd']))
				{
					$success = 'Kelulusan berhasil dihapus.';
					$danger = null;
				}
				else
				{
					$success = null;
					$danger = 'Kelulusan gagal dihapus.';
				}
				return \Redirect::to('/export/feeder/kelulusan/?kode_dikti=' . $input['kode_dikti'] . '&angkatan=' . $input['angkatan']) 
				-> with('success', $success)
				-> with('danger', $danger);				
			}
		}
		private function deleteKelulusan($id_reg_pd)
		{
			$token = $this -> getToken();
			
			// delete pembimbing
			$soap = $this -> getRecord($token,'dosen_pembimbing', "id_reg_pd='". $id_reg_pd ."'");
			
			if (!empty($soap -> result)) {
				foreach ($soap -> result as $value) {
					$temp_key[] = [
					'id_sdm' => $value -> id_sdm,
					'id_reg_pd' => $id_reg_pd
					];
				}
				$soap = $this -> deleteRecord($token, 'dosen_pembimbing', json_encode($temp_key));
			}
			
			// DELETE AKM
			$del_data[] = ['id_reg_pd' => $id_reg_pd];
			$this -> deleteRecord($token, 'aktivitas_mahasiswa', json_encode($del_data));			
			$this -> deleteRecord($token, 'anggota_aktivitas_mahasiswa', json_encode($del_data));
			
			//DELETE KELULUSAN					
			$array_mhs[] = [
			'key' => ['id_reg_pd' => $id_reg_pd],
			'data'=>[
			'id_jns_keluar' => '',
			'tgl_keluar' => '',
			'judul_skripsi' => '',
			'jalur_skripsi' => '',
			'bln_awal_bimbingan' => '',
			'bln_akhir_bimbingan' => '',
			'sk_yudisium' => '',
			'tgl_sk_yudisium' => '',
			'ipk' => '',
			'no_seri_ijazah' => ''
			]];
			
			$soap = $this -> updateRecord($token, 'mahasiswa_pt', json_encode($array_mhs));			
			if($soap -> error_code != 0) return false;		
			
			return true;
		}
		
		//DELETE Krs
		public function feederKrsDelete(Request $request)
		{
			$input = $request -> all();
			if($input['type'] == 'check')
			{
				$result['type'] = 'KRS';
				$result['success'] = $result['failed'] = 0;
				if(is_array($input['id']))
				{					
					foreach($input['id'] as $i)
					{
						$id = explode(':', $i);
						if($this -> deleteKrs($id[0], $id[1])) $result['success'] ++;
						else $result['failed'] ++;
					}	
				}	
				return \Response::json($result);
			}
			elseif($input['type'] == 'data')
			{
				if($this -> deleteKrs($input['id_kls'], $input['id_reg_pd']))
				{
					$success = 'KRS berhasil dihapus.';
					$danger = null;
				}
				else
				{
					$success = null;
					$danger = 'KRS gagal dihapus.';
				}
				return \Redirect::to('/export/feeder/krs/?prodi_id=' . $input['prodi_id'] . '&tapel_id=' . $input['tapel_id'] . '&angkatan=' . $input['angkatan']) 
				-> with('success', $success)
				-> with('danger', $danger);				
			}
		}
		private function deleteKrs($id_kls, $id_reg_pd)
		{
			$token = $this -> getToken();
			
			//DELETE KRS
			$del_krs[] = ['id_kls' => $id_kls, 'id_reg_pd' => $id_reg_pd];
			$soap = $this -> deleteRecord($token, 'nilai', json_encode($del_krs));	
			if($soap -> error_code != 0) return false;		
			
			return true;
		}
		
		//DELETE KELAS
		public function feederKelasDelete(Request $request)
		{
			$input = $request -> all();
			if($input['type'] == 'check')
			{
				$result['type'] = 'Kelas';
				$result['success'] = $result['failed'] = 0;
				if(is_array($input['id']))
				{					
					foreach($input['id'] as $i)
					{
						if($this -> deleteKelas($i)) $result['success'] ++;
						else $result['failed'] ++;
					}	
				}	
				return \Response::json($result);
			}
			elseif($input['type'] == 'data')
			{
				if($this -> deleteKelas($input['id_kls']))
				{
					$success = 'Kelas berhasil dihapus.';
					$danger = null;
				}
				else
				{
					$success = null;
					$danger = 'Kelas gagal dihapus.';
				}
				return \Redirect::to('/export/feeder/kelaskuliah/?prodi_id=' . $input['prodi_id'] . '&tapel_id=' . $input['tapel_id']) 
				-> with('success', $success)
				-> with('danger', $danger);				
			}				
		}
		private function deleteKelas($id_kls)
		{
			$filter_kls = "p.id_kls='".$id_kls."'";
			$token = $this -> getToken();
			
			//DOSEN AJAR
			$soap = $this -> getRecord($token, 'ajar_dosen', $filter_kls);
			if (!empty($soap['data'])) 
			{
				foreach ($soap['data'] as $reg) 
				{
					$hapus_dsn[] = [
					'id_ajar' => $reg -> id_ajar
					];  
				}
				$soap = $this -> deleteRecord($token, 'ajar_dosen', json_encode($hapus_dsn));
				if($soap -> error_code != 0) return false;
			}	
			
			//KRS
			$soap = $this -> getRecord($token, 'nilai', $filter_kls);
			if (!empty($soap['data'])) 
			{
				foreach ($soap['data'] as $reg) 
				{
					$hapus_krs[] = [
					'id_kls' => $reg -> id_kls,
					'id_reg_pd' => $reg -> id_reg_pd
					];  
				}
				$soap = $this -> deleteRecord($token, 'nilai', json_encode($hapus_krs));
				if($soap -> error_code != 0) return false;
			}	
			
			//DELETE KELAS
			$del_kls[] = ['id_kls' => $id_kls];
			$soap = $this -> deleteRecord($token, 'kelas_kuliah', json_encode($del_kls));	
			if($soap -> error_code != 0) return false;		
			
			return true;
		}
		
		//DELETE MAHASISWA
		public function feederMahasiswaDelete(Request $request)
		{
			$input = $request -> all();
			if($input['type'] == 'check')
			{
				$result['type'] = 'Mahasiswa';
				$result['success'] = $result['failed'] = 0;
				if(is_array($input['id']))
				{					
					foreach($input['id'] as $i)
					{
						$id = explode(':', $i);
						if($this -> deleteMahasiswa($id[0], $id[1])) $result['success'] ++;
						else $result['failed'] ++;
					}	
				}	
				return \Response::json($result);
			}
			elseif($input['type'] == 'data')
			{
				if($this -> deleteMahasiswa($input['id_pd'], $input['id_reg_pd']))
				{
					$success = 'Mahasiswa berhasil dihapus.';
					$danger = null;
				}
				else
				{
					$success = null;
					$danger = 'Mahasiswa gagal dihapus.';
				}
				return \Redirect::to('/export/feeder/mahasiswa/?prodi=' . $input['kode_dikti'] . '&angkatan=' . $input['angkatan']) 
				-> with('success', $success)
				-> with('danger', $danger);				
			}				
		}
		
		private function deleteMahasiswa($id_pd, $id_reg_pd)
		{
			//HAPUS NILAI
			$reg_pd = "p.id_reg_pd='".$id_reg_pd."'";
			$token = $this -> getToken();
			
			$soap = $this -> getRecord($token, 'nilai', $reg_pd);
			if (!empty($soap['data'])) 
			{
				foreach ($soap['data'] as $reg) 
				{
					$hapus_nilai[] = [
					'id_kls' => $reg -> id_kls,
					'id_reg_pd'=>$reg -> id_reg_pd
					];  
				}
				$soap = $this -> deleteRecord($token, 'nilai', json_encode($hapus_nilai));
				if($soap -> error_code != 0) return false;
			}	
			
			// AKM
			$soap = $this -> getRecord($token,'kuliah_mahasiswa',$reg_pd);	
			if (!empty($soap['data'])) 
			{
				foreach ($soap['data'] as $reg) 
				{
					$hapus_akm[] = [
					'id_smt' => $reg -> id_smt,
					'id_reg_pd'=>$reg -> id_reg_pd
					];
				}
				$soap = $this -> deleteRecord($token, 'kuliah_mahasiswa', json_encode($hapus_akm));	
				if($soap -> error_code != 0) return false;			
			}
			
			// ??
			$soap = $this -> getRecord($token,'nilai_transfer',$reg_pd);
			if (!empty($data['data'])) 
			{
				foreach ($data['data'] as $reg) 
				{
					$hapus_nilai_trf[] = [
					'id_ekuivalensi' => $reg -> id_ekuivalensi,
					'id_reg_pd'=>$reg -> id_reg_pd
					];  					
				}
				$soap = $this -> deleteRecord($token, 'nilai_transfer', json_encode($hapus_nilai_trf));
				if($soap -> error_code != 0) return false;
			}
			
			// ANGGOTA AKTIVITAS
			$filter = "id_registrasi_mahasiswa='$id_reg_pd'";
			$aktivitas = $this -> GetListAnggotaAktivitasMahasiswa($filter);
			if(!empty($aktivitas))
			{
				foreach($aktivitas as $id) 
				{
					$hapus_anggota[] = [
					'id_ang_akt_mhs' => $id -> id_anggota
					];  
				}
				$soap = $this->deleteRecord($token, 'anggota_aktivitas_mahasiswa', json_encode($hapus_anggota));
				if($soap -> error_code != 0) return false;
			}
			
			//DELETE MAHASISWA
			$del_reg[] = ['id_reg_pd' => $id_reg_pd];
			$soap = $this -> deleteRecord($token, 'mahasiswa_pt', json_encode($del_reg));	
			if($soap -> error_code != 0) return false;		
			
			$del_id[] = ['id_pd'=>$id_pd];
			$soap = $this -> deleteRecord($token, 'mahasiswa', json_encode($del_id));
			if($soap -> error_code != 0) return false;
			
			return true;
		}		
		
		//SKALA NILAI
		public function getSkala(Request $request)
		{
			$input = $request -> all();
			$prodi_select = $this -> getProdiSelection('id', 'asc', false, 'kode_dikti');
			
			$id_prodi_feeder = $kode_dikti = null;
			$ska_terdaftar = $skala = [];
			
			if(isset($input['kode_dikti']))
			{
				$skala = Skala::join('prodi', 'prodi.id', '=', 'skala.prodi_id')
				-> where('kode_dikti', $input['kode_dikti'])
				-> get();
				
				$id_prodi_feeder = $this -> getIdProdiFeeder($input['kode_dikti']);
				$ska_terdaftar = $this -> GetListSkalaNilaiProdi($id_prodi_feeder);
				$kode_dikti = $input['kode_dikti'];
			}
			
			return view('feeder.skala', compact('prodi_select', 'ska_terdaftar',  'kode_dikti', 'id_prodi_feeder', 'skala'));
		}
		
		public function postSkala(Request $request)
		{
			$input = $request -> all();
			$update = $insert = [];
			$count['failed']['insert'] = ['msg' => 'Terdapat kesalahan pada proses Input: ','desc' => []];
			$count['failed']['update'] = ['msg' => 'Terdapat kesalahan pada proses Update: ','desc' => []];
			$count['success'] = 0;
			
			if(isset($input['st']) and count($input['st']) > 0) 
			{
				foreach($input['st'] as $p)
				{
					$e = explode(':', $p);
					
					$update[] = [
					'key' => ['kode_bobot_nilai' => $e[0]],
					'data' => [
					'nilai_huruf' => $e[2],
					'bobot_nilai_min' => $e[3],
					'bobot_nilai_maks' => $e[4],
					'nilai_indeks' => $e[5],
					'tgl_mulai_efektif' => $e[6],
					'tgl_akhir_efektif' => $e[7]
					]
					];
				}
				
				if(!empty($update))
				{
					$token = $this -> getToken();
					$soap = $this->UpdateRecord($token, 'bobot_nilai', json_encode($update));
					
					foreach($soap -> result as $result)
					{
						if($result -> error_code == '0')
						{
							$count['success'] ++;
						}
						else
						{
							$count['failed']['update']['desc'][] = $result -> error_code . ' ' . $result -> error_desc;
						}
					}
				}
			}
			
			if(isset($input['stt']) and count($input['stt']) > 0) 
			{
				foreach($input['stt'] as $p)
				{
					$e = explode(':', $p);
					
					$insert[] = [
					'id_sms' => $e[1],
					'nilai_huruf' => $e[2],
					'bobot_nilai_min' => $e[3],
					'bobot_nilai_maks' => $e[4],
					'nilai_indeks' => $e[5],
					'tgl_mulai_efektif' => $e[6],
					'tgl_akhir_efektif' => $e[7]
					];
				}
				
				if(!empty($insert))
				{
					$token = $this -> getToken();
					$soap = $this->InsertRecord($token, 'bobot_nilai', json_encode($insert));
					foreach($soap -> result as $result)
					{
						if($result -> error_code == '0')
						{
							$count['success'] ++;
						}
						else
						{
							$count['failed']['insert']['desc'][] = $result -> error_desc;
						}
					}
				}
			}
			
			$failed_msg = '';
			foreach($count['failed'] as $failed_type)
			{
				if(count($failed_type['desc']) > 0)
				{
					$failed_msg .= $failed_type['msg'] . '<br/><ul>';
					foreach($failed_type['desc'] as $msg)
					{
						$failed_msg .= '<li>' . $msg . '</li>';
					}
					$failed_msg .= '</ul>';
				}
			}
			//with
			$success = $count['success'] > 0 ? $count['success'] . ' data berhasil diproses.' : null;
			$danger = (isset($failed_msg) and $failed_msg != '') ? $failed_msg : null;
			
			return \Redirect::to('/export/feeder/skala/?kode_dikti=' . $input['kode_dikti']) 
			-> with('success', $success)
			-> with('danger_raw', $danger);
			
		}
		
		//PERIODE
		public function getPeriode(Request $request)
		{
			$input = $request -> all();
			$prodi_select = $this -> getProdiSelection('id', 'asc', false, 'kode_dikti');
			
			$id_prodi_feeder = $kode_dikti = null;
			$per_terdaftar = $periode = [];
			
			if(isset($input['kode_dikti']))
			{
				$periode = SettingTapel::join('tapel', 'tapel.id', '=', 'setting_tapel.tapel_id')
				-> join('prodi', 'prodi.id', '=', 'setting_tapel.prodi_id')
				-> where('kode_dikti', $input['kode_dikti'])
				-> get();
				
				$per_terdaftar = $this -> GetDetailPeriodePerkuliahan();
				$id_prodi_feeder = $this -> getIdProdiFeeder($input['kode_dikti']);
				$kode_dikti = $input['kode_dikti'];
			}
			
			return view('feeder.periode', compact('prodi_select', 'per_terdaftar',  'kode_dikti', 'id_prodi_feeder', 'periode'));
		}
		public function postPeriode(Request $request)
		{
			$input = $request -> all();
			$update = $insert = [];
			$count['failed']['insert'] = ['msg' => 'Terdapat kesalahan pada proses Input: ','desc' => []];
			$count['failed']['update'] = ['msg' => 'Terdapat kesalahan pada proses Update: ','desc' => []];
			$count['success'] = 0;
			
			if(isset($input['pt']) and count($input['pt']) > 0) 
			{
				foreach($input['pt'] as $p)
				{
					$e = explode(':', $p);
					
					$update[] = [
					'key' => ['id_smt' => $e[0], 'id_sms' => $e[1]],
					'data' => [
					'target_mhs_baru' => $e[2],
					'calon_ikut_seleksi' => $e[3],
					'calon_lulus_seleksi' => $e[4],
					'daftar_sbg_mhs' => $e[5],
					'pst_undur_diri' => $e[6],
					'tgl_awal_kul' => $e[7],
					'tgl_akhir_kul' => $e[8],
					'jml_mgu_kul' => $e[9]
					]
					];
				}
				if(!empty($update))
				{
					$token = $this -> getToken();
					$soap = $this->UpdateRecord($token, 'daya_tampung', json_encode($update));
					foreach($soap -> result as $result)
					{
						if($result -> error_code == '0')
						{
							$count['success'] ++;
						}
						else
						{
							$count['failed']['update']['desc'][] = $result -> error_desc;
						}
					}
				}
			}
			
			if(isset($input['ptt']) and count($input['ptt']) > 0) 
			{
				foreach($input['ptt'] as $p)
				{
					$e = explode(':', $p);
					
					$insert[] = [
					'id_smt' => $e[0], 
					'id_sms' => $e[1],
					'target_mhs_baru' => $e[2],
					'calon_ikut_seleksi' => $e[3],
					'calon_lulus_seleksi' => $e[4],
					'daftar_sbg_mhs' => $e[5],
					'pst_undur_diri' => $e[6],
					'tgl_awal_kul' => $e[7],
					'tgl_akhir_kul' => $e[8],
					'jml_mgu_kul' => $e[9]
					];
				}
				
				if(!empty($insert))
				{
					$token = $this -> getToken();
					$soap = $this->InsertRecord($token, 'daya_tampung', json_encode($insert));
					foreach($soap -> result as $result)
					{
						if($result -> error_code == '0')
						{
							$count['success'] ++;
						}
						else
						{
							$count['failed']['insert']['desc'][] = $result -> error_desc;
						}
					}
				}
			}
			
			$failed_msg = '';
			foreach($count['failed'] as $failed_type)
			{
				if(count($failed_type['desc']) > 0)
				{
					$failed_msg .= $failed_type['msg'] . '<br/><ul>';
					foreach($failed_type['desc'] as $msg)
					{
						$failed_msg .= '<li>' . $msg . '</li>';
					}
					$failed_msg .= '</ul>';
				}
			}
			//with
			$success = $count['success'] > 0 ? $count['success'] . ' data berhasil diproses.' : null;
			$danger = (isset($failed_msg) and $failed_msg != '') ? $failed_msg : null;
			
			return \Redirect::to('/export/feeder/periode/?kode_dikti=' . $input['kode_dikti']) 
			-> with('success', $success)
			-> with('danger_raw', $danger);
		}
		
		//PRESTASI
		public function getPrestasi(Request $request)
		{
			$input = $request -> all();
			$prodi_select = $this -> getProdiSelection('id', 'asc', false, 'kode_dikti');
			$angkatan_select = $this -> getGolongan('angkatan', false);
			
			$mahasiswa = $id_prodi_feeder = $kode_dikti = $angkatan = null;
			$mhs_terdaftar = $pre_terdaftar = [];
			
			if(isset($input['kode_dikti']) and isset($input['angkatan']))
			{
				$mahasiswa = MahasiswaPrestasi::leftJoin('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_id')
				-> leftJoin('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
				-> where('prodi.kode_dikti', $input['kode_dikti'])
				-> where('mahasiswa.angkatan', $input['angkatan'])
				-> orderBy('NIM', 'desc')
				-> get(['NIM', 'mahasiswa.nama as nama_mahasiswa', 'strata', 'singkatan', 'mahasiswa_prestasi.*']);
				
				if($mahasiswa -> count())
				{
					foreach($mahasiswa as $m) $nim[] = $m -> NIM;			
					$filter = 'trim(nim) IN (' . "'" . implode ( "','", $nim ) . "'" . ')';
					$mhs_terdaftar = $this -> getListMahasiswaFeeder(null, null, $filter);
					
					if(count($mhs_terdaftar) > 0)
					{
						foreach($mhs_terdaftar as $m) $id_mahasiswa[] = explode(':', $m)[0];			
						$filter = 'id_mahasiswa IN (' . "'" . implode ( "','", $id_mahasiswa ) . "'" . ')';
						$pre_terdaftar = $this -> getPrestasiMahasiswaFeeder($filter);
					}
				}
				$kode_dikti = $input['kode_dikti'];
				$angkatan = $input['angkatan'];
			}
			
			return view('feeder.prestasi', compact('prodi_select', 'angkatan_select', 'mahasiswa', 'mhs_terdaftar', 'pre_terdaftar', 'kode_dikti', 'angkatan'));
		}
		
		public function postPrestasi(Request $request)
		{
			$input = $request -> all();
			$data = [];
			$count['failed']['insert'] = [
			'msg' => 'Terdapat kesalahan pada proses Input: ',
			'desc' => []
			];
			$count['success'] = 0;
			
			if(isset($input['dt']) and count($input['dt']) > 0) 
			{
				foreach($input['dt'] as $d) 
				{
					$e = explode(':', $d);
					$id_mhs_fdr[$e[1]] = $e[0];
					$id_pre[] = $e[2];
				}
				$prestasi = MahasiswaPrestasi::leftJoin('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_id')
				-> whereIn('mahasiswa_prestasi.id', $id_pre)
				-> get(['NIM', 'mahasiswa_prestasi.*']);
				
				foreach($prestasi as $p) 
				{
					$data_insert[] = [
					'id_pd' => $id_mhs_fdr[$p -> NIM],
					'id_jns_prestasi' => $p -> jenis,
					'id_tkt_prestasi' => $p -> tingkat,
					'nm_prestasi' => $p -> nama,
					'thn_prestasi' => $p -> tahun,
					'penyelenggara' => $p -> penyelenggara,
					'peringkat' => intval($p -> peringkat)
					];
				}
				
				$token = $this -> getToken();
				$soap = $this->InsertRecord($token, 'prestasi', json_encode($data_insert));
				foreach($soap -> result as $result)
				{
					if($result -> error_code == '0')
					{
						$count['success'] ++;
					}
					elseif($result -> error_code == '800')
					{
						$count['failed']['insert']['desc'][] = 'Prestasi sudah terdaftar di FEEDER';
					}
					else
					{
						$count['failed']['insert']['desc'][] = $result -> error_desc;
					}
				}	
				
				$failed_msg = '';
				foreach($count['failed'] as $failed_type)
				{
					if(count($failed_type['desc']) > 0)
					{
						$failed_msg .= $failed_type['msg'] . '<br/><ul>';
						foreach($failed_type['desc'] as $msg)
						{
							$failed_msg .= '<li>' . $msg . '</li>';
						}
						$failed_msg .= '</ul>';
					}
				}
			}
			//with
			$success = $count['success'] > 0 ? $count['success'] . ' data berhasil diproses.' : null;
			$danger = (isset($failed_msg) and $failed_msg != '') ? $failed_msg : null;
			
			return \Redirect::to('/export/feeder/prestasi/?kode_dikti=' . $input['kode_dikti'] . '&angkatan=' .$input['angkatan']) 
			-> with('success', $success)
			-> with('danger_raw', $danger);
		}
		
		//KELULUSAN
		public function getKelulusan(Request $request)
		{
			$input = $request -> all();
			
			if(
			(isset($input['kode_dikti']) and $input['kode_dikti'] == '-') or 
			(isset($input['angkatan']) and $input['angkatan'] == '-')
			) 
			return \Redirect::back() -> with('warning', 'Prodi dan/atau Angkatan belum dipilih');
			
			$prodi_select = $this -> getProdiSelection('id', 'asc', true, 'kode_dikti');
			$angkatan_select = $this -> getGolongan('angkatan', true);
			
			$mahasiswa = $id_prodi_feeder = $kode_dikti = $angkatan = null;
			$mhs_terdaftar = $lls_terdaftar = $mhs_pt = [];
			
			if(isset($input['kode_dikti']) and isset($input['angkatan']))
			{
				$mahasiswa = Mahasiswa::leftJoin('wisuda', 'wisuda.id', '=', 'mahasiswa.wisuda_id')
				-> leftJoin('aktivitas_perkuliahan', 'mahasiswa.id', '=', 'aktivitas_perkuliahan.mahasiswa_id')
				-> join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
				-> whereIn('statusMhs', [3, 4]) 
				-> where('prodi.kode_dikti', $input['kode_dikti'])
				-> where('mahasiswa.angkatan', $input['angkatan'])
				-> orderBy('NIM', 'desc')
				-> groupBy('NIM')
				// -> havingRaw('max(aktivitas_perkuliahan.tapel_id)')
				-> get(['mahasiswa.*', 'strata', 'singkatan', 'ipk', 'tglSKYudisium']);
				
				if($mahasiswa -> count())
				{
					foreach($mahasiswa as $m) $nim[] = $m -> NIM;			
					$filter = 'trim(nim) IN (' . "'" . implode ( "','", $nim ) . "'" . ')';
					$lls_terdaftar = $this -> getKelulusanMahasiswaFeeder($filter);	
					$mhs_terdaftar = $this -> getListMahasiswaFeeder(null, null, $filter);
					
					$mhs_pt = $this -> getListMahasiswaPTSoap(str_replace('nim', 'nipd', $filter));
				}
				$id_prodi_feeder = $this -> getIdProdiFeeder($input['kode_dikti']);
				$kode_dikti = $input['kode_dikti'];
				$angkatan = $input['angkatan'];
			}
			return view('feeder.kelulusan', compact('prodi_select', 'angkatan_select', 'mahasiswa', 'mhs_terdaftar', 'mhs_pt', 'lls_terdaftar', 'id_prodi_feeder', 'kode_dikti', 'angkatan'));
		}
		
		private function getListMahasiswaPTSoap($filter)
		{
			$mhs_terdaftar = [];
			
			$token = $this -> getToken();
			$soap = $this -> getRecord($token, 'mahasiswa_pt', $filter);
			if($soap['success'])
			{
				foreach($soap['data'] as $m) $mhs_terdaftar[trim($m -> nipd)] = 
				[
				'fk_jns_keluar' => $m -> fk__jns_keluar,
				'no_ijasah' => $m -> no_seri_ijazah,
				'tgl_keluar' => $m -> tgl_keluar,
				'tgl_sk_yudisium' => $m -> tgl_sk_yudisium,
				'ipk' => $m -> ipk
				];
			}
			else
			{
				return \Redirect::to('/') -> with('warning', $soap['error_desc']); 	
			}
			return $mhs_terdaftar;
		}
		
		public function postKelulusan(Request $request)
		{
			$input = $request -> all();
			$status_mhs_to_jenis_keluar = [
			1 => "Z", 2 => 2, 3 => 3, 4 => 1, 5 => 4, 6 => 5, 
			7 => 6, 8 => 7, 9 => "Z", 10 => "Z", 11 => "Z", 12 => "Z", 
			];
			$data = $nim = $penguji = $pembimbing = $data_pembimbing = $data_penguji = $nim_insert = $akm = [];
			$count['failed']['insert'] = [
			'msg' => 'Terdapat kesalahan pada proses Input: ',
			'desc' => []
			];
			$count['failed']['update'] = [
			'msg' => 'Terdapat kesalahan pada proses Update: ',
			'desc' => []
			];
			$count['success']['mahasiswa'] = $count['success']['akm'] = $count['success']['anggota'] = 
			$count['success']['penguji'] = $count['success']['pembimbing'] = 0;
			
			foreach($input['dt'] as $d) 
			{
				$e = explode(':', $d);
				$nim[] = $e[0];
				$reg[$e[0]] = $e[2];
				
				//FLAG untuk data baru
				if($e[1] == 0) $nim_insert[] = $e[0];
			}
			$nidn = Dosen::where('NIDN', '<>', '') -> whereNotNull('NIDN') -> pluck('NIDN', 'id');
			$dosen_feeder = $this -> getListDosenFeeder();
			
			$data_mhs = Mahasiswa::leftJoin('wisuda', 'wisuda.id', '=', 'mahasiswa.wisuda_id')
			-> join('aktivitas_perkuliahan', 'mahasiswa.id', '=', 'aktivitas_perkuliahan.mahasiswa_id')
			-> leftJoin('skripsi', 'skripsi.id', '=', 'mahasiswa.skripsi_id')
			-> leftJoin('mahasiswa_jusg', 'mahasiswa.id', '=', 'mahasiswa_jusg.mahasiswa_id')
			-> leftJoin('jadwal_ujian_skripsi_gelombang', 'jadwal_ujian_skripsi_gelombang.id', '=', 'mahasiswa_jusg.jusg_id')
			-> leftJoin('jadwal_ujian_skripsi', 'jadwal_ujian_skripsi.id', '=', 'jadwal_ujian_skripsi_gelombang.jadwal_ujian_skripsi_id')
			-> leftJoin('tapel', 'tapel.id', '=', 'jadwal_ujian_skripsi.tapel_id')
			
			-> whereIn('NIM', $nim) 
			-> groupBy('NIM')
			// -> havingRaw('max(aktivitas_perkuliahan.tapel_id)')  // AKM
			-> get([
			'NIM', 'mahasiswa.nama as mahasiswa_nama', 'statusMhs', 'tglKeluar', 'noIjazah', 'ketKeluar', 
			'SKYudisium', 'tglSKYudisium', 'smt_yudisium',
			'ipk',
			'skripsi.judul', 'skripsi_id',
			'penguji_utama', 'ketua', 'sekretaris',
			'tapel.nama2 as id_semester',
			'no_surat', 'tgl_surat'
			]);
			
			foreach($data_mhs as $m) 
			{
				$sky = $tsky = $nsi = '';
				if($status_mhs_to_jenis_keluar[$m -> statusMhs] == 1)
				{
					$sky = $m -> SKYudisium;
					$tsky = isset($m -> tglSKYudisium) ? date('Y-m-d', strtotime($m -> tglSKYudisium))  : '';
					$nsi = $m -> noIjazah;
				}
				
				$data[] = [
				'key' => ['id_reg_pd' => $reg[$m -> NIM]],
				'data' => 
				[
				'id_jns_keluar' => $status_mhs_to_jenis_keluar[$m -> statusMhs],
				'tgl_keluar' => isset($m -> tglKeluar) ? date('Y-m-d', strtotime($m -> tglKeluar))  : '',
				'sk_yudisium' => $sky,
				'tgl_sk_yudisium' => $tsky,
				'ipk' => round($m -> ipk,2),
				'smt_yudisium' => $m -> smt_yudisium,
				'no_seri_ijazah' => $nsi,
				'ket' => $m -> ketKeluar
				]
				];
				
				if(in_array($m -> NIM, $nim_insert))
				{
					$akm[] = [
					'id_smt' => $m -> id_semester,
					'judul_akt_mhs' => $m -> judul,
					'id_jns_akt_mhs' => 2,  //Tugas Akhir
					'lokasi_kegiatan' => config('custom.profil.alamat.kabupaten'),
					'sk_tugas' => $m -> no_surat,
					'tgl_sk_tugas' => $m -> tgl_surat,
					'a_komunal' => 0,
					'id_sms' => $input['id_sms'] //id_prodi
					]; 
					$akm_id[] = [
					'id_reg_pd' => $reg[$m -> NIM],
					'nama' => $m -> mahasiswa_nama,
					'nim' => $m -> NIM
					];
					
					//PEMBIMBING
					$ds = DosenSkripsi::where('skripsi_id', $m -> skripsi_id) -> get(['dosen_id']);
					if($ds -> count())
					{
						$c=1;
						foreach($ds as $s)
						{
							if(isset($nidn[$s -> dosen_id]))
							{
								if(isset($dosen_feeder[$nidn[$s -> dosen_id]]))
								{
									$pembimbing[$reg[$m -> NIM]][] = [
									'id_sdm' => $dosen_feeder[$nidn[$s -> dosen_id]],
									'id_reg_pd' => $reg[$m -> NIM],
									'id_katgiat' => 110400,
									'urutan_promotor' => $c
									];
									$c++;
								}
							}
						}
					}
					
					// PENGUJI
					if($m -> penguji_utama != '')
					{
						if(isset($nidn[$m -> penguji_utama]))
						{
							if(isset($dosen_feeder[$nidn[$m -> penguji_utama]]))
							{
								$penguji[$reg[$m -> NIM]][] = [
								'id_sdm' => $dosen_feeder[$nidn[$m -> penguji_utama]],
								'id_reg_pd' => $reg[$m -> NIM],
								'id_katgiat' => 110500,
								'urutan_uji' => 1
								];
							}
						}
					}
					if($m -> ketua != '')
					{
						if(isset($nidn[$m -> ketua]))
						{
							if(isset($dosen_feeder[$nidn[$m -> ketua]]))
							{
								$penguji[$reg[$m -> NIM]][] = [
								'id_sdm' => $dosen_feeder[$nidn[$m -> ketua]],
								'id_reg_pd' => $reg[$m -> NIM],
								'id_katgiat' => 110500,
								'urutan_uji' => 2
								];
							}
						}
					}
					if($m -> sekretaris != '')
					{
						if(isset($nidn[$m -> sekretaris]))
						{
							if(isset($dosen_feeder[$nidn[$m -> sekretaris]]))
							{
								$penguji[$reg[$m -> NIM]][] = [
								'id_sdm' => $dosen_feeder[$nidn[$m -> sekretaris]],
								'id_reg_pd' => $reg[$m -> NIM],
								'id_katgiat' => 110500,
								'urutan_uji' => 3
								];
							}
						}
					}
				}
			}
			
			// UPDATE MAHASISWA
			$update_mhs_success = false;
			
			if(count($data) > 0)
			{
				$token = $this -> getToken();
				$soap = $this->UpdateRecord($token, 'mahasiswa_pt', json_encode($data));
				
				if(isset($soap -> result))
				{
					$update_mhs_success = true;
					foreach($soap -> result as $result)
					{
						if($result -> error_code == '0')
						{
							$count['success']['mahasiswa'] ++;
						}
						else
						{
							$count['failed']['update']['desc'][] = 'Update Mahasiswa ' . $result -> error_desc;
						}
					}
				}
			}
			
			if(!$update_mhs_success) return \Redirect::back() -> withErrors(['FAILED' => 'Update data Mahasiswa Gagal. ']);
			
			
			// INSERT AKM
			if(count($akm) > 0)
			{
				$token = $this -> getToken();				
				$soap = $this->InsertRecord($token, 'aktivitas_mahasiswa', json_encode($akm));
				foreach($soap -> result as $key => $result)
				{
					if($result -> error_code == '0')
					{
						//ANGGOTA AKM
						$akm_anggota[] = [
						'id_reg_pd' => $akm_id[$key]['id_reg_pd'],
						'id_akt_mhs' => $result -> id_akt_mhs,
						'nm_pd' => $akm_id[$key]['nama'],
						'nipd' => $akm_id[$key]['nim'],
						'jns_peran_mhs' => 3
						];
						
						//PEMBIMBING
						if(count($pembimbing[$akm_id[$key]['id_reg_pd']]) > 0)
						{
							foreach($pembimbing[$akm_id[$key]['id_reg_pd']] as $pb)
							{
								$data_pembimbing[] = [
								'id_akt_mhs' => $result -> id_akt_mhs,
								'id_sdm' => $pb['id_sdm'],
								'urutan_promotor' => $pb['urutan_promotor'],
								'id_katgiat' => $pb['id_katgiat']
								];
							}
						}
						
						//PENGUJI
						if(count($penguji[$akm_id[$key]['id_reg_pd']]) > 0)
						{
							foreach($penguji[$akm_id[$key]['id_reg_pd']] as $pg)
							{
								$data_penguji[] = [
								'id_akt_mhs' => $result -> id_akt_mhs,
								'id_sdm' => $pg['id_sdm'],
								'urutan_uji' => $pg['urutan_uji'],
								'id_katgiat' => $pg['id_katgiat']
								];
							}
						}
						$count['success']['akm'] ++;
					}
					else
					{
						$count['failed']['insert']['desc'][] = 'Error Input AKM ' . $result -> error_desc;
					}
				}
			} 
			
			// INSERT ANGGOTA AKM
			if(count($data) > 0 && isset($akm_anggota))
			{
				$token = $this -> getToken();
				$soap = $this->InsertRecord($token, 'anggota_aktivitas_mahasiswa', json_encode($akm_anggota));
				foreach($soap -> result as $result)
				{
					if($result -> error_code == '0')
					{
						$count['success']['anggota'] ++;
					}
					else
					{
						$count['failed']['insert']['desc'][] = 'Error Input Anggota AKM ' . $result -> error_desc;
					}
				}
			}
			
			// INSERT PEMBIMBING
			if(count($data) > 0 && isset($data_pembimbing))
			{
				$token = $this -> getToken();
				$soap = $this->InsertRecord($token, 'bimbing_mahasiswa', json_encode($data_pembimbing));
				foreach($soap -> result as $result)
				{
					if($result -> error_code == '0')
					{
						$count['success']['pembimbing'] ++;
					}
					else
					{
						$count['failed']['insert']['desc'][] = 'Error Input Dosen Pembimbing ' . $result -> error_desc;
					}
				}
			}
			
			// INSERT PENGUJI
			if(count($data) > 0 && isset($data_penguji))
			{
				$token = $this -> getToken();
				$soap = $this->InsertRecord($token, 'uji_mahasiswa', json_encode($data_penguji));
				foreach($soap -> result as $result)
				{
					if($result -> error_code == '0')
					{
						$count['success']['penguji'] ++;
					}
					else
					{
						$count['failed']['insert']['desc'][] = 'Error Input Penguji ' . $result -> error_desc;
					}
				}
			}
			
			$failed_msg = '';
			foreach($count['failed'] as $failed_type)
			{
				if(count($failed_type['desc']) > 0)
				{
					$failed_msg .= $failed_type['msg'] . '<br/><ul>';
					foreach($failed_type['desc'] as $msg)
					{
						$failed_msg .= '<li>' . $msg . '</li>';
					}
					$failed_msg .= '</ul>';
				}
			}
			
			$success_msg = '';
			foreach($count['success'] as $success_type => $jml)
			{
				if($jml > 0)
				{
					$success_msg .= $jml . ' data ' . $success_type . ' berhasil dikirim.<br/>';
				}
			}
			
			//with
			$danger = (isset($failed_msg) and $failed_msg != '') ? $failed_msg : null;
			$success = (isset($success_msg) and $success_msg != '') ? $success_msg : null;
			
			return \Redirect::to('/export/feeder/kelulusan/?kode_dikti=' . $input['kode_dikti'] . '&angkatan=' .$input['angkatan']) 
			-> with('success_raw', $success)
			-> with('danger_raw', $danger);
		}
		
		//NILAI
		public function getNilaiKelas(Request $request)
		{
			$input = $request -> all();
			
			if(
			(isset($input['tapel_id']) and $input['tapel_id'] == '-') or 
			(isset($input['prodi_id']) and $input['prodi_id'] == '-')
			) 
			return \Redirect::back() -> with('warning', 'Prodi dan/atau Tahun Akademik belum dipilih');
			
			$tapel = $this -> getTapelSelection('desc', true, 'nama2');
			$prodi = $this -> getProdiSelection('id', 'asc', true, 'kode_dikti');
			
			
			$nilai_kelas = $kls_terdaftar = $id_semester = null;
			if(isset($input['prodi_id']) and isset($input['tapel_id']))
			{
				$nilai_kelas = Nilai::nilaiKelas($input['prodi_id'], $input['tapel_id']) -> get();
				$kls_terdaftar = $this -> getListKelasKuliahFeeder($input['prodi_id'], $input['tapel_id']);
				$id_semester = $input['tapel_id'];
			}
			return view('feeder.nilaikelas', compact('tapel', 'prodi', 'nilai_kelas', 'kls_terdaftar', 'id_semester'));
		}
		public function getNilai($matkul_tapel_id, $id_kelas)
		{
			$nilai = Nilai::join('mahasiswa', 'mahasiswa.id', '=', 'nilai.mahasiswa_id')
			-> join('matkul_tapel', 'matkul_tapel.id', '=', 'nilai.matkul_tapel_id')
			-> join('tapel', 'tapel.id', '=', 'matkul_tapel.tapel_id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'matkul.id', '=', 'kurikulum_matkul.matkul_id')
			-> join('prodi', 'prodi.id', '=', 'matkul_tapel.prodi_id')
			-> where('jenis_nilai_id', 0)
			-> where('matkul_tapel.id', $matkul_tapel_id)
			-> orderBy('NIM')
			-> get([
			'NIM', 'mahasiswa.nama as nama_mahasiswa', 'angkatan',
			'matkul_tapel.id as id_mt', 
			'nama2 as semester', 
			'kode', 'matkul.nama as nama_matkul', 
			'kurikulum_matkul.semester as kelas', 'kelas2', 
			'strata', 'prodi.singkatan as nama_prodi',
			'nilai'
			]);
			
			//get ID Reg Mhs
			foreach($nilai as $n) $nim[] = $n -> NIM;			
			$filter = 'trim(nipd) IN (' . "'" . implode ( "','", $nim ) . "'" . ')';
			$token = $this -> getToken();
			$soap = $this->GetRecord($token, 'mahasiswa_pt', $filter);
			
			$mhs_terdaftar = [];			
			if(count($soap['data']) > 0)
			{
				foreach($soap['data'] as $m)
				{
					$mhs_terdaftar[$m -> nipd] = $m -> id_reg_pd;
				}
			}
			
			//Data
			$kelas = MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			
			//KRS
			$krs_terdaftar = [];
			$filter = "id_kelas = '". $id_kelas ."'";
			$krs_feeder = $this -> getKRSFeeder($filter);
			foreach($krs_feeder as $m) $krs_terdaftar[$m -> nim . '-' . $m -> kode_mata_kuliah . '-' . $m -> nama_kelas_kuliah] = $m -> id_kelas . ':' . $m -> id_registrasi_mahasiswa;
			
			return view('feeder.nilai', compact('mhs_terdaftar', 'kelas', 'krs_terdaftar', 'nilai', 'id_kelas'));
		}
		
		public function postNilai(Request $request)
		{
			$input = $request -> all();
			
			$data = [];
			$count['failed']['insert'] = [
			'msg' => 'Terdapat kesalahan pada proses Input: ',
			'desc' => []
			];
			$count['failed']['update'] = [
			'msg' => 'Terdapat kesalahan pada proses Update: ',
			'desc' => []
			];
			$count['success'] = 0;
			
			$nilai_update = $nilai_insert = [];
			
			foreach($input['dt'] as $d)
			{
				$e = explode(':', $d);
				
				if($e[5] == 'td')
				{
					$nilai_update[] = [
					'key' => [
					'id_kls' => $e[0],
					'id_reg_pd' => $e[1]
					],
					'data' => [
					'nilai_huruf' => $e[2],
					'nilai_indeks' => $e[3],
					'nilai_angka' => $e[4]
					]
					];
				}
				else
				{
					$nilai_insert[] = [
					'id_kls' => $e[0],
					'id_reg_pd' => $e[1],
					'nilai_huruf' => $e[2],
					'nilai_indeks' => $e[3],
					'nilai_angka' => $e[4]
					];	
				}
			}
			
			if(count($nilai_insert) > 0)
			{
				// insert into Krs
				$token = $this -> getToken();
				$soap = $this->InsertRecord($token, 'nilai', json_encode($nilai_insert));
				foreach($soap -> result as $result)
				{
					if($result -> error_code == '0')
					{
						$count['success'] ++;
					}
					elseif($result -> error_code == '800')
					{
						$count['failed']['insert']['desc'][] = 'KRS sudah terdaftar di FEEDER';
					}
					else
					{
						$count['failed']['insert']['desc'][] = $result -> error_desc;
					}
				}				
			}
			
			if(count($nilai_update) > 0)
			{
				// insert into Krs
				$token = $this -> getToken();
				$soap = $this->UpdateRecord($token, 'nilai', json_encode($nilai_update));
				foreach($soap -> result as $result)
				{
					if($result -> error_code == '0')
					{
						$count['success'] ++;
					}
					else
					{
						$count['failed']['update']['desc'][] = $result -> error_desc;
					}
				}				
			}
			
			$failed_msg = '';
			foreach($count['failed'] as $failed_type)
			{
				if(count($failed_type['desc']) > 0)
				{
					$failed_msg .= $failed_type['msg'] . '<br/><ul>';
					foreach($failed_type['desc'] as $msg)
					{
						$failed_msg .= '<li>' . $msg . '</li>';
					}
					$failed_msg .= '</ul>';
				}
			}
			
			//with
			$success = $count['success'] > 0 ? $count['success'] . ' data berhasil diproses.' : null;
			$danger = (isset($failed_msg) and $failed_msg != '') ? $failed_msg : null;
			
			return \Redirect::to('/export/feeder/nilai/' . $input['mt_id'] . '/' .$input['id_kelas']) 
			-> with('success', $success)
			-> with('danger_raw', $danger);
		}
		
		public function deleteNilaiV1(Request $request)
		{
			$input = $request -> all();
			
			if(isset($input['query']))
			{
				$e = explode(':', $input['query']);
				$nilai_update[] = [
				'key' => [
				'id_kls' => $e[0],
				'id_reg_pd' => $e[1]
				],
				'data' => [
				'nilai_huruf' => null,
				'nilai_indeks' => null,
				'nilai_angka' => null
				]
				];
				
				$failed = $succes = '';
				if(count($nilai_update) > 0)
				{
					$token = $this -> getToken();
					$soap = $this->UpdateRecord($token, 'nilai', json_encode($nilai_update));
					foreach($soap -> result as $result)
					{
						if($result -> error_code == '0')
						{
							$success = 'Nilai Mata Kuliah '. $e[5] .' ('. $e[4] .') untuk Mahasiswa '. $e[3] .' ('. $e[2] .') telah dihapus';
						}
						else
						{
							$failed = $result -> error_desc;
						}
					}				
				}
				
				if($success != '') return \Redirect::back() -> with('success', $success);
				
				if($failed != '') return \Redirect::back() -> with('warning', $failed);
			}
			
			return \Redirect::back() -> withErrors(['ERROR' => 'Data tidak ditemukan']);
		}
		public function getNilaiV1(Request $request)
		{			
			$input = $request -> all();
			
			if(
			(isset($input['tapel_id']) and $input['tapel_id'] == '-') or 
			(isset($input['prodi_id']) and $input['prodi_id'] == '-')or 
			(isset($input['angkatan']) and $input['angkatan'] == '-')
			) 
			return \Redirect::back() -> with('warning', 'Prodi dan/atau Angkatan dan/atau Tahun Akademik belum dipilih');
			
			$tapel = $this -> getTapelSelection('desc', true, 'nama2');
			$prodi = $this -> getProdiSelection('id', 'asc', true, 'kode_dikti');
			$angkatan = $this -> getGolongan('angkatan', true);
			$nilai = $nilai_terdaftar = $mhs_terdaftar = $kls_terdaftar = $id_prodi = $id_prodi_local = $id_semester = $id_angkatan = null;
			
			if(isset($input['prodi_id']) and isset($input['tapel_id']) and isset($input['angkatan']))
			{
				$nilai = Nilai::join('mahasiswa', 'mahasiswa.id', '=', 'nilai.mahasiswa_id')
				-> join('matkul_tapel', 'matkul_tapel.id', '=', 'nilai.matkul_tapel_id')
				-> join('tapel', 'tapel.id', '=', 'matkul_tapel.tapel_id')
				-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
				-> join('matkul', 'matkul.id', '=', 'kurikulum_matkul.matkul_id')
				-> join('prodi', 'prodi.id', '=', 'matkul_tapel.prodi_id')
				-> where('jenis_nilai_id', 0)
				-> where('tapel.nama2', $input['tapel_id'])
				-> where('prodi.kode_dikti', $input['prodi_id'])
				-> where('mahasiswa.angkatan', $input['angkatan'])
				-> orderBy('NIM')
				-> get([
				'NIM', 'mahasiswa.nama as nama_mahasiswa',
				'matkul_tapel.id as id_mt', 
				'nama2 as semester', 
				'kode', 'matkul.nama as nama_matkul', 
				'kurikulum_matkul.semester as kelas', 'kelas2', 
				'strata', 'prodi.singkatan as nama_prodi',
				'nilai'
				]);
				
				$id_prodi = $this -> getIdProdiFeeder($input['prodi_id']);	
				$id_prodi_local = $input['prodi_id'];	
				$id_semester = $input['tapel_id'];
				$id_angkatan = $input['angkatan'];
				
				$nilai_terdaftar = $this -> getNilaiFeeder($id_prodi, $input['tapel_id'], $input['angkatan']);				
				$mhs_terdaftar = $this -> getListMahasiswaFeeder($id_prodi, $input['angkatan']);
				$kls_terdaftar = $this -> getListKelasKuliahFeeder($id_prodi, $input['tapel_id']);
			}
			return view('feeder.nilaiv1', compact('tapel', 'prodi', 'angkatan', 'nilai', 'nilai_terdaftar', 'mhs_terdaftar', 'kls_terdaftar',
			'id_prodi', 'id_prodi_local', 'id_semester', 'id_angkatan'));
		}
		
		public function postNilaiV1(Request $request)
		{
			$input = $request -> all();
			$batas = 500;
			
			//batasi jumlah input
			if(count($input['dt']) > $batas) $input['dt'] = array_slice($input['dt'], 0, $batas);
			
			$data = [];
			$count['failed']['insert'] = [
			'msg' => 'Terdapat kesalahan pada proses Input: ',
			'desc' => []
			];
			$count['failed']['update'] = [
			'msg' => 'Terdapat kesalahan pada proses Update: ',
			'desc' => []
			];
			$count['success'] = 0;
			
			//KRS
			$filter = "id_prodi = '". $input['id_prodi'] ."' AND id_periode = '". $input['id_semester'] ."' AND angkatan = '". $input['angkatan'] ."'";
			$krs_feeder = $this -> getKRSFeeder($filter);
			foreach($krs_feeder as $m) $krs_terdaftar[$m -> nim . '-' . $m -> kode_mata_kuliah . '-' . $m -> nama_kelas_kuliah] = $m -> id_kelas;
			
			$nilai_update = $nilai_insert = [];		
			
			foreach($input['dt'] as $d)
			{
				$e = explode(':', $d);
				if(array_key_exists($e[5], $krs_terdaftar))
				{
					$nilai_update[] = [
					'key' => [
					'id_kls' => $e[0],
					'id_reg_pd' => $e[1]
					],
					'data' => [
					'nilai_huruf' => $e[2],
					'nilai_indeks' => $e[3],
					'nilai_angka' => $e[4]
					]
					];
				}
				else
				{
					$nilai_insert[] = [
					'id_kls' => $e[0],
					'id_reg_pd' => $e[1],
					'nilai_huruf' => $e[2],
					'nilai_indeks' => $e[3],
					'nilai_angka' => $e[4]
					];	
				}
			}
			
			if(count($nilai_insert) > 0)
			{
				// insert into Krs
				$token = $this -> getToken();
				$soap = $this->InsertRecord($token, 'nilai', json_encode($nilai_insert));
				foreach($soap -> result as $result)
				{
					if($result -> error_code == '0')
					{
						$count['success'] ++;
					}
					elseif($result -> error_code == '800')
					{
						$count['failed']['insert']['desc'][] = 'KRS sudah terdaftar di FEEDER';
					}
					else
					{
						$count['failed']['insert']['desc'][] = $result -> error_desc;
					}
				}				
			}
			
			if(count($nilai_update) > 0)
			{
				// insert into Krs
				$token = $this -> getToken();
				$soap = $this->UpdateRecord($token, 'nilai', json_encode($nilai_update));
				foreach($soap -> result as $result)
				{
					if($result -> error_code == '0')
					{
						$count['success'] ++;
					}
					else
					{
						$count['failed']['update']['desc'][] = $result -> error_desc;
					}
				}				
			}
			
			$failed_msg = '';
			foreach($count['failed'] as $failed_type)
			{
				if(count($failed_type['desc']) > 0)
				{
					$failed_msg .= $failed_type['msg'] . '<br/><ul>';
					foreach($failed_type['desc'] as $msg)
					{
						$failed_msg .= '<li>' . $msg . '</li>';
					}
					$failed_msg .= '</ul>';
				}
			}
			
			
			//with
			$success = $count['success'] > 0 ? $count['success'] . ' data berhasil diproses.' : null;
			$danger = (isset($failed_msg) and $failed_msg != '') ? $failed_msg : null;
			
			return \Redirect::to('/export/feeder/nilaiv1?tapel_id=' . $input['id_semester'] . '&prodi_id=' .$input['id_prodi_local'] .
			'&angkatan=' .$input['angkatan']) 
			-> with('success', $success)
			-> with('danger_raw', $danger);
		}
		
		// KRS
		public function getKRS(Request $request)
		{		
			$input = $request -> all();
			
			if(
			(isset($input['tapel_id']) and $input['tapel_id'] == '-') or 
			(isset($input['prodi_id']) and $input['prodi_id'] == '-')or 
			(isset($input['angkatan']) and $input['angkatan'] == '-')
			) 
			return \Redirect::back() -> with('warning', 'Prodi dan/atau Angkatan dan/atau Tahun Akademik belum dipilih');
			
			$tapel = $this -> getTapelSelection('desc', true, 'nama2');
			$prodi = $this -> getProdiSelection('id', 'asc', true, 'kode_dikti');
			$angkatan = $this -> getGolongan('angkatan', true);
			
			$krs = $id_prodi = $id_prodi_local = $id_semester = $id_angkatan = null;
			$krs_terdaftar = $mhs_terdaftar = [];
			
			if(isset($input['prodi_id']) and isset($input['tapel_id']) and isset($input['angkatan']))
			{
				$krs = KrsDetail::join('krs', 'krs.id', '=', 'krs_detail.krs_id')
				-> join('mahasiswa', 'mahasiswa.id', '=', 'krs.mahasiswa_id')
				-> join('matkul_tapel', 'matkul_tapel.id', '=', 'krs_detail.matkul_tapel_id')
				-> join('tapel', 'tapel.id', '=', 'matkul_tapel.tapel_id')
				-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
				-> join('matkul', 'matkul.id', '=', 'kurikulum_matkul.matkul_id')
				-> join('prodi', 'prodi.id', '=', 'matkul_tapel.prodi_id')
				-> where('tapel.nama2', $input['tapel_id'])
				-> where('prodi.kode_dikti', $input['prodi_id'])
				-> where('mahasiswa.angkatan', $input['angkatan'])
				-> orderBy('NIM')
				-> orderBy('kode')
				-> get([
				'matkul_tapel.id as id_mt', 
				'nama2 as semester', 
				'NIM', 'mahasiswa.nama as nama_mahasiswa',
				'kode', 'matkul.nama as nama_matkul', 'kurikulum_matkul.semester as kelas', 'kelas2', 
				'strata', 'prodi.nama as nama_prodi', 'singkatan'
				]);
				
				$id_prodi = $this -> getIdProdiFeeder($input['prodi_id']);	
				$id_prodi_local = $input['prodi_id'];	
				$id_semester = $input['tapel_id'];
				$id_angkatan = $input['angkatan'];
				
				//Daftar KRS
				$filter = "id_prodi = '". $id_prodi ."' AND id_periode = '". $input['tapel_id'] ."' AND angkatan = '". $input['angkatan'] ."'";
				$krs_feeder = $this -> getKRSFeeder($filter);
				foreach($krs_feeder as $m) $krs_terdaftar[$m -> nim . '-' . $m -> kode_mata_kuliah . '-' . $m -> nama_kelas_kuliah] = $m -> id_kelas;
				
				$mhs_terdaftar = $this -> getListMahasiswaFeeder($id_prodi, $input['angkatan']);				
			}
			return view('feeder.krs_list', compact('tapel', 'prodi', 'angkatan', 'krs', 'krs_terdaftar', 'mhs_terdaftar', 'id_prodi', 'id_prodi_local', 'id_semester', 'id_angkatan'));
		}
		public function postKRS(Request $request)
		{	
			$input = $request -> all();
			
			$data = [];
			$count['failed']['mhs'] = [
			'msg' => 'Terdapat kesalahan pada data Mahasiswa: ',
			'desc' => []
			];
			$count['failed']['kls'] = [
			'msg' => 'Terdapat kesalahan pada data Kelas Kuliah: ',
			'desc' => []
			];
			$count['failed']['ins'] = [
			'msg' => 'Terdapat kesalahan pada proses Input: ',
			'desc' => []
			];
			$count['success'] = 0;
			
			$kelas_feeder = $this -> getListKelasKuliahFeeder($input['id_prodi'], $input['id_semester']);
			$mahasiswa_feeder = $this -> getListMahasiswaFeeder($input['id_prodi']);
			
			if(isset($input['id_i']) && is_array($input['id_i']))
			{
				foreach($input['id_i'] as $i)
				{
					$reg_mhs = $id_kelas = null;
					$a = explode(':', $i);
					if(array_key_exists($a[0], $mahasiswa_feeder))
					{
						$reg_mhs = explode(':', $mahasiswa_feeder[$a[0]])[1];
					}
					else
					{
						$count['failed']['mhs']['desc'][] = 'Mahasiswa ' . $a[0] . ' belum terdaftar di FEEDER';
					}
					
					if(array_key_exists($a[1] . '-' . $a[3] . '-' . $a[4] , $kelas_feeder))
					{
						$id_kelas = explode(':', $kelas_feeder[$a[1] . '-' . $a[3] . '-' . $a[4]])[0];
					}
					else
					{
						$count['failed']['kls']['desc'][] = 'Kelas Kuliah ' . $a[1] . ' ' . $a[2] . ' ' . $a[3] . ' belum terdaftar di FEEDER';
					}
					
					if($reg_mhs != null and $id_kelas != null)
					{
						$data[] = [
						'id_kls' => $id_kelas,
						'id_reg_pd' => $reg_mhs
						];
					}
				}
				
				// INSERT
				$failed_data = [];
				if(count($data) > 0)
				{
					$token = $this -> getToken();
					$soap = $this -> InsertRecord($token, 'nilai', json_encode($data));
					
					foreach($soap -> result as $key => $result)
					{
						if($result -> error_code == '0')
						{
							$count['success'] ++;
						}
						elseif($result -> error_code == '800')
						{
							$count['failed']['ins']['desc'][] = 'KRS sudah terdaftar di FEEDER';
						}
						else
						{
							//DELETED RECORD
							$failed_data[] = "('". $data[$key]['id_kls'] ."'".",'". $data[$key]['id_reg_pd'] ."')";
							// $count['failed']['ins']['desc'][] = $result -> error_desc;
						}
					}
				}
				
				//CEK DELETED RECORD
				if(!empty($failed_data))
				{
					$cek_krs = "(p.id_kls,p.id_reg_pd) IN (". implode(',', $failed_data) .")";
					$soap = $this -> getDeletedRecord($token,'nilai',$cek_krs);	
					
					if(!empty($soap -> result))
					{
						$reinsert = [];
						foreach($soap -> result as $result)
						{
							$reinsert[] = [
							'id_kls' =>  $result -> id_kls,
							'id_reg_pd' => $result -> id_reg_pd
							];
						}
						
						//INSERT DELETED RECORDS
						if (!empty($reinsert)) 
						{							
							$soap = $this -> restoreRecord($token, 'nilai', json_encode($reinsert));
							foreach($soap -> result as $result)
							{
								if($result -> error_code == '0')
								{
									$count['success'] ++;
								}
								else
								{
									$count['failed']['ins']['desc'][] = $result -> error_desc;
								}
							}
						}
					}
				}
				
				$failed_msg = '';
				foreach($count['failed'] as $failed_type)
				{
					if(count($failed_type['desc']) > 0)
					{
						$failed_msg .= $failed_type['msg'] . '<br/><ul>';
						foreach($failed_type['desc'] as $msg)
						{
							$failed_msg .= '<li>' . $msg . '</li>';
						}
						$failed_msg .= '</ul>';
					}
				}
			}
			
			//with
			$success = $count['success'] > 0 ? $count['success'] . ' data berhasil diproses.' : null;
			$danger = (isset($failed_msg) and $failed_msg != '') ? $failed_msg : null;
			
			return \Redirect::to('/export/feeder/krs?tapel_id=' . $input['id_semester'] . '&prodi_id=' .$input['id_prodi_local'] .
			'&angkatan=' .$input['angkatan']) 
			-> with('success', $success)
			-> with('danger_raw', $danger); 
		}
		
		// Kelas Kuliah
		public function syncKelasKuliah(Request $request)
		{
			$input = $request -> all();
			
			$failed_msg = '';
			$count['failed']['pengajar'] = [
			'msg' => 'Dosen Pengajar belum mempunyai penugasan pada Tahun ajaran Kelas Kuliah: ',
			'desc' => []
			];
			$count['success'] = 0;
			
			$get = $this -> getWSData([
			'act' => 'GetDosenPengajarKelasKuliah', 
			'filter' => "id_kelas_kuliah='". $input['id_kls'] ."'"
			]); 
			if($get['success'])
			{
				if(count($get['data']) > 0)
				{
					foreach($get['data'] as $a)
					{						
						$del = $this -> getWSData([
						'act' => 'DeleteDosenPengajarKelasKuliah', 
						'key' => ["id_aktivitas_mengajar" => $a -> id_aktivitas_mengajar]
						]); 
					}					
				}
			}
			
			if($input['id_reg_dosen'] != "0")
			{
				$dosen = explode('|', $input['id_reg_dosen']);
				foreach($dosen as $ds)
				{
					$dp = [
					'id_kelas_kuliah' => $input['id_kls'],
					'id_registrasi_dosen' => $ds,
					'id_jenis_evaluasi' => 1,
					'rencana_tatap_muka' => 0,
					'sks_substansi_total' => $input['sks']
					];
					
					$ws = $this -> getWSData([
					'act' => 'InsertDosenPengajarKelasKuliah', 
					'record' => $dp
					]); // INPUT Dosen pengajar
					
					if($ws['success'])
					{
						$count['success'] ++;
					}
					else
					{
						$count['failed']['pengajar']['desc'][] = $input['kode'] . ' ' . $input['nama'];
					}
				}
			}
			else
			{
				$count['failed']['pengajar']['desc'][] = $input['kode'] . ' ' . $input['nama'];
			}
			
			foreach($count['failed'] as $failed_type)
			{
				if(count($failed_type['desc']) > 0)
				{
					$failed_msg .= $failed_type['msg'] . '<br/><ul>';
					foreach($failed_type['desc'] as $msg)
					{
						$failed_msg .= '<li>' . $msg . '</li>';
					}
					$failed_msg .= '</ul>';
				}
			}
			
			//with
			$success = $count['success'] > 0 ? $count['success'] . ' data berhasil diproses.' : null;
			$danger = (isset($failed_msg) and $failed_msg != '') ? $failed_msg : null;
			
			return \Redirect::to('/export/feeder/kelaskuliah?tapel_id=' . $input['id_semester'] . '&prodi_id=' .$input['id_prodi_local']) 
			-> with('success', $success)
			-> with('danger_raw', $danger); 
		}
		
		public function getKelasKuliah(Request $request)
		{
			$input = $request -> all();
			
			if((isset($input['tapel_id']) and $input['tapel_id'] == '-') or (isset($input['prodi_id']) and $input['prodi_id'] == '-')) 
			return \Redirect::back() -> with('warning', 'Prodi dan/atau Tahun Akademik belum dipilih');
			
			$tapel = $this -> getTapelSelection('desc', true, 'nama2');
			$prodi = $this -> getProdiSelection('id', 'asc', true, 'kode_dikti');
			$kelas = $kls_terdaftar = $penugasan_dosen = $id_prodi = $id_prodi_local = $id_semester = null;
			
			if(isset($input['prodi_id']) and isset($input['tapel_id']))
			{
				$kelas = MatkulTapel::kelasKuliahFeeder($input['tapel_id'], $input['prodi_id']) -> get();
				
				$id_prodi = $this -> getIdProdiFeeder($input['prodi_id']);	
				$id_prodi_local = $input['prodi_id'];	
				$id_semester = $input['tapel_id'];
				
				//Daftar Kelas Kuliah
				$kls_terdaftar = $this -> getListKelasKuliahFeeder($id_prodi, $input['tapel_id']);				
				$penugasan_dosen = $this -> getListPenugasanDosenFeeder(substr($input['tapel_id'],0,4));				
			}
			return view('feeder.kelaskuliah_list', compact('tapel', 'prodi', 'kelas', 'kls_terdaftar', 'penugasan_dosen', 'id_prodi', 'id_prodi_local', 'id_semester'));
		}
		
		public function postKelasKuliah(Request $request)
		{	
			$input = $request -> all();
			
			$insert = false;
			$data = [];
			$count['failed']['dosen'] = [
			'msg' => 'Dosen Pengajar Mata Kuliah gagal di-<em>entry</em> karena belum mempunyai NIDN: ',
			'desc' => []
			];
			$count['failed']['matkul'] = [
			'msg' => 'Mata Kuliah belum di entry ke FEEDER: ',
			'desc' => []
			];
			$count['failed']['insert'] = [
			'msg' => 'Terdapat kesalahan pada proses Input Kelas Kuliah, Kelas mungkin sudah terdaftar: ',
			'desc' => []
			];
			$count['failed']['pengajar'] = [
			'msg' => 'Dosen Pengajar belum mempunyai penugasan pada Tahun ajaran Kelas Kuliah: ',
			'desc' => []
			];
			$count['success'] = 0;
			
			$matkul_feeder = $this -> getMatkulFeeder($input['id_prodi']);
			
			if(isset($input['id_i']) && is_array($input['id_i']))
			{
				foreach($input['id_i'] as $i)
				{
					$a = explode(':', $i);
					if(array_key_exists($a[1], $matkul_feeder))
					{
						$data = [
						'id_prodi' => $input['id_prodi'],
						'id_semester' => $input['id_semester'],
						'id_matkul' => $matkul_feeder[$a[1]] -> id_matkul,
						'nama_kelas_kuliah' => $a[3]
						]; 
						
						$ws = $this -> getWSData(['act' => 'InsertKelasKuliah', 'record' => $data]); // INPUT kelas kuliah
						
						if($ws['success'])
						{
							$id_kelas_kuliah = $ws['data'] -> id_kelas_kuliah;
							$dosen = $a[4] != "0" ? explode('|', $a[4]) : 0;
							
							if($dosen == 0)
							{
								$count['failed']['dosen']['desc'][] = $a[2] . ' ' . $data['nama_kelas_kuliah'];
							}
							else
							{
								foreach($dosen as $ds)
								{
									$dp = [
									'id_kelas_kuliah' => $id_kelas_kuliah,
									'id_registrasi_dosen' => $ds,
									'id_jenis_evaluasi' => 1,
									'rencana_tatap_muka' => 0,
									'sks_substansi_total' => $a[5]
									];
									$ws = $this -> getWSData([
									'act' => 'InsertDosenPengajarKelasKuliah', 
									'record' => $dp
									]); // INPUT Dosen pengajar
									
									if($ws['success'])
									{
										$count['success'] ++;
									}
									else
									{
										$count['failed']['pengajar']['desc'][] = $a[2] . ' ' . $data['nama_kelas_kuliah'];
									}
								}
							}
							
						}
						else
						{
							$count['failed']['insert']['desc'][] = $a[2] . ' ' . $data['nama_kelas_kuliah'];
						}
						
					}
					else
					{
						$count['failed']['matkul']['desc'][] = $a[2] . ' ' . $a[3];
					}
				}
				
				$failed_msg = '';
				foreach($count['failed'] as $failed_type)
				{
					if(count($failed_type['desc']) > 0)
					{
						$failed_msg .= $failed_type['msg'] . '<br/><ul>';
						foreach($failed_type['desc'] as $msg)
						{
							$failed_msg .= '<li>' . $msg . '</li>';
						}
						$failed_msg .= '</ul>';
					}
				}
			}
			
			//with
			$success = $count['success'] > 0 ? $count['success'] . ' data berhasil diproses.' : null;
			$danger = (isset($failed_msg) and $failed_msg != '') ? $failed_msg : null;
			
			return \Redirect::to('/export/feeder/kelaskuliah?tapel_id=' . $input['id_semester'] . '&prodi_id=' .$input['id_prodi_local']) 
			-> with('success', $success)
			-> with('danger_raw', $danger); 
		}
		
		//MAHASISWA		
		public function syncFeeder(Request $request)
		{		
			$input = $request -> all();
			
			if(!isset($input['id_feeder'])) return Redirect::to('/') -> withErrors(['Data Mahasiswa tidak ditemukan']);
			
			$id = explode(':', $input['id_feeder']);
			$param = ['act' => 'GetBiodataMahasiswa', 'filter' => 'id_mahasiswa = \''. $id[0] .'\''];			
			$ws = $this -> getWSData($param);
			
			if($ws['success'])
			{
				$feeder_data = (array) $ws['data'][0];
				
				$local = Mahasiswa::find($input['id']);
				$local_data = [
				'nama_mahasiswa' => $local -> nama,
				'jenis_kelamin' => strtoupper($local -> jenisKelamin),
				'tempat_lahir' => $local -> tmpLahir,
				'tanggal_lahir' => toYmd($local -> tglLahir),
				'id_agama' => intval($local -> agama),
				'nik' => strPad($local -> NIK),
				'kewarganegaraan' => $local -> wargaNegara,
				'kelurahan' => $local -> kelurahan,
				'id_wilayah' => strPad($local -> id_wil, 6),
				'penerima_kps' => $local -> kps == 'Y' ? 1 : 0,
				'nama_ibu_kandung' => "".$local -> namaIbu,
				'id_kebutuhan_khusus_ayah' => 0,
				'id_kebutuhan_khusus_ibu' => 0,
				'id_kebutuhan_khusus_mahasiswa' => 0,
				
				'jalan' => $local -> jalan,
				'rt' => $local -> rt,
				'rw' => $local -> rw,
				'dusun' => $local -> dusun,
				'kode_pos' => $local -> kodePos,
				'nisn' => $local -> NISN,
				'nama_ayah' => $local -> namaAyah,
				'tanggal_lahir_ayah' => $local -> tglLahirAyah,
				'nik_ayah' => strPad($local -> NIKAyah),
				'id_pendidikan_ayah' => $local -> pendidikanAyah,
				'id_pekerjaan_ayah' => $local -> pekerjaanAyah,
				'id_penghasilan_ayah' => $local -> penghasilanAyah,
				'tanggal_lahir_ibu' => $local -> tglLahirIbu,
				'nik_ibu' => strPad($local -> NIKIbu),
				'id_pendidikan_ibu' => $local -> pendidikanIbu,
				'id_pekerjaan_ibu' => $local -> pekerjaanIbu,
				'id_penghasilan_ibu' => $local -> penghasilanIbu,
				'nama_wali' => $local -> namaWali,
				'tanggal_lahir_wali' => $local -> tglLahirWali,
				'id_pendidikan_wali' => $local -> pendidikanWali,
				'id_pekerjaan_wali' => $local -> pekerjaanWali,
				'id_penghasilan_wali' => $local -> penghasilanWali,
				'telepon' => $local -> telp,
				'handphone' => $local -> hp,
				'email' => $local -> email,
				'nomor_kps' => $local -> noKps,
				'npwp' => strPad($local -> NPWP, 15),
				'id_jenis_tinggal' => $local -> mukim,
				'id_alat_transportasi' => $local -> transportasi 
				];
				
				$new_data = [];
				$invalid_key = [];
				
				foreach($local_data as $k => $v)
				{
					if(!array_key_exists($k, $feeder_data))
					{
						$invalid_key[] = $k;
					}
					else
					{
						if(trim($v) != trim($feeder_data[$k]))
						{
							$new_data[$k] = $local_data[$k];
						}
					}
				}
				
				if(count($new_data) > 0)
				{
					$success = $failed = [];
					foreach($new_data as $k => $v)
					{
						$param = [
						'act' => 'UpdateBiodataMahasiswa', 
						'key' => ['id_mahasiswa' => $id[0]],
						'record' => [$k => $v ]
						];			
						$ws = $this -> getWSData($param);
						if($ws['success'])
						$success[] = $k;
						else
						$failed[] = $k;
					}
				}
				else
				{
					return Redirect::back() -> with('message', 'Tidak ada perubahan data.');
				}
			}
			else
			{
				return Redirect::back() -> withErrors([$ws['error_desc']]);
			}
			$failed_msg = count($failed) > 0 ? ', ' . count($failed) . ' gagal ('. implode(', ', $failed) .')' : '';
			return Redirect::back() -> with('message', 'Sinkronisasi data: ' . count($success) . ' sukses ('. implode(', ', $success) .')' . $failed_msg);
		}
		
		public function postExportFeeder(Request $request)
		{	
			$input = $request -> all();
			$insert = false;
			
			$data = $riwayat = [];
			$count['failed']['insert'] = [
			'msg' => 'Terdapat kesalahan pada proses Input: ',
			'desc' => []
			];
			
			$count_riwayat['failed']['insert'] = [
			'msg' => 'Terdapat kesalahan pada proses Input Riwayat: ',
			'desc' => []
			];
			
			$count['success'] = $count_riwayat['success'] = 0;
			
			if(isset($input['id_i']) && is_array($input['id_i']))
			{
				$insert = Mahasiswa::whereIn('id', $input['id_i']) -> get();
				foreach($insert as $m)
				{
					$str = 'bamk_' . $m -> angkatan . '_' . $m -> prodi_id . '_' . $m -> kelasMhs . '_' . $m -> jenisPembayaran;
					$biaya = Cache::get($str, function() use($str, $m){
						$bamk = \Siakad\BiayaKuliah::join('jenis_biaya', 'jenis_biaya.id', '=','setup_biaya.jenis_biaya_id')
						-> where('golongan', 1)
						-> where('angkatan', $m -> angkatan)
						-> where('prodi_id', $m -> prodi_id)
						-> where('kelas_id', $m -> kelasMhs)
						-> where('jenisPembayaran', $m -> jenisPembayaran)
						-> sum('jumlah');
						
						$biaya = intval($bamk);
						
						Cache::put($str, $biaya, 60);
						return $biaya;
					});
					
					$riwayat[$m -> id] = [
					'nim' => $m -> NIM,
					'id_jenis_daftar' => $m -> jenisPendaftaran,
					'id_periode_masuk' => $m -> tapelMasuk,
					'tanggal_daftar' => toYmd($m -> tglMasuk),
					'id_perguruan_tinggi' => $input['id_pt'],
					'id_prodi' => $input['id_prodi'],
					'id_pembiayaan' => $m -> pembiayaan_awal,
					'biaya_masuk' => $biaya
					];
					
					$data[$m -> id] = [
					'nama_mahasiswa' => $m -> nama,
					'jenis_kelamin' => strtoupper($m -> jenisKelamin),
					'tempat_lahir' => $m -> tmpLahir,
					'tanggal_lahir' => toYmd($m -> tglLahir),
					'id_agama' => intval($m -> agama),
					'nik' => $m -> NIK,
					'kewarganegaraan' => $m -> wargaNegara,
					'kelurahan' => $m -> kelurahan,
					'id_wilayah' => strPad($m -> id_wil, 6),
					'penerima_kps' => $m -> kps == 'Y' ? 1 : 0,
					'nama_ibu_kandung' => "" . $m -> namaIbu,
					'id_kebutuhan_khusus_ayah' => 0,
					'id_kebutuhan_khusus_ibu' => 0,
					'id_kebutuhan_khusus_mahasiswa' => 0,
					
					'jalan' => $m -> jalan,
					'rt' => strPad(intval($m -> rt), 2),
					'rw' => strPad(intval($m -> rw), 2),
					'dusun' => $m -> dusun,
					'kode_pos' => $m -> kodePos,
					'nisn' => $m -> NISN,
					'nama_ayah' => $m -> namaAyah,
					'tanggal_lahir_ayah' => toYmd($m -> tglLahirAyah),
					'nik_ayah' => strPad($m -> NIKAyah),
					'id_pendidikan_ayah' => $m -> pendidikanAyah,
					'id_pekerjaan_ayah' => $m -> pekerjaanAyah,
					'id_penghasilan_ayah' => $m -> penghasilanAyah,
					'tanggal_lahir_ibu' => toYmd($m -> tglLahirIbu),
					'nik_ibu' => strPad($m -> NIKIbu),
					'id_pendidikan_ibu' => $m -> pendidikanIbu,
					'id_pekerjaan_ibu' => $m -> pekerjaanIbu,
					'id_penghasilan_ibu' => $m -> penghasilanIbu,
					'nama_wali' => $m -> namaWali,
					'tanggal_lahir_wali' => toYmd($m -> tglLahirWali),
					'id_pendidikan_wali' => $m -> pendidikanWali,
					'id_pekerjaan_wali' => $m -> pekerjaanWali,
					'id_penghasilan_wali' => $m -> penghasilanWali,
					'telepon' => preg_replace("/[^0-9]/", "", $m -> telp),
					'handphone' => preg_replace("/[^0-9]/", "", $m -> hp),
					'email' => $m -> email,
					'nomor_kps' => $m -> noKps,
					'npwp' => preg_replace("/[^0-9]/", "", $m -> NPWP),
					'id_jenis_tinggal' => $m -> mukim,
					'id_alat_transportasi' => $m -> transportasi 
					];
				}
			}			
			
			// INSERT
			if(count($data) > 0)
			{
				$error_riwayat = [];
				foreach($data as $k => $v)
				{
					$param = ['act' => 'InsertBiodataMahasiswa', 'record' => $v];
					$ws = $this -> getWSData($param);
					if($ws['success'])
					{
						$count['success'] ++;
						
						//riwayat pendidikan
						$param = [
						'act' => 'InsertRiwayatPendidikanMahasiswa', 
						'record' => $riwayat[$k]
						];
						$param['record']['id_mahasiswa'] = $ws['data'] -> id_mahasiswa;
						$ws = $this -> getWSData($param);
						
						if($ws['success'])
						{
							$count['success'] ++;
						}
						else
						{
							$count['failed']['insert']['desc'][] = 'Error Input Riwayat Pendidikan pada NIM ' . $riwayat[$k]['nim'] . ': ' . $ws['error_desc'];
						}
					}
					
					// Masukkan data riwayat bagi mahasiswa yang sudah terdaftar namun belum mempunyai riwayat 
					// (tampil sebagai belum terdaftar di SIAKAD EXPORT FEEDER)
					elseif($ws['error_code'] == 200)
					{
						$error_riwayat[$k] = $riwayat[$k];
					}
					else
					$count['failed']['insert']['desc'][] = 'Error Input Data Mahasiswa pada NIM ' . $riwayat[$k]['nim'] . ': ' . $ws['error_desc'];
				}
			}
			
			if(count($error_riwayat) > 0)
			{		
				foreach($error_riwayat as $k => $r)
				{
					$mhs = $this -> getListMahasiswaFeeder(0, null, "nama_mahasiswa='". $data[$k]['nama_mahasiswa'] . "' and tanggal_lahir='" . $data[$k]['tanggal_lahir'] . "'");
					$param = [
					'act' => 'InsertRiwayatPendidikanMahasiswa', 
					'record' => $r
					];
					$param['record']['id_mahasiswa'] = explode(':', current($mhs))[0];
					$ws = $this -> getWSData($param);
				}
			}
			
			$failed_msg = '';
			foreach($count['failed'] as $failed_type)
			{
				if(count($failed_type['desc']) > 0)
				{
					$failed_msg .= $failed_type['msg'] . '<br/><ul>';
					foreach($failed_type['desc'] as $msg)
					{
						$failed_msg .= '<li>' . $msg . '</li>';
					}
					$failed_msg .= '</ul>';
				}
			}
			
			//with
			$success = $count['success'] > 0 ? $count['success'] . ' data berhasil diproses.' : null;
			$danger = (isset($failed_msg) and $failed_msg != '') ? $failed_msg : null;
			
			return \Redirect::to('/export/feeder/mahasiswa?prodi='. $input['kode_dikti'] .'&angkatan=' . $input['id_semester'])
			-> with('success', $success)
			-> with('danger_raw', $danger); 			
		}
		
		public function getExportFeeder(Request $request)
		{		
			$input = $request -> all();
			
			if((isset($input['prodi']) and $input['prodi'] == '-') or (isset($input['angkatan']) and $input['angkatan'] == '-')) 
			return \Redirect::back() -> with('warning', 'Prodi dan/atau Angkatan belum dipilih');
			
			$angkatan = $this -> getGolongan('angkatan');
			$prodi = $this -> getProdiSelection('id', 'asc', true, 'kode_dikti');
			$mahasiswa = $mhs_terdaftar = $id_pt = $id_prodi = $kode_dikti = $id_semester = null;
			
			if(isset($input['prodi']))
			{
				$mahasiswa = Mahasiswa::join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id') 
				-> where('prodi.kode_dikti', $input['prodi']) 
				-> where('angkatan', $input['angkatan']) 
				-> select('mahasiswa.id', 'NIM', 'mahasiswa.nama AS nama', 'prodi.singkatan AS prodi', 'strata')
				-> orderBy('NIM')
				-> get();
				
				$id_pt = $this -> getIdPtFeeder();
				$id_prodi = $this -> getIdProdiFeeder($input['prodi']);				
				
				//Daftar Mahasiswa
				$mhs_terdaftar = $this -> getListMahasiswaFeeder($id_prodi);			
				
				$kode_dikti = $input['prodi'];
				$id_semester = $input['angkatan'];
			}
			
			return view('mahasiswa.export.feeder_list', compact('angkatan', 'prodi', 'mahasiswa', 'mhs_terdaftar', 'id_pt', 'id_prodi', 'kode_dikti', 'id_semester'));
		}
		
		//PRIVATE FC			
		private function GetListAnggotaAktivitasMahasiswa($filter)
		{
			$ws = $this -> getWSData(['act' => 'GetListAnggotaAktivitasMahasiswa', 'filter' => $filter]);
			if($ws['success'])
			{
				return $ws['data'];
			}
			else
			{
				return \Redirect::to('/') -> with('warning', $ws['error_desc']); 	
			}
		}	
		private function GetListSkalaNilaiProdi($id_prodi_feeder)
		{
			$terdaftar = [];
			$ws = $this -> getWSData(['act' => 'GetListSkalaNilaiProdi', 'filter' => "id_prodi='" . $id_prodi_feeder . "'"]);
			if($ws['success'])
			{
				foreach($ws['data'] as $m) $terdaftar[$m -> id_prodi . '-' . trim($m -> nilai_huruf)] = $m -> id_bobot_nilai;
			}
			else
			{
				return \Redirect::to('/') -> with('warning', $ws['error_desc']); 	
			}
			return $terdaftar;
		}
		
		private function GetDetailPeriodePerkuliahan()
		{
			$terdaftar = [];
			$ws = $this -> getWSData(['act' => 'GetDetailPeriodePerkuliahan']);
			if($ws['success'])
			{
				foreach($ws['data'] as $m) $terdaftar[$m -> id_semester . '-' . $m -> id_prodi] = intval($m -> jumlah_target_mahasiswa_baru) . ':' . 
				intval($m -> jumlah_pendaftar_ikut_seleksi) . ':' .  intval($m -> jumlah_pendaftar_lulus_seleksi) . ':' .  intval($m -> jumlah_daftar_ulang) . ':' .  
				intval($m -> jumlah_mengundurkan_diri) . ':' . $m -> tanggal_awal_perkuliahan . ':' . $m -> tanggal_akhir_perkuliahan;
			}
			else
			{
				return \Redirect::to('/') -> with('warning', $ws['error_desc']); 	
			}
			return $terdaftar;
		}
		
		private function getPrestasiMahasiswaFeeder($filter)
		{
			$mhs_terdaftar = [];
			$ws = $this -> getWSData(['act' => 'GetListPrestasiMahasiswa', 'filter' => $filter]);
			if($ws['success'])
			{
				foreach($ws['data'] as $m) $mhs_terdaftar[$m -> id_prestasi] = str_slug($m -> nama_prestasi) . '-' . 
				str_slug($m -> tahun_prestasi) . '-' . str_slug($m -> penyelenggara);
			}
			else
			{
				return \Redirect::to('/') -> with('warning', $ws['error_desc']); 	
			}
			return $mhs_terdaftar;
		}
		private function getKelulusanMahasiswaFeeder($filter)
		{
			$mhs_terdaftar = [];
			$ws = $this -> getWSData(['act' => 'GetListMahasiswaLulusDO', 'filter' => $filter]);
			// dd($ws);
			if($ws['success'])
			{
				foreach($ws['data'] as $m) if($m -> id_jenis_keluar != '') $mhs_terdaftar[trim($m -> nim)] = $m -> id_jenis_keluar;
			}
			else
			{
				return \Redirect::to('/') -> with('warning', $ws['error_desc']); 	
			}
			return $mhs_terdaftar;
		}
		
		private function getNilaiFeeder($id_prodi_feeder, $id_semester, $angkatan)
		{
			$nilai_terdaftar = [];
			$ws = $this -> getWSData([
			'act' => 'GetDetailNilaiPerkuliahanKelas',
			'filter' => "id_prodi = '". $id_prodi_feeder ."' AND id_semester = '". $id_semester ."' AND angkatan = '". $angkatan ."'"
			]);
			
			if($ws['success'])
			{
				foreach($ws['data'] as $m) $nilai_terdaftar[$m -> nim . '-' . $m -> kode_mata_kuliah . '-' . $m -> nama_kelas_kuliah] = $m -> id_kelas_kuliah . ':' . $m -> id_registrasi_mahasiswa . ':' . $m -> nilai_huruf  . ':' . $m -> nilai_indeks;
			}
			else
			{
				return \Redirect::to('/') -> with('warning', $ws['error_desc']); 	
			}
			return $nilai_terdaftar;
		}
		private function getKRSFeeder($filter)
		{
			$ws = $this -> getWSData([
			'act' => 'GetKRSMahasiswa',
			'filter' => $filter
			]);
			
			if($ws['success'])
			{
				return $ws['data'];
			}
			else
			{
				return \Redirect::to('/') -> with('warning', $ws['error_desc']); 	
			}
		}
		private function getListKelasKuliahFeeder($id_prodi_feeder, $id_semester, $sql_filter='')
		{
			$kls_terdaftar = $filter = [];
			
			if($id_prodi_feeder != '' and $id_semester != '')
			$filter = ['filter' => "id_prodi = '". $id_prodi_feeder ."' AND id_semester = '". $id_semester ."'"];	
			elseif($sql_filter != '')
			$filter = ['filter' => $sql_filter];	
			
			if(!count($filter)) return [];
			
			$ws = $this -> getWSData([
			'act' => 'GetListKelasKuliah',
			$filter
			]);
			
			if($ws['success'])
			{
				foreach($ws['data'] as $m) $kls_terdaftar[
				$m -> kode_mata_kuliah . '-' . 
				$m -> nama_kelas_kuliah . '-' . 
				$m -> id_semester] = $m -> id_kelas_kuliah . ':' . $m -> nama_dosen;
			}
			else
			{
				return \Redirect::to('/') -> with('warning', $ws['error_desc']); 	
			}
			return $kls_terdaftar;
		}
		private function getListPenugasanDosenFeeder($id_tahun_ajaran)
		{
			$dsn_terdaftar = [];
			$ws = $this -> getWSData([
			'act' => 'GetListPenugasanDosen', 
			'id_tahun_ajaran' => $id_tahun_ajaran
			]);
			if($ws['success'])
			{
				foreach($ws['data'] as $m) $dsn_terdaftar[$m -> nidn] = $m -> id_registrasi_dosen;
			}
			else
			{
				return \Redirect::to('/') -> with('warning', $ws['error_desc']); 	
			}
			return $dsn_terdaftar;
		}
		private function getListDosenFeeder()
		{
			$dsn_terdaftar = [];
			$ws = $this -> getWSData(['act' => 'GetListDosen', 'id_status_aktif' => 1]);
			if($ws['success'])
			{
				foreach($ws['data'] as $m) $dsn_terdaftar[$m -> nidn] = $m -> id_dosen;
			}
			else
			{
				return \Redirect::to('/') -> with('warning', $ws['error_desc']); 	
			}
			return $dsn_terdaftar;
		}
		private function getListMahasiswaFeeder($id_prodi_feeder, $angkatan=null, $filter=null)
		{
			$mhs_terdaftar = [];
			
			$filter_angkatan = $angkatan == null ? '' : " AND id_periode LIKE '". $angkatan ."%'";
			
			if($filter == null) $filter = "id_prodi = '". $id_prodi_feeder ."'" . $filter_angkatan;
			
			// dd($filter);
			$ws = $this -> getWSData(['act' => 'GetListMahasiswa', 'filter' => $filter]);
			// dd($ws);
			if($ws['success'])
			{
				foreach($ws['data'] as $m) $mhs_terdaftar[$m -> nim] = $m -> id_mahasiswa . ':' . $m -> id_registrasi_mahasiswa;
			}
			else
			{
				return \Redirect::to('/') -> with('warning', $ws['error_desc']); 	
			}
			return $mhs_terdaftar;
		}
		private function getMatkulFeeder($id_matkul_feeder)
		{
			$matkul_feeder = null;
			$ws = $this -> getWSData(['act' => 'GetListMataKuliah']);
			if($ws['success'])
			{
				foreach($ws['data'] as $p) $matkul_feeder[trim($p -> kode_mata_kuliah)] = $p;
			}
			else
			{
				return \Redirect::to('/') -> with('warning', $ws['error_desc']); 	
			}
			return $matkul_feeder;
		}
		
		private function getIdProdiFeeder($id_prodi_local)
		{
			// ID Prodi FEEDER
			$prodi_feeder = null;
			if(Cache::has('prodi_feeder'))
			{
				$prodi_feeder = Cache::get('prodi_feeder');
			}
			else
			{
				$ws = $this -> getWSData(['act' => 'GetProdi']);
				if($ws['success'])
				{
					foreach($ws['data'] as $p) $prodi_feeder[trim($p -> kode_program_studi)] = $p -> id_prodi;
					Cache::put('prodi_feeder', $prodi_feeder, 60);
				}
				else
				{
					return \Redirect::to('/') -> with('warning', $ws['error_desc']); 	
				}
			}
			return $prodi_feeder[$id_prodi_local];
		}
		
		private function getIdPtFeeder()
		{
			// ID Perguruan Tinggi FEEDER
			$id_pt = null;
			if(Cache::has('id_pt_feeder'))
			{
				$id_pt = Cache::get('id_pt_feeder');
			}
			else
			{
				$ws = $this -> getWSData(['act' => 'GetProfilPT']);
				if($ws['success'])
				{
					$id_pt = $ws['data'][0] -> id_perguruan_tinggi;
					Cache::put('id_pt_feeder', $ws['data'][0] -> id_perguruan_tinggi, 60);
				}
				else
				{
					return \Redirect::to('/') -> with('warning', $ws['error_desc']); 	
				}
			}
			return $id_pt;
		}
	}																																																																																																																																																																																																																																																																																																																																																																																																																																																										