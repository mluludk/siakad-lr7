<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	
	use Illuminate\Http\Request;
	
	use Siakad\Tagihan;
	use Siakad\Pembayaran;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class PembayaranController extends Controller
	{
		use \Siakad\BankTrait;
		use \Siakad\TapelTrait;
		use \Siakad\TagihanTrait;
		
		protected $rules = [
		'jumlah' => ['required', 'numeric', 'min:1']
		];
		
		protected $golongan = [
		1 => 'BAMK',
		'BAMP',
		'SPP',
		'Her Registrasi',
		'Kelulusan',
		99 => 'Lain-lain'
		];
		
		public function createWithToken(Request $request)
		{
			$kode = null !== $request -> get('kode') ? str_replace(' ', '', trim($request -> get('kode'))) : null;
			
			if($kode !== null && $kode !== '')
			{
				$identifier = substr($kode, 0, 2);
				if($identifier != '00' and $identifier != '99') return Redirect::to('/pembayaran/form?nim=' . $kode);
			}
			
			$histories = $status = null;
			$config_jenis = $config_golongan = []; 
			$lunas = false;
			
			if($kode !== null && $kode !== '')
			{
				$tagihan = Tagihan::detail(null, $kode) -> get();
				
				if($tagihan -> count()) 
				{
					$golongan = false;
					if($tagihan -> count() > 1) $golongan = true;
					
					$jumlah = $bayar = 0;
					foreach($tagihan as $t)
					{
						$jumlah += $t -> jumlah;
						$bayar += $t -> bayar;
					}
					if($jumlah - $bayar <= 0) $lunas = true;
					
					$tagihan = $tagihan[0];
					
					if($this -> generateTagihan($tagihan -> mahasiswa_id) == false) return Redirect::route('biayakuliah.form') -> withErrors(['NOT_FOUND' => 'Setup Biaya tidak ditemukan']);
					
					//history
					$status = Tagihan::where('mahasiswa_id', $tagihan -> mahasiswa_id) -> get();
					$histories = Pembayaran::riwayat($tagihan -> mahasiswa_id) -> get();   
					
					$config_jenis = config('custom.pilihan.jenisPembayaran');
					$config_golongan = $this -> golongan;
				}
				else
				$tagihan = null;
			}
			return  view('biaya.form_token', compact('tagihan', 'status', 'histories', 'kode', 'config_jenis', 'config_golongan', 'golongan', 'jumlah', 'lunas'));			
		}
		public function storeWithToken(Request $request)
		{
			$kode = $request -> get('kode');
			
			$tagihan = Tagihan::where('token', $kode) -> get();
			$mahasiswa = $tagihan[0] -> mahasiswa;
			
			if(!$tagihan) return Redirect::back() -> withErrors(['NOT_FOUND' => 'Data Tagihan tidak ditemukan.']);
			
			$pembayaran = [];
			$admin_id = \Auth::user() -> id;
			foreach($tagihan as $t)
			{
				$pembayaran[] = [
				'tagihan_id' => $t -> id,
				'jumlah' => $t -> jumlah,
				'user_id' => $admin_id
				];
				$t -> update([
				'bayar' => $t -> jumlah,
				'token' => null,
				'token_expired_at' => null
				]);
				
				$setup = \Siakad\BiayaKuliah::find($t -> setup_biaya_id);
				if($setup && $setup -> jenis_biaya_id == 2)
				{
					if($mahasiswa -> semesterMhs >= 9) $mahasiswa -> update(['statusMhs' => 1]); 
				}
			}
			
			if(count($pembayaran) > 0) Pembayaran::insert($pembayaran);
			
			return Redirect::to('/pembayaran/form?nim=' . $mahasiswa -> NIM) -> with('success', 'Pembayaran berhasil.');
		}
		
		public function fixPembayaran($mode='all')
		{
			if($mode == 'all')
			{
				$id = Tagihan::distinct() -> get(['mahasiswa_id']);
				foreach($id as $i)
				{
					echo $this ->fixPembayaranMahasiswa($i -> mahasiswa_id);
				}
			}
			else
			{
				$message = $this ->fixPembayaranMahasiswa($mode);
				return Redirect::back() -> with('warning', $message);
			}
		}
		
		private function fixPembayaranMahasiswa($mahasiswa_id)
		{
			$fix = 0;
			$tagihan = Tagihan::where('mahasiswa_id', $mahasiswa_id) -> get();
			foreach($tagihan as $t)
			{
				$pembayaran = Pembayaran::where('tagihan_id', $t -> id) -> sum('jumlah');
				if($pembayaran > 0)
				{
					Tagihan::find($t -> id) -> update(['bayar' => $pembayaran]);
					$fix++;
				}
			}	
			return 'Fixing: ' . $fix . ' item(s) on id ' . $mahasiswa_id . '<br/>';	
		}
		
		public function pembayaran($id = null)
		{
			$auth = \Auth::user();	
			
			if($auth -> role_id == 512)
			$mahasiswa = $auth -> authable;	
			else 
			$mahasiswa = \Siakad\Mahasiswa::find($id);
			
			if(!$mahasiswa) return Redirect::back() -> withErrors(['NOT_FOUND' => 'Data Mahasiswa tidak ditemukan']);
			
			$histories = Pembayaran::riwayat($mahasiswa -> id) -> get();
			return view('biaya.mahasiswa.riwayat', compact('histories', 'mahasiswa'));
		}
		
		public function printStatus($nim)
		{
			$mahasiswa = \Siakad\Mahasiswa::where('NIM', $nim) -> first();
			$status = Tagihan::tanggungan($mahasiswa -> id) -> get();
			return view('biaya.status', compact('status', 'mahasiswa'));
		}
		public function printReceipt($id)
		{
			$kwitansi = Pembayaran::kwitansi($id) -> first();
			
			$credential = [];
			
			//update password & show it
			if($kwitansi -> j_id == 2)
			{
				$user = \Siakad\User::where('authable_type', 'Siakad\Mahasiswa') -> where('authable_id', $kwitansi -> id);
				if($user -> count())
				{
					$data = $user -> first();
					$credential['username'] = $data -> username;
					$credential['password'] = rand(00000000, 99999999);
					$password = bcrypt($credential['password']);
					$user -> update(['password' => $password]);
				}
			}
			
			return view('biaya.kwitansi', compact('kwitansi', 'credential'));
		}
		
		public function create(Request $request)
		{
			$lunas = true;
			$nim = null !== $request -> get('nim') ? str_replace(' ', '', trim($request -> get('nim'))) : null;
			if($nim !== null && $nim !== '')
			{
				$identifier = substr($nim, 0, 2);
				if($identifier == '00' or $identifier == '99') return Redirect::to('/pembayaran/token?kode=' . $nim);
			}
			
			$mahasiswa = $tmp = $spp = $biaya = $jenis_list = $jenis_per_gol = $tagihan = $histories = null;
			$golongan = array_merge([0 => '-- Semua --'], $this -> golongan);
			
			if($nim !== null)
			{
				$mahasiswa = \Siakad\Mahasiswa::with('prodi') -> with('kelas') -> where('NIM', $nim) -> first();
				if($mahasiswa) 
				{
					if($this -> generateTagihan($mahasiswa -> id) == false) return Redirect::route('biayakuliah.form') -> withErrors(['NOT_FOUND' => 'Setup Biaya tidak ditemukan']);
					$tagihan = Tagihan::tanggungan($mahasiswa -> id) -> get();
					foreach($tagihan as $t)
					{
						//hanya yg belum lunas
						if($t -> jumlah - $t -> bayar > 0 && $t -> bank_id == 0)
						{
							$suffix = ' ';
							if(isset($t -> bulan)) $suffix .= $t -> bulan;
							if(isset($t -> tapel)) $suffix .= $t -> tapel;
							if(isset($t -> tahun)) $suffix .= $t -> tahun;
							
							$jenis_list[$t -> id] = $t -> nama . $suffix;											
							$jenis_per_gol[$t -> golongan][] = [$t -> id, $t -> nama . $suffix];		
						}
					}
					
					//history
					$histories = Pembayaran::riwayat($mahasiswa -> id) -> get();
					
					//Hilangkan Golongan yang tidak mempunyai pembayaran
					if($jenis_per_gol !== null) 
					{
						ksort($jenis_per_gol);
						foreach($golongan as $k => $v)
						{
							if(!array_key_exists($k, $jenis_per_gol) && $k > 0) unset($golongan[$k]);
						}
						$lunas = false;
					}
				}
			}
			return  view('biaya.form', compact('mahasiswa', 'jenis_list', 'tagihan', 'nim', 'histories',  'golongan', 'jenis_per_gol', 'lunas'));			
		}
		
		public function store(Request $request)
		{
			$this -> validate($request, $this -> rules);
			$input = $request -> except('_token');
			
			$tagihan = Tagihan::find($input['tagihan_id']);
			if(!$tagihan) return Redirect::back() -> withErrors(['NOT_FOUND' => 'Data Tagihan tidak ditemukan.']);
			
			$harus_dibayar = $tagihan -> jumlah - $tagihan -> bayar;
			
			// No cicilan, as it is already handled in tagihanTrait.
			if($tagihan -> bank_id > 0)
			{
				if($input['jumlah'] < $harus_dibayar) return Redirect::back() 
				-> withErrors(['NO_UTANG' => 'Jumlah Pembayaran kurang dari Tagihan. Mohon periksa kembali.']);
			}
			
			$sisa = 0;
			$msg = '';
			if($input['jumlah'] > $harus_dibayar) 
			{
				$sisa = $input['jumlah'] - $harus_dibayar;
				$input['jumlah'] = $harus_dibayar;
				$msg = ' Sisa pembayaran Rp.' . number_format($sisa, 0, ',', '.');
			}
			
			//23072019
			//Status Mahasiswa Otomatis Aktif untuk pembayaran Her smt 9 keatas
			$setup = \Siakad\BiayaKuliah::find($tagihan -> setup_biaya_id);
			if($setup && $setup -> jenis_biaya_id == 2)
			{
				if(($harus_dibayar - $input['jumlah']) <= 0)
				{
					$mahasiswa = \Siakad\Mahasiswa::find($input['mahasiswa_id']);
					if($mahasiswa -> semesterMhs >= 9) $mahasiswa -> update(['statusMhs' => 1]); 
				}
			}
			
			$pembayaran = [
			'tagihan_id' => $input['tagihan_id'],
			'jumlah' => $input['jumlah'],
			'user_id' => \Auth::user() -> id
			];
			
			if(Pembayaran::create($pembayaran))
			$tagihan -> increment('bayar', $input['jumlah']);
			else
			return Redirect::back() -> withErrors(['NOT_FOUND' => 'Pembayaran Gagal diproses.']);
			
			//Neraca
			// $this -> storeIntoNeraca(['transable_id' => $proses -> id, 'transable_type' => 'Siakad\Biaya', 'jenis' => 'masuk', 'jumlah' => $input['jumlah']]);
			
			return Redirect::back() -> with('success', 'Pembayaran berhasil.' . $msg);
		}
		
		public function destroy($id)
		{
			$pembayaran = Pembayaran::find($id);
			Tagihan::find($pembayaran -> tagihan_id) -> decrement('bayar', $pembayaran -> jumlah);
			$pembayaran -> delete();
			
			//$this -> deleteFromNeraca(['transable_id' => $id, 'transable_type' => 'Siakad\\Biaya']);
			return Redirect::back() -> with('success', 'Pembayaran berhasil dihapus.');
		}
		
		public function index(Request $request)
		{
			$q = null !== $request -> get('q') ? $request -> get('q') : null;
			$t = null !== $request -> get('t') ? $request -> get('t') : null;
			$p = null !== $request -> get('p') ? $request -> get('p') : 100;
			$m = null !== $request -> get('m') ? $request -> get('m') : null;
			
			$tgla = null !== $request -> get('tgla') ? $request -> get('tgla') : null;
			$tglb = null !== $request -> get('tglb') ? $request -> get('tglb') : null;
			
			$metode = $this -> getBankSelection();
			$tapel = $this -> getTapelSelection('desc', true, 'nama2');
			
			$pembayaran = Pembayaran::riwayat(null, $q, $t, $tgla, $tglb, $m);
			
			if($tgla != null or $tglb != null) 
			{
				$page = false;
				$pembayaran = $pembayaran -> get();
			}
			else  
		{
		$page = true;
		$pembayaran = $pembayaran -> paginate($p);
		}
		
		return view('biaya.index', compact('pembayaran', 'tapel', 'metode', 'page'));
		}		
		
		}
				