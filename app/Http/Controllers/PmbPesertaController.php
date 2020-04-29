<?php namespace Siakad\Http\Controllers;
	
	use Excel;
	use Input;
	use Redirect;
	
	use Siakad\PmbPeserta;
	use Siakad\Pmb;
	
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	use Siakad\Jobs\SendPmbNotificationEmail;
	
	use Imagine\Image\Box;
	use Imagine\Image\ImageInterface;
	use Orchestra\Imagine\Facade as Imagine;
	
	class PmbPesertaController extends Controller {
		protected $rules = [
		'nama' => 'required',
		'namaIbu' => 'required',
		'telpMhs' => 'required',
		'tglLahir' => 'required|date',
		'noKtp' => 'required|digits_between:16,17',
		/* 'foto' => 'required|image|max:2048',
		'slip' => 'required|image|max:2048', */
		];
		
		public function dialog($type)
		{
			$types = ['kartu' => 'Kartu Ujian', 'formulir' => 'Formulir Pendaftaran'];
			if(!isset($types[$type])) return view('pmb.peserta.error', 
			[
			'error' => 'not_found'
			]);
			
			return view('pmb.peserta.dialog', compact('type', 'types'));
		}
		
		public function destroy($id, $kode)
		{
			PmbPeserta::whereKode($kode) -> delete();
			return Redirect::route('pmb.peserta.index', $id) -> with('success', 'Pendaftar berhasil dihapus.');
		}
		
		public function printing($type, $kode)
		{
			$data = PmbPeserta::whereKode($kode) -> first();	
			if(!$data) return view('pmb.peserta.error', 
			[
			'error' => 'data_not_found'
			]);
			$pmb = Pmb::find($data -> pmb_id);
			$prodi = \Siakad\Prodi::orderBy('nama') -> get();
			foreach($prodi as $p) $tmp[$p -> id] = $p;
			$prodi = $tmp;
			
			if($type === 'formulir') return view('pmb.peserta.formulir', compact('data', 'pmb', 'prodi'));
			if($type === 'kartu') return view('pmb.peserta.kartu', compact('data', 'pmb', 'prodi'));
			
			return view('pmb.peserta.error', 
			[
			'error' => 'not_found'
			]);
			
		}
		
		public function index($id)
		{
			$pmb = Pmb::find($id);
			$peserta = $pmb -> peserta;
			return view('pmb.peserta.index', compact('pmb', 'peserta'));
		}
		
		public function exportTo($id, $format='excel')
		{
			$pmb = Pmb::find($id);
			$data = PmbPeserta::where('pmb_id', $id) -> get();
			$rdata = compact('pmb', 'data');
			$title = 'rekap-pmb-online-' . str_slug($pmb -> nama) . '-' . date('Y-m-d H-i-s');
			
			if($format == 'excel')
			{				
				return \Excel::download(new \Siakad\Exports\DataExport('pmb.export_tpl', $rdata), $title . '.xlsx');
			}
			
			/* if($format == 'pdf')
				{
				return \PDF::loadView('pmb.excel', $this->d) -> setOrientation('landscape') -> download('rekap-pmb-online-' . date('Y-m-d H-i-s') . '.pdf');
			} */
		}
		
		public function graph()
		{
			
			if(null !== $request -> get('p')){
				$tahun = $request -> get('p');
			}
			else
			{
				$periode_aktif = \Siakad\Config::whereModule('pmb') -> whereAttribute('tahun') -> first();
				$tahun = $periode_aktif -> value;
			}
			
			$prodi = PmbPeserta::select(\DB::raw('jurusan, count(jurusan) as jumlah')) -> wherePeriode($tahun) -> groupBy('jurusan') -> get();
			$jk = PmbPeserta::select(\DB::raw('jenisKelamin, count(jenisKelamin) as jumlah')) -> wherePeriode($tahun) -> groupBy('jenisKelamin') -> orderBy('jenisKelamin', 'DESC') -> get();
			$pend = PmbPeserta::select(\DB::raw('sekolahAsal, count(sekolahAsal) as jumlah')) -> wherePeriode($tahun) -> groupBy('sekolahAsal') -> get();
			$lulus = PmbPeserta::select(\DB::raw('thLulus AS tahunLulus, count(thLulus) as jumlah')) -> wherePeriode($tahun) -> groupBy('tahunLulus') -> get();
			
			$periode = PmbPeserta::distinct('periode') -> get(['periode']);
			
			return view('pmb.peserta.graph', compact('prodi', 'jk', 'pend', 'lulus', 'tahun', 'periode'));
		}
		
		private function check($data = null, $ip, $key = null, $nik=null)
		{
			$date = date('Y-m-d');
			$pmb = ($data == null) ? Pmb::whereRaw($date . ' BETWEEN mulai AND selesai') -> first() : $data;
			if(!$pmb or $pmb == null) return view('pmb.peserta.error', ['error' => null]) -> render();
			
			//check waktu
			$today = strtotime($date);
			if($today < strtotime($pmb -> mulai . ' 00:00:00') or $today > strtotime($pmb -> selesai . ' 23:59:59'))  return view('pmb.peserta.error', 
			['error' => null, 'message' => 'Pendaftaran Mahasiswa Baru dibuka mulai tanggal ' . formatTanggal($pmb -> mulai) . ' - ' . formatTanggal($pmb -> selesai)]) -> render();
			
			//check NIK
			if($nik != null)
			{
				$nik = PmbPeserta::where('pmb_id', $pmb -> id) -> where('noKtp', $nik) -> first();
				if($nik !== null and ($key == null or $key !== csrf_token())) 
				return view('pmb.peserta.error', 
				[
				'message' => 'NIK sudah terdaftar di data kami atas nama: <strong>' . $nik -> nama . '</strong>. ',
				'error' => 'NIK'
				]) 
				-> render(); 
			}
			
			//check IP
			/*
				$db = PmbPeserta::where('pmb_id', $pmb -> id) -> where('ipAddr', $ip) -> orderBy('created_at', 'desc') -> first();
				if($db !== null and ($key == null or $key !== csrf_token())) 
				return view('pmb.peserta.error', 
				[
				'message' => 'Alamat IP anda sudah terdaftar di data kami atas nama: <strong>' . $db -> nama . '</strong>. ',
				'error' => 'ip'
				]) 
				-> render(); 
			*/
			
			
			//check kuota
			/* 
				$pendaftar = $pmb -> peserta -> count();
				if(($pendaftar + 1) > $pmb -> kuota) return view('pmb.peserta.error', 
				['message' => 'Kuota Pendaftaran Mahasiswa Baru pada Gelombang ini sudah terpenuhi.']) -> render(); 
			*/
			
			return null;
		}
		
		public function create(Request $request)
		{		
			$pmb = Pmb::whereBuka('y') -> first();
			$key = $request -> get('key');
			
			$check = $this -> check($pmb, $request -> ip(), $key);
			if($check !== null) return $check;
			
			$jalur = explode(',', $pmb -> jalur) == null ? [] : explode(',', $pmb -> jalur);
			$tujuan = explode(',', $pmb -> tujuan) == null ? [] : explode(',', $pmb -> tujuan);
			$kelas = explode(',', $pmb -> kelas) == null ? [] : explode(',', $pmb -> kelas);
			
			foreach(\Siakad\Prodi::orderBy('nama') -> get() as $p) $prodi[$p -> id] = $p -> nama . ' (' . $p -> singkatan . ')';
			
			$today = strtotime(date('Y-m-d'));
			$mulai = strtotime($pmb -> mulai);
			$selesai = strtotime($pmb -> selesai);
			
			return view('pmb.peserta.create', compact('pmb', 'tujuan', 'prodi', 'kelas', 'jalur'));
		}
		
		private function saveImage($file, $w, $h)
		{
			
			$date = date('Y/m/');
			$path = storage_path('app/upload/images/') . $date;
			if(!is_dir($path)) mkdir($path, 0777, true);
			
			$filename = str_random(9) . '.' . strtolower($file->getClientOriginalExtension());
			
			//Imagine
			$size = new Box($w, $h);
			$image = Imagine::open($file);
			$result = $image
			-> thumbnail($size, ImageInterface::THUMBNAIL_OUTBOUND)
			-> save($path . $filename);
			
			if($result)
			{ 
				return $date . $filename;
			}
			return false;			
		}
		public function store(Request $request)
		{
			$check = $this -> check(null, $request -> ip(), $request -> get('key'), $request -> get('noKtp'));
			if($check !== null) return $check;
			// if($request -> ip() != '127.0.0.1') $this -> rules['g-recaptcha-response'] = ['required', 'captcha'];
			
			$this ->rules['foto'] = 'required|image|max:2048';
			$this ->rules['slip'] = 'required|image|max:2048';
			
			$this -> validate($request, $this -> rules);
			$input = array_except($request -> all(), ['_token','key']);
			
			//FOTO
			$foto = $this -> saveImage($input['foto'], 300, 400);
			if($foto != false) $input['foto'] = $foto;
			else unset($input['foto']);
			
			$slip = $this -> saveImage($input['slip'], 600, 400);
			if($slip != false) $input['slip'] = $slip;
			else unset($input['slip']);
			
			/* $pmb = Pmb::whereBuka('y') -> first(); */
			
			/* $exists = PmbPeserta::where('nama', $input['nama'])
				-> where('pmb_id', $pmb -> id)
				-> where('telpMhs', $input['telpMhs'])
				-> where('noKtp', $input['noKtp'])
				-> first();
				
			if(isset($exists)) return redirect('/pmb/formulir/exist/' . $exists -> noPendaftaran);	 */	
			
			if(isset($input['p-lain-ayah']) and $input['p-lain-ayah'] != '') 
			{
				$input['pekerjaanAyah'] = $input['p-lain-ayah'];
				unset($input['p-lain-ayah']);				
			}
			if(isset($input['p-lain-ibu']) and $input['p-lain-ibu'] != '') 
			{
				$input['pekerjaanIbu'] = $input['p-lain-ibu'];
				unset($input['p-lain-ibu']);				
			}
			
			$maxid = \DB::select('select MAX(noPendaftaran) as maxid from `pmb_peserta`  WHERE `pmb_id` = ' . $input['pmb_id']);
			if(intval($maxid[0]->maxid) < 1) $maxid = 1; else $maxid = $maxid[0]->maxid + 1;
			$noPendaftaran = str_pad($maxid, 4, "0", STR_PAD_LEFT);
			$input['kode'] = str_random(6);
			$input['noPendaftaran'] = $noPendaftaran;
			$input['ipAddr'] = $request -> ip();
			$input['UA'] = $request -> header('user-agent');
			
			PmbPeserta::create($input);
			
			//send email
			$this -> dispatch(new SendPmbNotificationEmail($noPendaftaran));
			// $this -> sendEmail($noPendaftaran);
			
			return Redirect::route('pmb.peserta.stored', $input['kode']) -> with('message', 'Proses pendaftaran berhasil.');
		}
		
		public function stored($kode)
		{
			$data = PmbPeserta::whereKode($kode) -> first();	
			if(!$data) abort(404);
			$pmb = Pmb::find($data -> pmb_id);
			return view('pmb.peserta.stored', compact('data', 'pmb'));
		}
		
		public function show($no_pendaftaran)
		{
			$configs = Config::whereModule('pmb') -> get();
			foreach($configs as $conf)
			{
				if($conf -> attribute == 'tahun') $tmp['tahun'] = $conf -> value;
				if($conf -> attribute == 'status') $tmp['status'] = $conf -> value;
				if($conf -> attribute == 'mulai') $tmp['mulai'] = $conf -> value;
				if($conf -> attribute == 'selesai') $tmp['selesai'] = $conf -> value;
				if($conf -> attribute == 'kuota-1') $tmp['kuota-1'] = $conf -> value;
				if($conf -> attribute == 'kuota-2') $tmp['kuota-2'] = $conf -> value;
				if($conf -> attribute == 'kuota-3') $tmp['kuota-3'] = $conf -> value;
				if($conf -> attribute == 'syarat') $tmp['syarat'] = $conf -> value;
			}
			$data = PmbPeserta::where('noPendaftaran', $no_pendaftaran) -> first();
			if(!$data) abort(404);
			return view('pmb.peserta.show', compact('data', 'tmp'));
		}	
		
		public function edit($pmb_id, $kode)
		{
			$pmb = Pmb::find($pmb_id);
			$mahasiswa = PmbPeserta::where('kode', $kode) -> first();
			
			$jalur = explode(',', $pmb -> jalur) == null ? [] : explode(',', $pmb -> jalur);
			$tujuan = explode(',', $pmb -> tujuan) == null ? [] : explode(',', $pmb -> tujuan);
			$kelas = explode(',', $pmb -> kelas) == null ? [] : explode(',', $pmb -> kelas);
			
			foreach(\Siakad\Prodi::orderBy('nama') -> get() as $p) $prodi[$p -> id] = $p -> nama . ' (' . $p -> singkatan . ')';
			
			return view('pmb.peserta.edit', compact('pmb', 'mahasiswa', 'tujuan', 'kelas', 'prodi', 'jalur'));		
		}	
		
		public function update(Request $request, $pmb_id, $kode)
		{
			$this -> validate($request, $this -> rules);
			$input =$request -> except('_token', 'key', 'foto_stored', 'slip_stored');
			
			$mahasiswa = PmbPeserta::where('kode', $kode) -> first();
			
			//IMAGE FILE
			if(isset($input['foto']) && $input['foto'] != '')
			{
				$foto = $this -> saveImage($input['foto'], 300, 400);
				if($foto != false) $input['foto'] = $foto;
			}
			elseif(false !== $request -> get('foto_stored')) $input['foto'] = $request -> get('foto_stored');
			
			if(isset($input['slip']) && $input['slip'] != '')
			{
				$slip = $this -> saveImage($input['slip'], 600, 400);
				if($slip != false) $input['slip'] = $slip;
			}
			elseif(false !== $request -> get('slip_stored')) $input['slip'] = $request -> get('slip_stored');
			
			if(isset($input['p-lain-ayah']) and $input['p-lain-ayah'] != '') 
			{
				$input['pekerjaanAyah'] = $input['p-lain-ayah'];
				unset($input['p-lain-ayah']);				
			}
			if(isset($input['p-lain-ibu']) and $input['p-lain-ibu'] != '') 
			{
				$input['pekerjaanIbu'] = $input['p-lain-ibu'];
				unset($input['p-lain-ibu']);				
			}
			
			$mahasiswa -> update($input);
			
			return Redirect::route('pmb.peserta.index', $pmb_id) -> with('success', 'Data Calon Mahasiswa '. $input['nama'] .' berhasil diperbarui.');
		}	
		
	}
