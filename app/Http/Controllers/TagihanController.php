<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Siakad\Jobs\GenerateTagihan;
	
	use Siakad\Tagihan;
	use Siakad\Pembayaran;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	
	class TagihanController extends Controller
	{
		use \Siakad\TapelTrait;
		use \Siakad\MahasiswaTrait;
		use \Siakad\TagihanTrait;
		
		protected $golongan = [
		1 => 'BAMK',
		'BAMP',
		'SPP',
		'Her Registrasi',
		'Kelulusan',
		99 => 'Lain-lain'
		];
		
		public function getTagihanToken($tagihan_id, $golongan=null)
		{
			if($golongan == null)
			{
				$tagihan = Tagihan::where('id', $tagihan_id) -> get();
			}
			else
			{
				$id_array = explode(':', $tagihan_id);
				$tagihan = Tagihan::whereIn('id', $id_array) -> get();
			}
			
			if(!$tagihan) return Redirect::back() -> withErrors(['NOT_FOUND' => 'Tagihan tidak ditemukan.']);
			
			$mahasiswa = $tagihan[0] -> mahasiswa;
			$jumlah = $bayar = 0;
			foreach($tagihan as $t)
			{
				$jumlah += intval($t -> jumlah);
				$bayar += intval($t -> bayar);
			}
			$sisa = $jumlah - $bayar;
			if($sisa <= 0) return Redirect::back() -> with('message', 'Tagihan sudah Lunas.');
			
			$status = $bayar > 0 ? 'Belum Lunas' : 'Belum dibayar';
			
			$now = time();
			if($tagihan[0] -> token == '' or $now > strtotime($tagihan[0] -> token_expired_at))
			{
				$token_prefix = $golongan == null ? '00' : '99';
				$token = $token_prefix . rand(1111, 9999);
				$token_expired_at = date('Y-m-d H:i:s', $now + 86400);
				
				foreach($tagihan as $t)
				{
					$t -> update([
					'token' => $token,
					'token_expired_at' => $token_expired_at
					]);
				}
				
			}	
			else
			{
				$token = $tagihan[0] -> token;
				$token_expired_at = $tagihan[0] -> token_expired_at;
			}
			
			$nama_tagihan = $golongan == null ? $tagihan[0] -> nama : strtoupper($golongan);
			
			return view('biaya.mahasiswa.tagihan_token_print', compact('mahasiswa', 'nama_tagihan', 'jumlah', 'status', 'token', 'token_expired_at', 'sisa'));
		}
		
		public function tagihanToken()
		{
			$auth = \Auth::user();	
			$mahasiswa = $auth -> authable;	
			
			if(!$mahasiswa) return Redirect::back() -> withErrors(['NOT_FOUND' => 'Data Mahasiswa tidak ditemukan']);
			
			$tanggungan = Tagihan::tanggungan($mahasiswa -> id) -> get();
			
			$tagihan = [];
			foreach($tanggungan as $tg)
			{
				if((intval($tg -> jumlah) - intval($tg -> bayar)) > 0)
				{
					if($tg -> golongan == 1)
					{
						$tagihan['bamk']['id'] = isset($tagihan['bamk']['id']) ? $tagihan['bamk']['id'] . ':' . $tg -> id : $tg -> id;
						$tagihan['bamk']['jumlah'] = isset($tagihan['bamk']['jumlah']) ? $tagihan['bamk']['jumlah'] + $tg -> jumlah : $tg -> jumlah;
						$tagihan['bamk']['bayar'] = isset($tagihan['bamk']['bayar']) ? $tagihan['bamk']['bayar'] + $tg -> bayar : $tg -> bayar;
					}
					
					if($tg -> golongan == 2)
					{
						$tagihan['bamp']['id'] = $tg -> id;
						$tagihan['bamp']['jumlah'] = isset($tagihan['bamp']['jumlah']) ? $tagihan['bamp']['jumlah'] + $tg -> jumlah : $tg -> jumlah;
						$tagihan['bamp']['bayar'] = isset($tagihan['bamp']['bayar']) ? $tagihan['bamp']['bayar'] + $tg -> bayar : $tg -> bayar;
					}
					
					if($tg -> golongan == 3)
					{
						$tagihan['spp'][$tg -> tapel][]= [						
						'id' => $tg -> id,
						'nama' => $tg -> nama_tagihan,
						'jumlah' => $tg -> jumlah,
						'bayar' => $tg -> bayar,
						'tgl_cicilan_awal' => $tg -> tgl_cicilan_awal,
						'tgl_cicilan_akhir' => $tg -> tgl_cicilan_akhir
						];
					}
					
					if($tg -> golongan == 4)
					{
						// $tagihan['her'][$tg -> tapel]['nama'] = $tg -> nama_tagihan;
						$tagihan['her'][$tg -> tapel] = [
						'id' => $tg -> id,
						'jumlah' => $tg -> jumlah,
						'bayar' => $tg -> bayar
						];
					}
					
					if($tg -> golongan == 5)
					{
						$tagihan['kel'][] = [
						'id' => $tg -> id,
						'nama' => $tg -> nama_tagihan,
						'jumlah' => $tg -> jumlah,
						'bayar' => $tg -> bayar
						] ;
					}
				}
			}	
			
			return view('biaya.mahasiswa.tagihan_token', compact('tagihan', 'mahasiswa', 'auth'));
		}
		
		public function unlock($id)
		{
			Tagihan::find($id) -> update(['override' => 'y']);
			return Redirect::back() -> with('message', 'Pembayaran Tagihan telah dibuka.');
		}
		
		public function fixNama()
		{
			$tagihan = Tagihan::leftJoin('setup_biaya', 'setup_biaya.id', '=', 'setup_biaya_id')
			-> join('jenis_biaya', 'setup_biaya.jenis_biaya_id', '=', 'jenis_biaya.id')
			-> whereNull('tagihan.nama')
			-> get(['tagihan.id', 'tagihan.tapel', 'tagihan.bulan', 'tagihan.tahun', 'jenis_biaya.nama as jns']);
			
			$upd = [];
			foreach($tagihan as $t)
			{
				$suffix = '';
				if(isset($t -> bulan)) $suffix .= $t -> bulan;
				if(isset($t -> tapel)) $suffix .= $t -> tapel;
				if(isset($t -> tahun)) $suffix .= $t -> tahun;
				
				$upd[trim($t -> jns . ' ' . $suffix)][] = $t -> id;
			}
			
			if(count($upd))
			{			
				foreach($upd as $k => $v) echo 'UPDATE IGNORE `tagihan` SET `nama` ="'. $k . '" WHERE `id` IN (' . implode(',', $v) . ');' . PHP_EOL;
			}
			else echo 'No change';
		}
		
		public function fixDuplicate($angkatan)
		{
			$query = '
			select mahasiswa.id as mahasiswa_id, tagihan.id as tagihan_id, jenis_biaya_id from tagihan 
			inner join setup_biaya on setup_biaya.id = tagihan.setup_biaya_id
			inner join jenis_biaya on jenis_biaya.id = jenis_biaya_id
			inner join mahasiswa on mahasiswa.id = tagihan.mahasiswa_id
			where mahasiswa.angkatan = "' . $angkatan . '"
			order by mahasiswa_id, jenis_biaya_id, tagihan.id';
			
			$tagihan = \DB::select($query);
			$tgh = $dup = [];
			foreach($tagihan as $t)
			{
				if(isset($tgh[$t -> mahasiswa_id]) && in_array($t -> jenis_biaya_id, $tgh[$t -> mahasiswa_id])) $dup[] = $t -> tagihan_id;
				else
				$tgh[$t -> mahasiswa_id][] = $t -> jenis_biaya_id;
			}
			
			if(count($dup) > 0) 
			{
				Tagihan::whereIn('id', $dup) -> delete();
				echo 'Duplicate Deleted';
			}
			else echo "No Duplicate Records found";
		}
		
		// OBSOLETE !!!!!
		/* 		public function generateData() //from SIAKAD V2, before tagihan
			{	
			echo 'Jobs Queued<br/>';
			
			// $this -> dispatch(new GenerateTagihan('sql'));
			$this -> dispatch(new GenerateTagihan($angkatan));
			
			echo 'OK';
		} */
		
		public function create()
		{
			$tapel = $this -> getTapelSelection('desc', false, 'nama2');
			$prodi = $this -> getGolongan('prodi');
			$angkatan = $this -> getGolongan('angkatan');
			return view('biaya.tagihan.create', compact('angkatan', 'prodi', 'tapel'));
		}
		public function store(Request $request)
		{
			$input = $request -> except('_token');
			
			if($input['tapel_id'] != '-') $tapel_id = $input['tapel_id'] != '-' ? $input['tapel_id'] : null;
			
			$mahasiswa = \Siakad\Mahasiswa::whereNotIn('statusMhs', [4, 7, 2, 3, 8, 6, 5]);
			if($input['prodi_id'] != '-') $mahasiswa = $mahasiswa -> where('prodi_id', $input['prodi_id']);
			if($input['angkatan'] != '-') $mahasiswa = $mahasiswa -> where('angkatan', $input['angkatan']);
			
			foreach($mahasiswa -> get(['id', 'NIM', 'nama']) as $m)
			{
				$this -> generateTagihan($m -> id, true, $tapel_id);
			}
			
			return Redirect::back() -> with('message', 'Tagihan berhasil di-Generate.');			
		}
		
		public function generateData($angkatan=null)
		{	
			$mahasiswa = \Siakad\Mahasiswa::whereNotIn('statusMhs', [4, 7, 2, 3, 8, 6, 5]);
			if($angkatan != null)
			$mahasiswa = $mahasiswa -> whereAngkatan($angkatan);
			
			$mahasiswa = $mahasiswa -> get();
			foreach($mahasiswa as $m)
			{
				if($this -> generateTagihan($m -> id)) echo 'Tagihan untuk mahasiswa ' . $m -> nama . ' (' . $m -> NIM . ') berhasil dibuat';
				else echo 'Tagihan untuk mahasiswa ' . $m -> nama . ' (' . $m -> NIM . ') gagal dibuat';
				
				echo '</br>';
			}
		} 
		
		public function tagihan($id = null)
		{
			$auth = \Auth::user();	
			if($auth -> role_id == 512)
			$mahasiswa = $auth -> authable;	
			else 
			$mahasiswa = \Siakad\Mahasiswa::find($id);
			
			if(!$mahasiswa) return Redirect::back() -> withErrors(['NOT_FOUND' => 'Data Mahasiswa tidak ditemukan']);
			
			$tagihan = Tagihan::tanggungan($mahasiswa -> id) -> get();
			
			$status = [];
			foreach($tagihan as $tg)
			{
				$sisa = $tg -> jumlah - $tg -> bayar;
				$status[$tg -> golongan]['tg'] = isset($status[$tg -> golongan]['tg']) ? $status[$tg -> golongan]['tg'] + $tg -> jumlah : $tg -> jumlah;
				$status[$tg -> golongan]['db'] = isset($status[$tg -> golongan]['db']) ? $status[$tg -> golongan]['db'] + $tg -> bayar : $tg -> bayar;
				$status[$tg -> golongan]['ss'] = isset($status[$tg -> golongan]['ss']) ? $status[$tg -> golongan]['ss'] + $sisa : $sisa;
			}
			
			ksort($status);
			$golongan = $this -> golongan;
			return view('biaya.mahasiswa.tagihan', compact('status', 'golongan', 'mahasiswa'));
		}
		
		public function index(Request $request)
		{
			$q = null !== $request -> get('q') ? $request -> get('q') : null;
			$t = null !== $request -> get('t') ? $request -> get('t') : null;
			$p = null !== $request -> get('p') ? $request -> get('p') : 100;
			
			$tagihan = Tagihan::daftar($q, $t) -> paginate($p);
			
			return view('biaya.tagihan.index', compact('tagihan'));
		}	
		
		public function destroy($id)
		{
			Tagihan::find($id) -> delete();
			return Redirect::back() -> with('message', 'Data berhasil dihapus.');
		}
		
		public function edit($id)
		{
			$tagihan = Tagihan::detail($id) -> first();
			return view('biaya.tagihan.edit', compact('tagihan'));
		}
		
		public function update(Request $request, $id)
		{
			$input = $request -> except('_method');
			Tagihan::find($id) -> update($input);
			return Redirect::route('tagihan.privilege') -> with('message', 'Data berhasil diperbarui.');
		}
		
		public function privilegeGolongan(Request $request, $golongan_id=2)
		{
			$golongan = $this -> golongan;
			$q = null !== $request -> get('q') && $request -> get('q') != '' ? $request -> get('q') : null;
			
			$tagihan = Tagihan::privilege($q, $golongan_id) -> get();
			
			$bamp = [];
			foreach($tagihan as $t) $bamp[$t -> mahasiswa_id][] = $t;
			foreach($bamp as $b => $t) 
			{
				$tanggungan = 0;
				foreach($t as $t2)
				{
					$tanggungan += $t2 -> jumlah - $t2 -> bayar;
				}
				
				$tgolongan[$b] = [
				'mahasiswa_id' => $bamp[$b][0] -> mahasiswa_id,
				'nim' => $bamp[$b][0] -> NIM,
				'nama' => $bamp[$b][0] -> nama,
				'privilege_wis' => $bamp[$b][0] -> privilege_wis,
				'tanggungan' => $tanggungan
				];
			}
			return view('biaya.tagihan.privilege_golongan', compact('tgolongan', 'golongan_id'));
		}
		public function updateGolongan($golongan_id, $mahasiswa_id, $privilege, $value)
		{
			$query = '
			UPDATE `tagihan` 
			inner join mahasiswa on mahasiswa.id = tagihan.mahasiswa_id
			inner join setup_biaya on setup_biaya.id = tagihan.setup_biaya_id
			inner join jenis_biaya on setup_biaya.jenis_biaya_id = jenis_biaya.id
			SET `'. $privilege .'` = "'. $value .'" 
			WHERE jenis_biaya.golongan = :golongan_id 
			AND mahasiswa_id = :mahasiswa_id;
			';
			\DB::update($query, ['golongan_id' => $golongan_id, 'mahasiswa_id' => $mahasiswa_id]);
			
			return Redirect::route('tagihan.privilege.golongan', $golongan_id) -> with('message', 'Setting Privilege telah disimpan');
		}
		
		public function privilege(Request $request)
		{
			$q = null !== $request -> get('q') && $request -> get('q') != '' ? $request -> get('q') : null;
			
			$tagihan = Tagihan::privilege($q) -> paginate(50);
			return view('biaya.tagihan.privilege', compact('tagihan'));
		}
		
		public function rincian(Request $request)
		{
			$input = $request -> all();
			$mode = isset($input['mode']) ? $input['mode'] : null;
			$angkatan = $this -> getGolongan('angkatan');
			$prodi = $this -> getGolongan('prodi');
			$program = $this -> getGolongan('program');
			
			$jenis['-'] = '-- Jenis Pembiayaan --';
			$tmp = config('custom.pilihan.jenisPembayaran');
			foreach($tmp as $k => $v) $jenis[$k] = $v;
			
			$setup = $mahasiswa = $title = null;
			$rincian = [];
			
			if(
			isset($input['angkatan']) && intval($input['angkatan']) > 0 &&
			isset($input['prodi']) && intval($input['prodi']) > 0 &&
			isset($input['program']) && intval($input['program']) > 0 &&
			isset($input['jenis']) && intval($input['jenis']) > 0
			)
			{				
				$tprodi = \Siakad\Prodi::whereId($input['prodi']) -> first();
				$tkelas = \Siakad\Kelas::whereId($input['program']) -> first();
				$tjenis = config('custom.pilihan.jenisPembayaran')[$input['jenis']];
				$title = $tprodi -> strata . ' ' . $tprodi -> singkatan . ' ' . $input['angkatan'] . ' ' . $tkelas -> nama . ' ' . $tjenis;
				
				$mahasiswa = \Siakad\Mahasiswa::where('angkatan', $input['angkatan']) 
				-> where('prodi_id', $input['prodi']) 
				-> where('kelasMhs', $input['program'])
				-> where('jenisPembayaran', $input['jenis'])
				-> pluck('id') 
				-> toArray();
				
				$setup = \Siakad\BiayaKuliah::jenisPembayaran([
				'angkatan' => $input['angkatan'],
				'prodi_id' => $input['prodi'],
				'program_id' => $input['program'],
				'jenisPembayaran' => $input['jenis']
				]) -> get();
				
				if(count($mahasiswa) > 0)
				{					
					$tmp = Tagihan::rincian($mahasiswa) -> get();
					$bayar = 0;
					foreach($tmp as $r)
					{
						$bayar = isset($r -> bayar) ? (int)$r -> bayar : 0;
						$rincian[$r -> mahasiswa_id . '-' . $r -> nama][$r -> jenis_biaya_id] = 
						isset($rincian[$r -> mahasiswa_id . '-' . $r -> nama][$r -> jenis_biaya_id]) ? 
						$rincian[$r -> mahasiswa_id . '-' . $r -> nama][$r -> jenis_biaya_id] + $bayar : $bayar;
					}
				}
			}
			
			if($mode == 'xlsx')
			{
				$data = compact('title', 'setup', 'rincian');
				
				/* 
				\Excel::create('rincin-biaya-pendidikan-' . str_slug($title) . '-' . date('Y-m-d H-i-s'), function($excel) use($data){
					$excel
					->setTitle('Rincian Biaya Pendidikan')
					->setCreator('schz')
					->setLastModifiedBy('Schz')
					->setManager('Schz')
					->setCompany('Schz. Co')
					->setKeywords('Al-Hikam, STAIMA, STAIMA Al-Hikam Malang, Rincian Biaya Pendidikan')
					->setDescription('Rincian Biaya Pendidikan format excel');
					$excel->sheet('Rincian Biaya Pendidikan', function($sheet) use($data){
						$sheet
						-> setOrientation('landscape')
						-> setFontSize(12)
						-> loadView('biaya.rinciansave') -> with($data);
					});
					
				})->download('xlsx');
				 */
				$title = 'rincian-biaya-pendidikan-' . str_slug($title) . '-' . date('Y-m-d H-i-s');
				return \Excel::download(new \Siakad\Exports\DataExport('biaya.rinciansave', $data), $title . '.xlsx');	
			}
			elseif($mode == 'print') return view('biaya.rincianprint', compact('title', 'setup', 'rincian'));
			
			else	return view('biaya.rincianbiaya', compact('angkatan', 'prodi', 'program', 'jenis', 'setup', 'rincian'));
		}
	}
