<?php
	
	namespace Siakad\Imports;
	
	use Redirect;
	
	use Siakad\User;
	use Siakad\Mahasiswa;
	use Siakad\SenayanMembership;
	
	use Illuminate\Support\Collection;
	use Illuminate\Support\Facades\Hash;
	
	use Maatwebsite\Excel\Concerns\ToCollection;
	use Maatwebsite\Excel\Concerns\WithHeadingRow;
	use Maatwebsite\Excel\Concerns\WithChunkReading;
	
	class MahasiswaImport implements ToCollection, WithHeadingRow, WithChunkReading
	{
		protected $prodi_id;
		protected $password;
		protected $success;
		protected $failed;
		protected $registered;
		
		public function __construct(int $prodi_id)
		{
			$this -> prodi_id = $prodi_id;
		}
		
		public function chunkSize(): int
		{
			return 50;
		}
		
		public function collection(Collection $rows)
		{
			$this -> password = str_random(6);
			$this -> registered = $this -> success = $this -> failed = 0;
			
			foreach ($rows as $row) 
			{
				if($row['nim'] == '') continue;
				
				$data = [
				'NIM' => '' . $row['nim'],
				'angkatan' => substr('' . $row['nim'], 0, 4),
				'prodi_id' => $this -> prodi_id,
				'nama' => $row['nama'],
				'tmpLahir' => $row['tempat_lahir'],
				'tglLahir' => $row['tanggal_lahir'] == '-' ? NULL : date('d-m-Y', strtotime($row['tanggal_lahir'])),
				'jenisKelamin' => strtoupper($row['jenis_kelamin']),
				'NIK' => $row['nik'],
				'agama' => intval($row['agama']),
				'NISN' => $row['nisn'],
				'jalurMasuk' => intval($row['id_jalur_masuk']),
				'NPWP' => $row['npwp'],
				'wargaNegara' => $row['kewarganegaraan'],
				'jenisPendaftaran' => intval($row['jenis_pendaftaran']),
				'tglMasuk' => $row['tgl_masuk_kuliah'] == '-' ? NULL : date('d-m-Y', strtotime($row['tgl_masuk_kuliah'])),
				'tapelMasuk' => intval($row['mulai_semester']),
				'jalan' => $row['jalan'],
				'rt' => $row['rt'],
				'rw' => $row['rw'],
				'dusun' => isset($row['dusun_lingkungan']) ? $row['dusun_lingkungan'] : $row['nama_dusun'],
				'kelurahan' => isset($row['desa_kelurahan']) ? $row['desa_kelurahan'] : $row['kelurahan'],
				'id_wil' => $row['kecamatan'],
				'kodePos' => '' . $row['kode_pos'],
				'mukim' => intval($row['jenis_tinggal']) == 0 ? 99 : intval($row['jenis_tinggal']),
				'transportasi' => intval($row['alat_transportasi']) == 0 ? 99 : intval($row['alat_transportasi']),
				'telp' => $row['telp_rumah'],
				'hp' => $row['no_hp'],
				'email' => $row['email'],
				'kps' => strtoupper($row['terima_kps']),
				'noKps' => $row['no_kps'],
				
				'NIKAyah' => $row['nik_ayah'],
				'namaAyah' => $row['nama_ayah'],
				'tglLahirAyah' => $row['tgl_lahir_ayah'],
				'pendidikanAyah' => intval($row['pendidikan_ayah']) == 0 ? 99 : intval($row['pendidikan_ayah']),
				'pekerjaanAyah' => intval($row['pekerjaan_ayah']) == 0 ? 99 : intval($row['pekerjaan_ayah']),
				'penghasilanAyah' => intval($row['penghasilan_ayah']) == 0 ? 1 : intval($row['penghasilan_ayah']),
				
				'NIKIbu' => $row['nik_ibu'],
				'namaIbu' => '' . $row['nama_ibu'],
				'tglLahirIbu' => $row['tanggal_lahir_ibu'],
				'pendidikanIbu' => intval($row['pendidikan_ibu']) == 0 ? 99 : intval($row['pendidikan_ibu']),
				'pekerjaanIbu' => intval($row['pekerjaan_ibu']) == 0 ? 99 : intval($row['pekerjaan_ibu']),
				'penghasilanIbu' => intval($row['penghasilan_ibu']) == 0 ? 1 : intval($row['penghasilan_ibu']),
				
				'namaWali' => $row['nama_wali'],
				'tglLahirWali' => $row['tanggal_lahir_wali'],
				'pendidikanWali' => intval($row['pendidikan_wali']) == 0 ? 99 : intval($row['pendidikan_wali']),
				'pekerjaanWali' => intval($row['pekerjaan_wali']) == 0 ? 99 : intval($row['pekerjaan_wali']),
				'penghasilanWali' => intval($row['penghasilan_wali']) == 0 ? 1 : intval($row['penghasilan_wali'])
				];
				
				if(Mahasiswa::where('NIM', $data['NIM']) -> exists())
				{
					$this -> failed ++;
					$this -> registered ++;
				}
				else
				{
					$result = Mahasiswa::create($data);
					if($result)
					{
						if(!User::where('username', $data['NIM']) -> exists())
						{
							//USER
							$authinfo['username'] = $data['NIM'];
							$authinfo['password'] = Hash::make($this -> password);
							$authinfo['role_id'] = 512;
							$authinfo['authable_id'] = $result -> id;
							$authinfo['authable_type'] = 'Siakad\Mahasiswa';
							User::create($authinfo);
						}
						
						$connection_to_senayan = true;
						try {
							\DB::connection('senayan') -> getPdo();
							} catch (\Exception $e) {
							$connection_to_senayan = false;
						}
						
						if($connection_to_senayan)
						{
							if(!SenayanMembership::where('member_id', $data['NIM']) -> exists())
							{
								//SENAYAN
								$gender = isset($data['jenisKelamin']) AND $data['jenisKelamin'] == 'L' ? 1 : 0;
								$today = date('Y-m-d');
								
								if($data['tglLahir'] != NULL)
								{
									$tmp = explode('-', $data['tglLahir']);
									if(!$tmp) $birth_date = '';
									else $birth_date = $tmp[2] . '-' . $tmp[1] . '-' . $tmp[0];
								}
								else
								{
									$birth_date = '';
								}
								$member = [
								'member_id' => $authinfo['username'],
								'member_name' => $data['nama'],
								'gender' => $gender,
								'birth_date' => $birth_date,
								'member_type_id' => 1,
								
								'member_phone' => $data['hp'],
								'member_since_date' => $today,
								'register_date' => $today,
								'input_date' => $today,
								'last_update' => $today,
								'expire_date' => date('Y-m-d', strtotime("+4 years")),
								'mpasswd' => md5($this -> password)
								];
								SenayanMembership::create($member);
							}
						}
						$this -> success ++;
					}
					else
					{
						$this -> failed ++;							
					}
				}
			}
		}
		
		public function getSuccess()
		{
			return $this -> success;	
		}
		public function getFailed()
		{
			return $this -> failed;	
		}
		public function getRegistered()
		{
			return $this -> registered;	
		}
		public function getPassword()
		{
			return $this -> password;	
		}
	}
