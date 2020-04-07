<?php
	
	namespace Siakad\Http\Controllers;
	
	
	use Redirect;
	
	use Siakad\Tapel;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	class TapelController extends Controller
	{		
		use \Siakad\SkalaTrait;
		
		protected $rules = [
		'batasRegistrasi' => ['required', 'date', 'date_format:Y-m-d'],
		'mulai' => ['required', 'date', 'date_format:Y-m-d'],
		'selesai' => ['required', 'date', 'date_format:Y-m-d', 'after:mulai'],
		'mulaiKrs' => ['required', 'date', 'date_format:Y-m-d'],
		'selesaiKrs' => ['required','date', 'date_format:Y-m-d', 'after:mulaiKrs']
		];
		
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$tapel = Tapel::orderBy('nama2', 'desc')->get();
			$auth = \Auth::user();
			if($auth -> role -> name == 'Prodi') return view('tapel.index2', compact('tapel'));
			return view('tapel.index', compact('tapel'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			return view('tapel.create');
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{			
			$this -> validate($request, $this -> rules);
			$input = $request -> all();
			// if(isset($input['aktif']) && $input['aktif'] == 'y') Tapel::where(['aktif' => 'y'])->update(['aktif' => 'n']);
			$input['nama'] = $input['tahun'] . '/' . intval($input['tahun'] + 1) . ' ' . $input['semester'];
			
			$input['nama2'] = $input['tahun'];
			$input['nama2'] .= $input['semester'] == 'Ganjil' ? 1 : 2;
			
			if(Tapel::where('nama2', $input['nama2']) -> exists()) 
			return Redirect::route('tapel.index') -> with('danger', 'Tahun akademik sudah terdaftar.');
			
			unset($input['tahun']);
			unset($input['semester']);
			Tapel::create($input);
			return Redirect::route('tapel.index') -> with('message', 'Data berhasil dimasukkan');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param Tapel $tapel
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$tapel = Tapel::find($id);
			$nama = explode(' ', $tapel -> nama);
			
			$tapel -> tahun = explode('/', $nama[0])[0];
			$tapel -> semester = $nama[1];
			return view('tapel.edit', compact('tapel'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @param  int  Tapel $tapel
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $id)
		{
			// $this -> validate($request, $this->rules);
			$tapel = Tapel::find($id);
			
			$input = $request -> except(['_method']);
			$rollback = $msg = '';
			
			//cek aktifasi TA yg lalu
			if(isset($input['aktif']) && $input['aktif'] == 'y') 
			{
				$aktif = Tapel::where('aktif', 'y') -> first();
				if($aktif)
				{
					if($aktif -> id != $id)
					{
						if($aktif !== null and $aktif -> nama2 > $tapel -> nama2) return Redirect::route('tapel.index') -> with('warning', 'Tahun akademik yang sudah terlewati tidak dapat diaktifkan kembali.');
						
						//set status
						$aktif -> update(['aktif' => 'n']);
						$rollback .= 'UPDATE `tapel` SET `aktif` = "n";' . PHP_EOL;
						$rollback .= 'UPDATE `tapel` SET `aktif` = "y" WHERE `id` = ' . $aktif -> id . ';' . PHP_EOL;
						
						// insert status MHS to aktifitas_perkuliahan
						// Get angkatan with statusMhs = 1 or 9 @ prodi
						$prodi = \Siakad\Prodi::all();
						foreach($prodi as $p)
						{
							$angkatan[$p -> id] = \Siakad\Mahasiswa::where('prodi_id', $p -> id) 
							-> where(function($w)
							{
								$w 
								-> where('statusMhs', 1)
								-> orWhere('statusMhs', 9);
							}) 
							-> select('angkatan') 
							-> distinct() 
							-> pluck('angkatan');
						}
						
						foreach($angkatan as $prodi => $a)
						{
							$mahasiswa = \Siakad\Mahasiswa::where('prodi_id', $prodi) -> whereIn('angkatan', $a) -> get();
							foreach($mahasiswa as $m)
							{
								$skala = $this -> skala($m -> prodi_id);
								$data = \Siakad\Nilai::dataKHS($m -> NIM) -> get();
								
								$jsks = $ip = $ipk = $jsksn = $disc = 0;
								$cursks = $cursksn = 0;
								
								// if(count($data) >= 1)
								// if($data -> count())
								// {
								foreach($data as $d) 
								{
									if($d -> nama2 == $aktif -> nama2)
									{
										$cursks += $d -> sks;
										$cursksn += array_key_exists($d -> nilai, $skala) ? $skala[$d -> nilai]['angka'] * $d -> sks : 0; 
										$ip = $cursks < 1 ? 0 : number_format($cursksn / $cursks, 2);
									}
									
									$jsks += $d -> sks;
									$jsksn += array_key_exists($d -> nilai, $skala) ? $skala[$d -> nilai]['angka'] * $d -> sks : 0;
									
									if(!array_key_exists($d -> nilai, $skala)) $disc += $d -> sks;
								}
								
								$div = ($jsks - $disc) < 1 ? $jsks : ($jsks - $disc);
								$ipk = $jsks < 1 ? 0 : number_format($jsksn / $div, 2);
								
								if($jsks > 0)
								{
									$upd_mhs_ids[] = $m -> id;
									
									//Semester Lalu
									$upd_mhs_data[] = 
									[
									'tapel_id' => $aktif -> id,
									'status' => $m -> statusMhs,
									'mahasiswa_id' => $m -> id,
									'jsks' => $cursks,
									'total_sks' => $jsks,
									'jsksn' => $jsksn,
									'ips' => $ip,
									'ipk' => $ipk,
									'semester' => $m -> semesterMhs
									];
								}
								
								//Semester Skrg
								$status = $m -> statusMhs == 1 ? 9 : $m -> statusMhs;
								
								//naikkan semester HANYA utk MHS yg berstatus AKTIF								
								$new_semester = $m -> statusMhs == 1 ? $m -> semesterMhs + 1 : $m -> semesterMhs;
								
								$upd_mhs_data[] = 
								[
								'tapel_id' => $tapel -> id,
								'status' => $status,
								'mahasiswa_id' => $m -> id,
								'jsks' => $cursks,
								'total_sks' => $jsks,
								'jsksn' => $jsksn,
								'ips' => 0,
								'ipk' => $ipk,
								'semester' => $new_semester
								];
								// }
							}
						}
						
						\Siakad\Aktivitas::where('tapel_id', $aktif -> id) -> whereIn('mahasiswa_id', $upd_mhs_ids) -> delete();
						// $rollback .= 'DELETE FROM `aktivitas_perkuliahan` WHERE `tapel_id` = ' . $aktif -> id . ';';
						
						\Siakad\Aktivitas::where('tapel_id', $tapel -> id) -> whereIn('mahasiswa_id', $upd_mhs_ids) -> delete();
						$rollback .= 'DELETE FROM `aktivitas_perkuliahan` WHERE `tapel_id` = ' . $tapel -> id . ';' . PHP_EOL;
						
						\Siakad\Aktivitas::insert($upd_mhs_data);
						
						// set status Mahasiswa Aktif -> non-aktif
						$mhs_aktif_array = [];
						$mhs_aktif = \Siakad\Mahasiswa::where('statusMhs', 1);
						
						foreach($mhs_aktif -> get() as $ma) $mhs_aktif_array[] = $ma -> id;
						if(count($mhs_aktif_array) > 0) $rollback .= 'UPDATE `mahasiswa` SET `statusMhs` = 1 WHERE `mahasiswa`.`id` IN (' . implode(', ', $mhs_aktif_array) . ');' . PHP_EOL;
						
						$mhs_aktif -> update(['statusMhs' => 9]);
						
						// update semesterMhs
						$smt_mhs_array = [];
						$smt_mhs = \Siakad\Mahasiswa::whereIn('statusMhs', [1, 9, 10, 11, 12]);
						
						foreach($smt_mhs -> get() as $ma) $smt_mhs_array[] = $ma -> id;
						if(count($smt_mhs_array) > 0) $rollback .= 'UPDATE `mahasiswa` SET `semesterMhs` = `semesterMhs` - 1 WHERE `mahasiswa`.`id` IN (' . implode(', ', $smt_mhs_array) . ');' . PHP_EOL;
						
						$smt_mhs -> increment('semesterMhs');
						
						//penugasan dosen
						$penugasan = \Siakad\DosenPenugasan::SKTerakhir() -> get();
						foreach($penugasan as $t)
						{
							$tmp[] = [
							'dosen_id' => $t -> dosen_id,
							'tapel_id' => $id,
							'prodi_id' => $t -> prodi_id,
							'no_surat_tugas' => $t -> no_surat_tugas,
							'tgl_surat_tugas' => $t -> tgl_surat_tugas,
							'tmt_surat_tugas' => $t -> tmt_surat_tugas,
							'berlaku_sampai' => $t -> berlaku_sampai,
							'homebase' => $t -> homebase,
							'keterangan' => $t -> keterangan,
							];
						}
						$rollback .= 'DELETE FROM `dosen_penugasan` WHERE `tapel_id` = ' . $id . ';' . PHP_EOL;
						\Siakad\DosenPenugasan::insert($tmp);
						
						//Rollback
						$file = 'TA_'. $tapel -> nama2 .'_rollback_to_'. $aktif -> nama2 .'_' . date('YmdHis') . '.sql';
						$storage = \Storage::disk('backups');
						$result = $storage -> put($file, $rollback);
						if($result)
						{
							if(\Siakad\Backup::insert(['date' => date('Y-m-d H:i:s'), 'user' => \Auth::user() -> id, 'file' => $file]))
							$msg = 'File Backup ('. $file .') berhasil dibuat.';
						}
					}
				}
			}
			
			$tapel -> update($input);
			
		return Redirect::route('tapel.index', $tapel->id) -> with('message', 'Data berhasil diperbarui. ' . $msg);
		}
		
		/**
		* Remove the specified resource from storage.
		*
		* @param  int  Tapel $tapel
		* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
		Tapel::find($id) -> delete();
		}
		}
				