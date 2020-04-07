<?php
	
	namespace Siakad\Http\Controllers;
	
	use Cache;
	use Redirect;
	
	use Siakad\Wisuda;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	
	class WisudaController extends Controller
	{
		//use \Siakad\DataExchangeTrait;	
		use \Siakad\DosenTrait;	
		use \Siakad\TapelTrait;	
		
		protected $tanggungan = [
		'nil' => 'Tidak mempunyai tanggungan Nilai',
		'sks' => 'Tidak mempunyai tanggungan SKS',
		'pro' => 'Tidak mempunyai tanggungan Kaprodi (Skripsi)', 
		'keu' => 'Tidak mempunyai tanggungan Keuangan',
		'skr' => 'Sudah mengikuti Ujian Skripsi',
		'pes' => 'Lunas biaya pesantren bagi mahasiswa MUKIM'
		];
		protected $rules = [
		'nama' => ['required', 'min:3'],
		'tanggal' => ['required', 'date', 'date_format:d-m-Y'],
		'kuota' => ['required', 'numeric'],
		'tgl_daftar_mulai' => ['required', 'date', 'date_format:d-m-Y'],
		'tgl_daftar_selesai' => ['date', 'date_format:d-m-Y', 'after:tgl_daftar_mulai']
		];
		
		/*
			15 Okt 2016
			Wisuda
		*/
		
		public function export($id)
		{
			$wisuda = Wisuda::whereId($id) -> first();
			$data = \Siakad\Mahasiswa::where('wisuda_id', $id) -> with('skripsi') -> with('prodi') -> with('kelas') -> get();
			$title = cutStr('Daftar Peserta ' . $wisuda -> nama, 27);
			// $this -> toXlsx(str_slug($title), $title, 'mahasiswa.wisuda.export', $data);
			return \Excel::download(new \Siakad\Exports\DataExport('mahasiswa.wisuda.export', $data), str_slug($title) . '.xlsx');
		}
		
		public function cetakFormulir($id, $mhs)
		{
			$mahasiswa = \Siakad\Mahasiswa::whereId($mhs) -> first();
			$wisuda = Wisuda::whereId($id) -> first();
			
			$alamat = '';
			if($mahasiswa['jalan'] != '') $alamat .= 'Jl. ' . $mahasiswa['jalan'] . ' ';
			if($mahasiswa['dusun'] != '') $alamat .= $mahasiswa['dusun'] . ' ';
			if($mahasiswa['rt'] != '') $alamat .= 'RT ' . $mahasiswa['rt'] . ' ';
			if($mahasiswa['rw'] != '') $alamat .= 'RW ' . $mahasiswa['rw'] . ' ';
			if($mahasiswa['kelurahan'] != '') $alamat .= $mahasiswa['kelurahan'] . ' ';
			if($mahasiswa['id_wil'] != '') 
			{
				$wilayah2 = \Siakad\Wilayah::dataKecamatan($mahasiswa['id_wil']) -> first();
				if($wilayah2)
				$alamat .= trim($wilayah2 -> kec) . ' ' . trim($wilayah2 -> kab) . ' ' . trim($wilayah2 -> prov) . ' ';
			}
			if($mahasiswa['kodePos'] != '') $alamat .= $mahasiswa['kodePos'];
			
			return view('mahasiswa.wisuda.printFormulir', compact('mahasiswa', 'wisuda', 'alamat'));
		}
		
		public function cetakFormulir2()
		{
			$mahasiswa = \Auth::user() -> authable;
			$wisuda = Wisuda::whereId($mahasiswa -> wisuda_id) -> first();
			$alamat = '';
			if($mahasiswa['jalan'] != '') $alamat .= 'Jl. ' . $mahasiswa['jalan'] . ' ';
			if($mahasiswa['dusun'] != '') $alamat .= $mahasiswa['dusun'] . ' ';
			if($mahasiswa['rt'] != '') $alamat .= 'RT ' . $mahasiswa['rt'] . ' ';
			if($mahasiswa['rw'] != '') $alamat .= 'RW ' . $mahasiswa['rw'] . ' ';
			if($mahasiswa['kelurahan'] != '') $alamat .= $mahasiswa['kelurahan'] . ' ';
			if($mahasiswa['id_wil'] != '') 
			{
				$wilayah2 = \Siakad\Wilayah::dataKecamatan($mahasiswa['id_wil']) -> first();
				if($wilayah2)
				$alamat .= trim($wilayah2 -> kec) . ' ' . trim($wilayah2 -> kab) . ' ' . trim($wilayah2 -> prov) . ' ';
			}
			if($mahasiswa['kodePos'] != '') $alamat .= $mahasiswa['kodePos'];
			
			return view('mahasiswa.wisuda.printFormulir', compact('mahasiswa', 'wisuda', 'alamat'));
		}
		
		public function formDaftarWisuda()
		{
			$data = $propinsi = null;
			$show = $admin = $full = false;
			
			$data = \Auth::user() -> authable;
			$tmp = [];
			if(intval($data -> wisuda_id) > 0) 
			{
				$show = true;	
				$wisuda = Wisuda::whereId($data -> wisuda_id) -> get();
			}
			else
			{
				if($data -> statusMhs != 1) return Redirect::back() -> with('warning', 'Status Mahasiswa tidak Aktif');
				$wisuda = \DB::select('
				SELECT * 
				FROM `wisuda`
				WHERE now() 
				BETWEEN STR_TO_DATE(concat(tgl_daftar_mulai, " 00:00:00"), "%d-%m-%Y %H:%i:%s") 
				AND STR_TO_DATE(concat(tgl_daftar_selesai, " 23:59:59"), "%d-%m-%Y %H:%i:%s")
				ORDER BY `tanggal`
				');
				
				foreach($wisuda as $w) $tmp[$w -> id] = $w -> nama . ' (' . formatTanggal(date('Y-m-d', strtotime($w -> tanggal))) . ')';
				$wisuda = $tmp;
			}
			
			if(count($wisuda) > 0)
			{				
				//check validasi
				$tanggungan = $this -> tanggungan;
				
				$invalid = [];
				if(!cekTanggungan($data -> id, 'nil', 8)) $invalid['nil'] = 1;
				if(!cekTanggungan($data -> id, 'sks', 8)) $invalid['sks'] = 1;
				if($data -> tg_pr_wis == 0) $invalid['pro'] = 1;
				// if(!cekTanggungan($data -> id, 'keu', null, 25)) $invalid['keu'] = 1;
				
				if(!cekTanggungan($data -> id, 'gol', null, null,5)) $invalid['keu'] = 1; // tagihan kelulusan
				
				if(!$data -> skripsi) return Redirect::back() -> with('warning', 'Data Skripsi tidak ditemukan');
				if($data -> skripsi -> validasi_kompre != 'y') $invalid['skr'] = 1;
				
				if(!cekTanggungan($data -> id, 'gol', null, null,2))  // if BAMP == false; check privilege
				{
					//cek privilege
					$privileged = false;
					$priv = \Siakad\Tagihan::privilege($data -> NIM, 2) -> get();
					if($priv)
					{
						foreach($priv as $p)
						{
							if($p -> privilege_wis == 'y') 
							{
								//if any of tagihan BAMP is privileged; set $privileged = true;break from loop;
								$privileged = true;
								break;
							}
						}
						
						//if $privilege == false; mark as invalid
						if(!$privileged) $invalid['pes'] = 1;
					}
				}
				
				if(count($invalid) > 0) 
				{
					return view('mahasiswa.wisuda.syarat', compact('data', 'tanggungan', 'invalid'));
				}
				
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
				if($data['id_wil'] != '') 
				{
					$wilayah2 = \Siakad\Wilayah::dataKecamatan($data['id_wil']) -> first();
					if($wilayah2)
					$alamat .= trim($wilayah2 -> kec) . ' ' . trim($wilayah2 -> kab) . ' ' . trim($wilayah2 -> prov) . ' ';
				}
				if($data['kodePos'] != '') $alamat .= $data['kodePos'];
				
				$data -> judul_skripsi = intval($data -> skripsi_id > 0) ? $data -> skripsi -> judul : '';
				
				if($data -> judul_skripsi != '')
				{
					$pb = $data -> skripsi -> pembimbing;
					foreach($pb as $p) 
					{
						if($show)
						{
							if(!isset($data -> dosen1_id)) $data -> dosen1_id = $p -> gelar_depan . ' ' . $p -> nama . ' ' . $p -> gelar_belakang;
							else $data -> dosen2_id = $p -> gelar_depan . ' ' . $p -> nama . ' ' . $p -> gelar_belakang;
						}
						else
						{
							if(!isset($data -> dosen1_id)) $data -> dosen1_id = $p -> id;
							else $data -> dosen2_id = $p -> id;
						}
					}
				}
				
				$dosen = $this -> getDosenSelection();
				
				return view('mahasiswa.wisuda.daftar', compact('data', 'wilayah', 'alamat', 'show', 'wisuda', 'admin', 'dosen'));
			}
			else
			{
				return Redirect::back() -> withErrors(['NOT_FOUND' => 'Pendaftaran Wisuda belum dibuka']);
			}
		}
		
		public function daftarWisuda(Request $request)
		{	
			$rules = [
			'foto' => ['required', 'min:3'],
			'namaAyah' => ['required', 'min:3'],
			'tmpLahir' => ['required', 'min:3'],
			'tglLahir' => ['required', 'date_format:d-m-Y'],
			'hp' => ['required', 'min:10'],
			'kelurahan' => ['required', 'min:3'],
			'dusun' => ['required', 'min:3'],
			'judul_skripsi' =>  ['required', 'min:10'],
			];
			
			$this -> validate($request, $rules);
			$input = $request -> except('_method');
			
			$wisuda = Wisuda::find($input['wisuda_id']);
			$peserta = Wisuda::dataWisuda($input['wisuda_id']) -> pluck('peserta');
			if(intval($peserta) >= intval($wisuda -> kuota)) 
			{
				$wisuda -> update(['daftar' => 'n']);
				return Redirect::route('mahasiswa.wisuda.formdaftar') -> with('warning', 'Kuota untuk ' . $wisuda -> nama . ' sudah penuh!');
			}
			
			$data = \Auth::user() -> authable;
			
			$mahasiswa = \Siakad\Mahasiswa::find($data -> id);
			
			if(intval($mahasiswa -> skripsi_id)) 
			{
				\Siakad\Skripsi::find($mahasiswa -> skripsi_id) -> update(['judul' => $input['judul_skripsi']]);
				$input['skripsi_id'] = $mahasiswa -> skripsi_id;
			}
			else 
			{
				$skripsi= \Siakad\Skripsi::create(['judul' => $input['judul_skripsi']]);
				$input['skripsi_id'] = $skripsi -> id;
			}
			unset($input['judul_skripsi']);
			
			$p1 = $input['dosen1_id'];
			$p2 = $input['dosen2_id'];
			
			unset($input['dosen1_id']);
			unset($input['dosen2_id']);
			
			if($p1 > 0)
			{
				\Siakad\DosenSkripsi::where('skripsi_id', $input['skripsi_id']) -> delete();
				\Siakad\DosenSkripsi::create(['dosen_id' => $p1, 'skripsi_id' => $input['skripsi_id']]);
			}
			
			if($p2 > 0 && $p2 != $p1)
			{
				\Siakad\DosenSkripsi::create(['dosen_id' => $p2, 'skripsi_id' => $input['skripsi_id']]);
			}
			
			$input['tgl_daftar_wisuda'] = date('Y-m-d h:i:s');
			$mahasiswa -> update($input);
			
			return Redirect::route('mahasiswa.wisuda.formdaftar') -> with('message', 'Proses pendaftaran berhasil.');
		}
		
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$wisuda = Wisuda::dataWisuda() -> get();
			return view('mahasiswa.wisuda.index', compact('wisuda'));
		}
		
		public function peserta($id)
		{
			$wisuda = Wisuda::whereId($id) -> first();
			$peserta = \Siakad\Mahasiswa::where('wisuda_id', $id) 
			-> with('skripsi') 
			-> with('prodi') 
			-> with('kelas') 
			-> orderBy('tgl_daftar_wisuda', 'desc') 
			-> paginate(30);
			return view('mahasiswa.wisuda.peserta', compact('wisuda', 'peserta'));
		}
		
		public function showPeserta($id, $mhs)
		{
			$wisuda = Wisuda::whereId($id) -> first();
			$show = $admin = true;
			$data = \Siakad\Mahasiswa::whereId($mhs) -> first();
			
			$alamat = '';
			if($data['jalan'] != '') $alamat .= 'Jl. ' . $data['jalan'] . ' ';
			if($data['dusun'] != '') $alamat .= $data['dusun'] . ' ';
			if($data['rt'] != '') $alamat .= 'RT ' . $data['rt'] . ' ';
			if($data['rw'] != '') $alamat .= 'RW ' . $data['rw'] . ' ';
			if($data['kelurahan'] != '') $alamat .= $data['kelurahan'] . ' ';
			if($data['id_wil'] != '') 
			{
				$wilayah2 = \Siakad\Wilayah::dataKecamatan($data['id_wil']) -> first();
				if($wilayah2)
				$alamat .= trim($wilayah2 -> kec) . ' ' . trim($wilayah2 -> kab) . ' ' . trim($wilayah2 -> prov) . ' ';
			}
			if($data['kodePos'] != '') $alamat .= $data['kodePos'];
			
			$data -> judul_skripsi = isset($data -> skripsi_id) ? $data -> skripsi -> judul : '';
			if($data -> judul_skripsi != '')
			{
				$pb = $data -> skripsi -> pembimbing;
				foreach($pb as $p) 
				{
					if(!isset($data -> dosen1_id)) $data -> dosen1_id = $p -> gelar_depan . ' ' . $p -> nama . ' ' . $p -> gelar_belakang;
					else $data -> dosen2_id = $p -> gelar_depan . ' ' . $p -> nama . ' ' . $p -> gelar_belakang;
				}
			}
			return view('mahasiswa.wisuda.daftar', compact('data', 'alamat', 'show', 'wisuda', 'admin'));
		}
		
		public function hapusPeserta($id, $mhs)
		{
			\Siakad\Mahasiswa::find($mhs) -> update(['wisuda_id' => '']);
			return Redirect::route('mahasiswa.wisuda.peserta', $id) -> with('message', 'Peserta wisuda berhasil dihapus.');
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$tapel = $this -> getTapelSelection('desc', false, 'nama2');
			return view('mahasiswa.wisuda.create', compact('tapel'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request  $request)
		{
			$this -> validate($request, $this -> rules);
			$input = $request -> all();
			$input['tanggal'] = date('Y-m-d', strtotime($input['tanggal']));
			
			$now = time();
			$input['daftar'] = (strtotime($input['tgl_daftar_mulai'] . ' 00:00:00') <= $now 
			&& strtotime($input['tgl_daftar_selesai'] . ' 23:59:59') >= $now) ? 'y' : 'n'; 
			
			Wisuda::create($input);
			return Redirect::route('mahasiswa.wisuda.index') -> with('message', 'Jadwal wisuda berhasil disimpan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{			
			$wisuda = Wisuda::where('id', $id) -> first();
			$tapel = $this -> getTapelSelection('desc', false, 'nama2');
			return view('mahasiswa.wisuda.edit', compact('wisuda', 'tapel'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $id)
		{
			$this -> validate($request, $this -> rules);
			$input = $request -> except('_method');
			
			$input['tanggal'] = date('Y-m-d', strtotime($input['tanggal']));
			$now = time();
			$input['daftar'] = (strtotime($input['tgl_daftar_mulai'] . ' 00:00:00') <= $now 
			&& strtotime($input['tgl_daftar_selesai'] . ' 23:59:59') >= $now) ? 'y' : 'n'; 
			
			Wisuda::find($id) -> update($input);
			
			return Redirect::route('mahasiswa.wisuda.index') -> with('message', 'Jadwal Wisuda berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			Wisuda::find($id) -> delete();
			return Redirect::route('mahasiswa.wisuda.index') -> with('message', 'Jadwal Wisuda berhasil dihapus.');
		}
	}
	
