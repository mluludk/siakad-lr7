<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	
	use Illuminate\Http\Request;
	
	use Siakad\BiayaKuliah;
	use Siakad\Tagihan;
	
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class BiayaKuliahController extends Controller
	{
		use \Siakad\MahasiswaTrait;		
		use \Siakad\TagihanTrait;
		
		protected $rules= [
		'jumlah' => ['required', 'numeric']
		];
		
		protected $golongan = [
		1 => 'BAMK',
		'BAMP',
		'SPP',
		'Her Registrasi',
		'Kelulusan',
		99 => 'Lain-lain'
		];
		
		public function setup_copy(Request $request)
		{
			
			//error placeholder
			// return Redirect::back() -> with('warning', 'Fungsi ini masih dalam perbaikan. Terima kasih');
			
			$input = $request -> all();
			$from = BiayaKuliah::where('angkatan', $input['from']['angkatan'])
			-> where('prodi_id', $input['from']['prodi'])
			-> where('kelas_id', $input['from']['program'])
			-> where('jenisPembayaran', $input['from']['jenis'])
			-> get();
			
			if(!$from -> count()) return Redirect::back() -> with('warning', 'Setup Biaya asal belum diatur.');
			
			//check if setup destination exists
			if(BiayaKuliah::where('angkatan', $input['to']['angkatan'])
			-> where('prodi_id', $input['to']['prodi'])
			-> where('kelas_id', $input['to']['program'])
			-> where('jenisPembayaran', $input['to']['jenis'])
			-> exists()) return Redirect::back() -> withErrors(['DUPLICATION' => 'Setup Biaya tujuan sudah diatur. 
			Untuk menghindari Duplikasi Setup Biaya, proses penyalinan dibatalkan.']);
			
			$data = [];
			foreach($from as $f)
			{
				$data[] = [
				'jenis_biaya_id' => $f -> jenis_biaya_id,
				'angkatan' => $input['to']['angkatan'],
				'prodi_id' => $input['to']['prodi'],
				'kelas_id' => $input['to']['program'],
				'jenisPembayaran' => $input['to']['jenis'],
				'jumlah' => $f -> jumlah,
				'krs' => $f -> krs,
				'uts' => $f -> uts,
				'uas' => $f -> uas,
				'login' => $f -> login
				];
			}
			
			if(BiayaKuliah::insert($data))
			{
				return Redirect::to('/biaya/setup?_token='.
				csrf_token() . '&prodi=' . $input['to']['prodi'] . '&angkatan=' . $input['to']['angkatan'] . 
				'&program=' . $input['to']['program'] . '&jenis=' . $input['to']['jenis'])
				-> with('success', 'Setup Biaya berhasil disalin.');
			}
			else
			{
				return Redirect::back() -> with('warning', 'Setup Biaya gagal disalin.');
			}
		}
		
		public function index(Request $request)
		{
			$input = $request -> all();
			$angkatan = $this -> getGolongan('angkatan');
			$prodi = $this -> getGolongan('prodi');
			$program = $this -> getGolongan('program');
			
			$jenis['-'] = '-- Jenis Pembiayaan --';
			$tmp = config('custom.pilihan.jenisPembayaran');
			foreach($tmp as $k => $v) $jenis[$k] = $v;
			
			$jbiaya['-'] = '-- Biaya --';
			$tmp = \Siakad\JenisBiaya::orderBy('id') -> pluck('nama', 'id');
			foreach($tmp as $k => $v) $jbiaya[$k] = $v;
			
			$golongan = $this -> golongan;
			$biaya = [];
			$jumlah_nominal_golongan = [];
			if(
			isset($input['angkatan']) && intval($input['angkatan']) > 0 &&
			isset($input['prodi']) && intval($input['prodi']) > 0 &&
			isset($input['program']) && intval($input['program']) > 0 &&
			isset($input['jenis']) && intval($input['jenis']) > 0
			)
			{
				$tmp = BiayaKuliah::with('prodi', 'program', 'jenis')
				-> where('angkatan', $input['angkatan'])
				-> where('prodi_id', $input['prodi'])
				-> where('kelas_id', $input['program'])
				-> where('jenisPembayaran', $input['jenis'])
				-> get();
				
				foreach($tmp as $b)
				{
					foreach($golongan as $k => $v)
					{
						if(isset($b -> jenis -> golongan) and $b -> jenis -> golongan == $k)
						{
							$biaya[$k][] = [
							'biaya' => $b -> jenis -> nama,
							'cicilan' => $b -> cicilan,
							'jumlah' => number_format($b -> jumlah, 0, ',', '.'),
							'krs' => intval($b -> krs),
							'uts' => intval($b -> uts),
							'uas' => intval($b -> uas),
							'login' => $b -> login,
							'route' => [
							$b -> jenis_biaya_id, $b -> angkatan, $b -> prodi_id, $b -> kelas_id, $b -> jenisPembayaran
							]
							];	
							
							if(isset($jumlah_nominal_golongan[$k])) $jumlah_nominal_golongan[$k] += $b -> jumlah;
							else $jumlah_nominal_golongan[$k] = $b -> jumlah;
						}
					}
				}
				
				ksort($biaya);
			}			
			
			return view('biaya.setup.index', compact('biaya', 'angkatan', 'prodi', 'program', 'jenis', 'jumlah_nominal_golongan', 'golongan'));			
		}
		
		public function index2(Request $request)
		{
			$input = $request -> all();
			$angkatan = $this -> getGolongan('angkatan');
			$prodi = $this -> getGolongan('prodi');
			$program = $this -> getGolongan('program');
			
			$jenis['-'] = '-- Jenis Pembiayaan --';
			$tmp = config('custom.pilihan.jenisPembayaran');
			foreach($tmp as $k => $v) $jenis[$k] = $v;
			
			$jbiaya['-'] = '-- Biaya --';
			$tmp = \Siakad\JenisBiaya::orderBy('id') -> pluck('nama', 'id');
			foreach($tmp as $k => $v) $jbiaya[$k] = $v;
			
			$biaya = BiayaKuliah::with('prodi', 'program', 'jenis');
			if(isset($input['angkatan']) && $input['angkatan'] !== '-') $biaya = $biaya -> where('angkatan', $input['angkatan']);
			if(isset($input['prodi']) && $input['prodi'] !== '-') $biaya = $biaya -> where('prodi_id', $input['prodi']);
			if(isset($input['program']) && $input['program'] !== '-') $biaya = $biaya -> where('kelas_id', $input['program']);
			if(isset($input['jenis']) && $input['jenis'] !== '-') $biaya = $biaya -> where('jenisPembayaran', $input['jenis']);
			if(isset($input['biaya']) && $input['biaya'] !== '-') $biaya = $biaya -> where('jenis_biaya_id', $input['biaya']);
			
			$biaya = $biaya -> paginate(30);
			
			return view('biaya.setup.index', compact('biaya', 'angkatan', 'prodi', 'program', 'jenis', 'jbiaya'));			
		}
		
		public function create()
		{
			$angkatan = $this -> getGolongan('angkatan');
			$prodi = $this -> getGolongan('prodi');
			$program = $this -> getGolongan('program');
			
			$jenis['-'] = '-- Jenis Pembiayaan --';
			$tmp = config('custom.pilihan.jenisPembayaran');
			foreach($tmp as $k => $v) $jenis[$k] = $v;
			
			$jbiaya['-'] = '-- Biaya --';
			$tmp = \Siakad\JenisBiaya::orderBy('id') -> pluck('nama', 'id');
			foreach($tmp as $k => $v) $jbiaya[$k] = $v;
			
			$bank = \Siakad\Bank::pluck('nama', 'id');
			
			return view('biaya.setup.create', compact('angkatan', 'prodi', 'program', 'jenis', 'jbiaya', 'bank'));	
		}
		
		public function store(Request $request)
		{
			$this -> validate($request, $this -> rules);
			
			$input = $request -> all();
			
			//check cicilan
			$input['cicilan'] = $this -> checkCicilan($input['cicilan'], $input['jumlah']);
			if(false === $input['cicilan']) return Redirect::back() -> with('warning', 'Jumlah cicilan tidak cocok dengan total Pembayaran / Pengaturan Tanggal cicilan salah');
			
			try {
				BiayaKuliah::create($input);
				} catch(\Illuminate\Database\QueryException $e){
				$errorCode = $e->errorInfo[1];
				if($errorCode == 1062){
					return Redirect::back() -> withErrors(['DUPLICATION' => 'Setup Biaya sudah terdaftar.']);
				}
			}
			
			//Generate Tagihan OTOMATIS Utk tagihan baru
			$mahasiswa = \Siakad\Mahasiswa::where('prodi_id', $input['prodi_id'])
			-> where('angkatan', $input['angkatan'])
			-> where('kelasMhs', $input['kelas_id'])
			-> where('jenisPembayaran', $input['jenisPembayaran'])
			-> get();
			
			if($mahasiswa -> count())
			{
				foreach($mahasiswa as $m) $this -> generateTagihan($m -> id);
			}
			
			// return Redirect::route('biaya.setup.index') -> with('success', 'Data berhasil dimasukkan. ');
			return Redirect::to('/biaya/setup?_token=' . csrf_token() . 
			'&prodi=' . $input['prodi_id'] . 
			'&angkatan=' . $input['angkatan'] .
			'&program=' . $input['kelas_id'] . 
			'&jenis=' . $input['jenisPembayaran']) 
			-> with('success', 'Data berhasil dimasukkan. ');
		}
		
		public function destroy($jenis_biaya_id, $angkatan, $prodi_id, $kelas_id, $jenisPembayaran)
		{
			$data = BiayaKuliah::where('jenis_biaya_id', $jenis_biaya_id)
			-> where('angkatan', $angkatan)
			-> where('prodi_id', $prodi_id)
			-> where('kelas_id', $kelas_id)
			-> where('jenisPembayaran', $jenisPembayaran);
			
			$setup = $data -> first();
			
			$pembayaran = BiayaKuliah::join('tagihan', 'tagihan.setup_biaya_id', '=', 'setup_biaya.id')
			-> join('pembayaran', 'pembayaran.tagihan_id', '=', 'tagihan.id')
			-> where('setup_biaya.id', $setup -> id) -> get(['pembayaran.*']);		
			
			if($pembayaran -> count())
			{
				return Redirect::back() -> withErrors(['DATA_EXISTS' => 'Setup Biaya telah direferensi pada Data Pembayaran. Setup Biaya tidak dapat dihapus ']);
			}
			else
			{
				$data -> delete();			
			}
			return Redirect::back() -> with('success', 'Data berhasil dihapus. ');
		}
		
		public function edit($jenis_biaya_id, $angkatan, $prodi_id, $kelas_id, $jenisPembayaran)
		{
			$setup = BiayaKuliah::where('jenis_biaya_id', $jenis_biaya_id)
			-> where('angkatan', $angkatan)
			-> where('prodi_id', $prodi_id)
			-> where('kelas_id', $kelas_id)
			-> where('jenisPembayaran', $jenisPembayaran)
			-> first();
			
			$tagihan = Tagihan::where('setup_biaya_id', $setup -> id) -> get();
			$message = $tagihan -> count() ? 'Data telah masuk dalam tagihan. Pastikan perubahan Setup Biaya tidak menimbulkan hal-hal yang tidak diinginkan.' : null;
			
			$setup -> cicilan = json_decode($setup -> cicilan);
			
			$angkatan = $this -> getGolongan('angkatan');
			$prodi = $this -> getGolongan('prodi');
			$program = $this -> getGolongan('program');
			
			$jenis['-'] = '-- Jenis Pembiayaan --';
			$tmp = config('custom.pilihan.jenisPembayaran');
			foreach($tmp as $k => $v) $jenis[$k] = $v;
			
			$jbiaya['-'] = '-- Biaya --';
			$tmp = \Siakad\JenisBiaya::orderBy('id') -> pluck('nama', 'id');
			foreach($tmp as $k => $v) $jbiaya[$k] = $v;
			
			$tmp = \Siakad\Bank::orderBy('id') -> pluck('nama', 'id');
			foreach($tmp as $k => $v) $bank[$k] = $v;
			
			
			return view('biaya.setup.edit', compact('angkatan', 'prodi', 'program', 'jenis', 'jbiaya', 'setup', 'bank', 'message'));	
		}
		
		public function update(Request $request)
		{
			$this -> validate($request, $this -> rules);
			
			$input = $request -> except('_token', '_method');
			
			//check cicilan
			$input['cicilan'] = $this -> checkCicilan($input['cicilan'], $input['jumlah']);		
			if(false === $input['cicilan']) return Redirect::back() -> with('warning', 'Jumlah cicilan tidak cocok dengan total Pembayaran / Pengaturan Tanggal cicilan salah');
			
			$setup = BiayaKuliah::where('jenis_biaya_id', $input['jenis_biaya_id'])
			-> where('angkatan', $input['angkatan'])
			-> where('prodi_id', $input['prodi_id'])
			-> where('kelas_id', $input['kelas_id'])
			-> where('jenisPembayaran', $input['jenisPembayaran']);
			
			if($setup)
			{
				//update jumlah tagihan
				$data = $setup -> first();
				\Siakad\Tagihan::where('setup_biaya_id', $data -> id) -> update(['jumlah' => $input['jumlah']]);
				
				$setup -> update($input);
				// return Redirect::route('biaya.setup.index') -> with('success', 'Data berhasil diperbarui. ');
				return Redirect::to('/biaya/setup?_token='.
				csrf_token() . '&prodi=' . $input['prodi_id'] . '&angkatan=' . $input['angkatan'] . '&program=' . $input['kelas_id'] . '&jenis=' . $input['jenisPembayaran']
				) -> with('success', 'Data berhasil diperbarui. ');
			}
		}
		private function checkCicilan($cicilan, $jumlah)
		{
			if(!is_array($cicilan)) return null;
			$tmp = [];
			$jc = $d = 0;
			foreach($cicilan as $c)
			{
				$jml = intval($c['jml']);
				
				if($jml > 0)
				{
					$jc += $jml;
					if(strtotime($c['tgla']) > strtotime($c['tglb']) or !strtotime($c['tgla']) or !strtotime($c['tglb'])) continue;
					
					$d++;
					$tmp[$d] = [
					'jml' => $jml,
					'tgla' => $c['tgla'],
					'tglb' => $c['tglb']
					];
				}
			}
			
			if($jc == 0) return null; // NO cicilan
			
			if($jc != $jumlah) return false;
			
			return json_encode($tmp);
		}
		
		public function setup($tahun = null, $prodi_id = 1, $program_id = 1, $jenisPembayaran = 1)
		{
			$data['angkatan'] = $tahun;
			$data['prodi_id'] = $prodi_id;
			$data['program_id'] = $program_id;
			$data['jenisPembayaran'] = $jenisPembayaran;
			
			$angkatan = \DB::select('SELECT DISTINCT LEFT(`nim`, 4) AS `tahun` FROM `mahasiswa` ORDER BY `tahun`');
			foreach($angkatan as $a) $tmp[$a -> tahun] = $a -> tahun;
			$angkatan = $tmp;
			if($tahun == null) $data['angkatan'] = array_values($tmp)[0];
			
			\DB::insert('
			INSERT IGNORE INTO `setup_biaya` (jenis_biaya_id, angkatan, jumlah, prodi_id, kelas_id, jenisPembayaran)
			SELECT `id`, ' . $data['angkatan'] . ', 0, ' . $data['prodi_id'] . ', ' . $data['program_id'] . ', ' . $data['jenisPembayaran'] . '
			FROM `jenis_biaya`
			');
			$jenis = \Siakad\JenisBiaya::biayaKuliah($data) -> get();
			
			$prodi = \Siakad\Prodi::orderBy('nama') -> pluck('nama', 'id');
			$program = \Siakad\Kelas::orderBy('nama') -> pluck('nama', 'id');
			return view('biaya.setup', compact('jenis', 'angkatan', 'prodi', 'program', 'data'));
		}
		
		public function setupSubmit(Request $request)
		{
			$input = $request -> all();
			$c = $total = 0;
			$saved = false;
			foreach($input['jenis_biaya'] as $j) 
			{
				// if($jumlah = intval($input['jumlah'][$c]) != 0)
				// {
				$biaya = BiayaKuliah::where('jenis_biaya_id', $j) 
				-> where('angkatan', $input['angkatan']) 
				-> where('prodi_id', $input['prodi_id'])
				-> where('kelas_id', $input['program_id'])
				-> where('jenisPembayaran', $input['jenisPembayaran'])
				-> update(['jumlah' => intval($input['jumlah'][$c])]);
				if($biaya) $saved = true;
				$total += $input['jumlah'][$c];
				// }
				$c++;
			}
			if($saved)
			return \Response::json(['success' => true, 'total' => number_format($total, 0, ',', '.')]);
			else
			return \Response::json(['success' => false, 'total' => number_format($total, 0, ',', '.')]);
			// return Redirect::route('biayakuliah.setup') -> with('success', 'Biaya Kuliah berhasil disimpan');
		}
	}
