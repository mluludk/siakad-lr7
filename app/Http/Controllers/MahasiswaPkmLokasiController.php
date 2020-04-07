<?php
	
	namespace Siakad\Http\Controllers;
	
	use Cache;
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Pkm;
	use Siakad\Nilai;
	use Siakad\PkmLokasi;
	use Siakad\PkmLokasiDosen;
	use Siakad\PkmLokasiMatkul;
	use Siakad\Mahasiswa;
	use Siakad\MahasiswaPkmLokasi;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class MahasiswaPkmLokasiController extends Controller
	{
		use \Siakad\SkalaTrait;
		
		public function status($mahasiswa_id)
		{
			$status = MahasiswaPkmLokasi::status($mahasiswa_id) -> first();
			$mahasiswa = Mahasiswa::find($mahasiswa_id);
			$auth = \Auth::user();
			
			return view('mahasiswa.pkm.status', compact('mahasiswa', 'status', 'auth'));
		}
		
		public function index($id, $print=null)
		{
			$user = \Auth::user();
			
			$pkm = Pkm::find($id);
			
			$peserta = MahasiswaPkmLokasi::peserta($id) -> get();
			$tbl = [];
			foreach($peserta as $p)
			{
				$tbl[$p -> pkm_lokasi_id][$p -> prodi_id][] = $p;
			}
			$peserta = $tbl;
			
			$pendamping = PkmLokasiDosen::pendampingPkm($id) -> get();
			
			$pd = [];
			foreach($pendamping as $p)
			{
				$pd[$p -> pkm_lokasi_id][$p -> dosen_id] = $p -> gelar_depan . ' ' . $p -> nama . ' ' . $p -> gelar_belakang;
			}
			$pendamping = $pd;
			
			if($print == 'print')
			return view('mahasiswa.pkm.lokasi.peserta.print', compact('pkm', 'peserta', 'pendamping', 'user'));
			else
			return view('mahasiswa.pkm.lokasi.peserta.index', compact('pkm', 'peserta', 'pendamping', 'user'));
		}
		
		public function nilai($pkm_id, $pkm_lokasi_id, $matkul_id)
		{
			$pkm = Pkm::find($pkm_id);
			$lokasi_matkul = PkmLokasiMatkul::with('prodi', 'lokasi', 'mk') 
			-> where('pkm_lokasi_id', $pkm_lokasi_id) 
			-> where('matkul_id', $matkul_id) 
			-> first();
			// dd($lokasi_matkul -> prodi);
			
			$peserta = PkmLokasiMatkul::pesertaMatkul($pkm_lokasi_id, $matkul_id) -> get();
			
			$pendamping = PkmLokasiDosen::pendampingPkm($pkm_id) -> get();
			
			$pd = [];
			foreach($pendamping as $p)
			{
				$pd[$p -> pkm_lokasi_id][$p -> dosen_id] = $p -> gelar_depan . ' ' . $p -> nama . ' ' . $p -> gelar_belakang;
			}
			$pendamping = $pd;
			
			$skala_o = $this -> skala($lokasi_matkul -> prodi -> id);
		
			$tmp['-'] = '-';
			foreach($skala_o as $h => $v) $tmp[$h] = $h;
			$skala = $tmp;
			
			return view('mahasiswa.pkm.lokasi.peserta.nilai', compact('pkm', 'skala_o', 'skala', 'lokasi_matkul', 'pendamping', 'peserta'));
		}
		
		public function storeNilai(Request $request, $pkm_id, $pkm_lokasi_id, $matkul_id)
		{
			$input = $request -> except('_token');
			$nilai = explode('|', $input['nilai']);
			
			$return = ['success' => 0, 'message' => 'Unknown Error.'];
			$matkul_tapel = Pkm::getMatkulTapelId($matkul_id, $input['mahasiswa_id']) -> first();
			
			if(!$matkul_tapel)
			{
				return ['success' => 0, 'message' => 'Mahasiswa belum melakukan KRS pada Mata Kuliah PKM.'];
			}
			
			//MahasiswaPkmLokasi
			$nilai_pkm = MahasiswaPkmLokasi::where('mahasiswa_id', $input['mahasiswa_id'])
			-> where('pkm_lokasi_id', $pkm_lokasi_id);
			
			if(!$nilai_pkm -> exists())
			{
				$return = ['success' => 0, 'message' => 'Mahasiswa belum mendaftar PKM.'];
			}
			else
			{
				if($nilai_pkm -> update(['nilai' => $nilai[0], 'nilai_angka' => $nilai[1]]))
				{
					if(
					Nilai::where('mahasiswa_id', $input['mahasiswa_id'])
					-> where('matkul_tapel_id', $matkul_tapel -> id)
					-> where('jenis_nilai_id', 0)
					-> exists()
					)
					{
						if(
						Nilai::where('mahasiswa_id', $input['mahasiswa_id'])
						-> where('matkul_tapel_id', $matkul_tapel -> id)
						-> where('jenis_nilai_id', 0)
						-> update(['nilai' => $nilai[0]])
						)
						{
							$return = ['success' => 1, 'message' => 'Nilai PKM Mahasiswa '. $input['mahasiswa_nim'] .' telah diperbarui.'];
						}
						else
						{
							$return = ['success' => 0, 'message' => 'Transfer Nilai PKM gagal.'];
						}
					}
					else
					{
						if(
						Nilai::create([
						'mahasiswa_id' => $input['mahasiswa_id'],
						'matkul_tapel_id' => $matkul_tapel -> id,
						'jenis_nilai_id' => 0,
						'nilai' => $nilai[0]
						])
						)
						{
							$return = ['success' => 1, 'message' => 'Nilai PKM Mahasiswa '. $input['mahasiswa_nim'] .' telah disimpan.'];
						}
						else
						{
							$return = ['success' => 0, 'message' => 'Data Nilai tidak ditemukan.'];
						}
					}
					
					// NILAI 1					
					if(
					Nilai::where('mahasiswa_id', $input['mahasiswa_id'])
					-> where('matkul_tapel_id', $matkul_tapel -> id)
					-> where('jenis_nilai_id', 1)
					-> exists()
					)
					{
						if(
						Nilai::where('mahasiswa_id', $input['mahasiswa_id'])
						-> where('matkul_tapel_id', $matkul_tapel -> id)
						-> where('jenis_nilai_id', 1)
						-> update(['nilai' => $nilai[1]])
						)
						{
							$return = ['success' => 1, 'message' => 'Nilai PKM Mahasiswa '. $input['mahasiswa_nim'] .' telah diperbarui.'];
						}
						else
						{
							$return = ['success' => 0, 'message' => 'Transfer Nilai PKM gagal.'];
						}
					}
					else
					{
						if(
						Nilai::create([
						'mahasiswa_id' => $input['mahasiswa_id'],
						'matkul_tapel_id' => $matkul_tapel -> id,
						'jenis_nilai_id' => 1,
						'nilai' => $nilai[1]
						])
						)
						{
							$return = ['success' => 1, 'message' => 'Nilai PKM Mahasiswa '. $input['mahasiswa_nim'] .' telah disimpan.'];
						}
						else
						{
							$return = ['success' => 0, 'message' => 'Data Nilai tidak ditemukan.'];
						}
					}
				}
				else
				{
					$return = ['success' => 0, 'message' => 'Gagal menyimpan Nilai PKM.'];
				}
			}
			
			return $return;
			
			// if($return['success'] == 1) return Redirect::to('/pkm/' . $pkm_id . '/peserta') -> with('success', $return['message']);
			
			// return Redirect::back() -> with('warning', $return['message']);
		}
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$show = $admin = $lokasi = false;
			$data = \Auth::user() -> authable;
			if($data == null or !$data) return Redirect::back() -> with('warning', 'Terjadi kesalahan pada Data Mahasiswa. Silahkan menghubungi Bagian Akademik.');
			
			if($data -> statusMhs != 1) return Redirect::to('home') -> withErrors(['ERROR' => 'Status Mahasiswa Non-Aktif']);
			
			$lokasi_pkm_mhs = MahasiswaPkmLokasi::join('pkm_lokasi', 'mahasiswa_pkm_lokasi.pkm_lokasi_id', '=', 'pkm_lokasi.id')
			-> join('pkm', 'pkm.id', '=', 'pkm_lokasi.pkm_id') 
			-> where('mahasiswa_id', $data -> id) -> first();
			
			if($lokasi_pkm_mhs) // Mahasiswa sudah PKM
			{
				$pkm = Pkm::find($lokasi_pkm_mhs -> pkm_id);
			}
			else
			{
				$pkm = Pkm::join('pkm_lokasi', 'pkm_lokasi.pkm_id', '=', 'pkm.id')
				-> join('pkm_lokasi_matkul', 'pkm_lokasi.id', '=', 'pkm_lokasi_matkul.pkm_lokasi_id')
				-> where('prodi_id', $data -> prodi_id)
				-> whereRaw('date(now()) BETWEEN STR_TO_DATE(tgl_mulai_daftar, "%d-%m-%Y") AND STR_TO_DATE(tgl_selesai_daftar, "%d-%m-%Y")')
				-> first();
			}
			
			if($pkm)
			{
				if($lokasi_pkm_mhs)
				{
					$show = true;
					$q = PkmLokasi::leftJoin('mahasiswa_pkm_lokasi', 'mahasiswa_pkm_lokasi.pkm_lokasi_id', '=', 'pkm_lokasi.id')
					-> where('pkm_lokasi.id', $lokasi_pkm_mhs -> pkm_lokasi_id)
					-> select(
					\DB::raw('
					pkm_lokasi.nama AS lokasi, kuota, pkm_lokasi.id AS lokasi_id,
					count(mahasiswa_id) AS terdaftar
					')) 
					-> first();
					$lokasi = $q -> lokasi . ' (Sudah mendaftar: ' . $q -> terdaftar .' orang | Sisa kuota: ' . intval($q -> kuota - $q -> terdaftar) .' orang)';
				}
				else
				{
					$lokasi = PkmLokasi::lokasiList($pkm -> pkm_id) -> get();
					
					if(!$lokasi -> count()) 
					return Redirect::back() -> with('warning', 'Lokasi PKM belum ditentukan. Hubungi Admin / Bagian Akademik');
				}
				
				if($lokasi !== false)
				{
					//check validasi
					$tanggungan = [
					'nil' => 'Tidak mempunyai tanggungan Nilai',
					'sks' => 'Tidak mempunyai tanggungan SKS',
					'krs' => 'Tidak mempunyai tanggungan KRS',
					'keu' => 'Tidak mempunyai tanggungan Keuangan',
					];
					
					$invalid = [];
					if(!cekTanggungan($data -> id, 'nil', 6)) $invalid['nil'] = 1;
					if(!cekTanggungan($data -> id, 'sks', 6)) $invalid['sks'] = 1;
					if(!cekTanggungan($data -> id, 'krs', 7)) $invalid['krs'] = 1;
					if(!cekTanggungan($data -> id, 'keu', null, 22)) $invalid['keu'] = 1;
					
					if(count($invalid) > 0)
					{
						return view('mahasiswa.pkm.syarat', compact('tanggungan', 'invalid', 'data'));
					}
					
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
					
					$alamat = '';
					if($data['jalan'] != '') $alamat .= 'Jl. ' . $data['jalan'] . ' ';
					if($data['dusun'] != '') $alamat .= $data['dusun'] . ' ';
					if($data['rt'] != '') $alamat .= 'RT ' . $data['rt'] . ' ';
					if($data['rw'] != '') $alamat .= 'RW ' . $data['rw'] . ' ';
					if($data['kelurahan'] != '') $alamat .= $data['kelurahan'] . ' ';
					if($data['wilayah_id'] != '')
					{
						$wilayah2 = \Siakad\Wilayah::dataKecamatan($data['wilayah_id']) -> first();
						$alamat .= trim($wilayah2 -> kec) . ' ' . trim($wilayah2 -> kab) . ' ' . trim($wilayah2 -> prov) . ' ';
					}
					if($data['kodePos'] != '') $alamat .= $data['kodePos'];
					
					if(!$lokasi_pkm_mhs)
					{ 
						$tmp = [];
						foreach($lokasi as $l)
						{
							$sisa_kuota = intval($l -> kuota - $l -> terdaftar);
							if($sisa_kuota > 0 || $show == true)
							{
								$tmp[$l -> lokasi_id] = $l -> lokasi . ' (Sudah mendaftar: ' . $l -> terdaftar .' orang | Sisa kuota: ' . $sisa_kuota .' orang)';
							}
						}
						$lokasi = $tmp;
					}
					
					return view('mahasiswa.pkm.daftar', compact('data', 'wilayah', 'alamat',  'show', 'pkm', 'admin', 'negara', 'lokasi', 'lokasi_pkm_mhs'));
				}
			}
			return Redirect::back() -> with('warning', 'Pendaftaran PKM belum dibuka.');
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$rules = [
			'foto' => ['required', 'min:3'],
			'tmpLahir' => ['required', 'min:3'],
			'tglLahir' => ['required', 'date_format:d-m-Y'],
			'hp' => ['required', 'min:10'],
			'kelurahan' => ['required', 'min:3'],
			'dusun' => ['required', 'min:3'],
			];
			
			$this -> validate($request, $rules);
			$data = \Auth::user() -> authable;
			
			if(MahasiswaPkmLokasi::where('mahasiswa_id', $data -> id) -> exists())
			{
				return Redirect::back() -> with('warning', 'Anda sudah Terdaftar.');
			}
			else
			{
				$input = $request -> except('_method', 'pkm_lokasi_id', 'matkul_id');
				
				if(isset($input['kemampuan']) && is_array($input['kemampuan']))
				{
					$input['kemampuan'] = implode('[]', $input['kemampuan']);
				}
				if(isset($input['kekurangan']) && is_array($input['kekurangan']))
				{
					$input['kekurangan'] = implode('[]', $input['kekurangan']);
				}
				
				$lokasi['pkm_lokasi_id'] = $request -> get('pkm_lokasi_id');
				$lokasi['mahasiswa_id'] = $data -> id;
				
				//nilai
				$nilai = Pkm::getNilai($request -> get('matkul_id'), $data -> id) -> first();
				if($nilai) $lokasi['nilai'] = $nilai -> nilai;
				
				\Siakad\Mahasiswa::find($data -> id) -> update($input);				
				
				MahasiswaPkmLokasi::create($lokasi);				
				
				return Redirect::back() -> with('success', 'Pendaftaran PKM sukses.');
			}
		}
		
		public function cetakFormulir($lokasi_id=null, $mahasiswa_id=null)
		{
			$auth = \Auth::user();
			
			if($auth -> role_id == 512) $mahasiswa = $auth -> authable;
			else $mahasiswa = \Siakad\Mahasiswa::find($mahasiswa_id);
			
			$prodi = \Siakad\Prodi::get();
			foreach($prodi as $p) $tmp[] = $p -> strata . ' ' . $p -> nama;
			$prodi = $tmp;
			
		$lokasi = PkmLokasi::lokasiDetail($lokasi_id) -> first();
		if(!$lokasi) return Redirect::back() -> with('warning', 'Maaf, Anda belum terdaftar mengikuti PKM.');
		
		$alamat = '';
		if($mahasiswa['jalan'] != '') $alamat .= 'Jl. ' . $mahasiswa['jalan'] . ' ';
		if($mahasiswa['dusun'] != '') $alamat .= $mahasiswa['dusun'] . ' ';
		if($mahasiswa['rt'] != '') $alamat .= 'RT ' . $mahasiswa['rt'] . ' ';
		if($mahasiswa['rw'] != '') $alamat .= 'RW ' . $mahasiswa['rw'] . ' ';
		if($mahasiswa['kelurahan'] != '') $alamat .= $mahasiswa['kelurahan'] . ' ';
		if($mahasiswa['wilayah_id'] != '')
		{
		$wilayah2 = \Siakad\Wilayah::dataKecamatan($mahasiswa['wilayah_id']) -> first();
		$alamat .= trim($wilayah2 -> kec) . ' ' . trim($wilayah2 -> kab) . ' ' . trim($wilayah2 -> prov) . ' ';
		}
		if($mahasiswa['kodePos'] != '') $alamat .= $mahasiswa['kodePos'];
		
		return view('mahasiswa.pkm.printFormulir', compact('mahasiswa', 'lokasi', 'alamat', 'prodi'));
		}
		
		/**
		* Remove the specified resource from storage.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function destroy($pkm_id, $pkm_lokasi_id, $mahasiswa_id)
		{
		MahasiswaPkmLokasi::where('pkm_lokasi_id', $pkm_lokasi_id)
		-> where('mahasiswa_id', $mahasiswa_id) -> delete();
		
		return Redirect::route('mahasiswa.pkm.lokasi.peserta.index', $pkm_id) -> with('success', 'Peserta telah dihapus');
		}
		}
				