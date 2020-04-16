<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Skripsi;
	use Siakad\PengajuanSkripsi;
	use Siakad\Mahasiswa;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class SkripsiController extends Controller
	{
		use \Siakad\SkripsiTrait;
		
		protected $rules = [
		'judul' => ['required', 'min:30'],
		];
		
		protected $jenis = ['proposal' => 0, 'komprehensif' => 1];
		
		function __construct()
		{
			$this -> cache = 'skripsi_tmp_' . csrf_token();
		}
		
/* 		public function fixPengajuanBelumTabelMasuk()
		{
			$mahasiswa = \DB::select("
			select mahasiswa.id, skripsi_id, pengajuan_skripsi.judul, diterima, dosen_id from mahasiswa
			inner join pengajuan_skripsi on pengajuan_skripsi.mahasiswa_id = mahasiswa.id
			where diterima = 'y' and (skripsi_id = '' or skripsi_id IS NULL or skripsi_id = 0)
			");
			$f=0;
			$aktif = \Siakad\Tapel::whereAktif('y') -> first();
			foreach($mahasiswa as $m)
			{
				$skripsi = Skripsi::create(['judul' => $m -> judul]);
				Mahasiswa::find($m -> id) -> update(['skripsi_id' => $skripsi -> id]);
				
				if($m -> dosen_id > 0)
				{
					\Siakad\DosenSkripsi::where('skripsi_id', $skripsi -> id) -> delete();
					\Siakad\DosenSkripsi::create(['dosen_id' => $m->dosen_id, 'skripsi_id' => $skripsi -> id, 'tapel_id' => $aktif -> id]);
				}
				
				$f++;
			}
			return $f . ' data telah diperbaiki';
		} */
		
		public function revisi($skripsi_id)
		{
			$skripsi = Skripsi::find($skripsi_id);
			if(!$skripsi) return Redirect::back() -> withErrors(['NOT_FOUND' => 'Skripsi tidak ditemukan. Harap hubungi Admin untuk perbaikan data.']);
			$mahasiswa = $skripsi -> pengarang;
		
		return view('mahasiswa.skripsi.revisi', compact('skripsi', 'mahasiswa'));
		}
		
		private function cleanUnusedSkripsi()
		{
			$deleted = Skripsi::whereRaw('id NOT IN (select skripsi_id from mahasiswa where skripsi_id IS NOT NULL)') -> delete();	
			
			if($deleted > 0) \Cache::forget('id_judul_skripsi');
		}
	
	public function revisiPost(Request $request, $skripsi_id)
	{
	$mahasiswa = \Auth::user() -> authable;
	
	$pengajuan = PengajuanSkripsi::where('diterima', 'y') -> where('mahasiswa_id', $mahasiswa -> id) -> first();
	if(!$pengajuan) abort(404);
	
	$revisi = $request -> get('revisi');
	
	//clean Skripsi Table
	$this -> cleanUnusedSkripsi();
	
	$similarity = $this -> similarity($revisi, $skripsi_id);	
	
	
	$forced = false != $request -> get('force');
	$rejected = intval($similarity['sim'][0]) > intval($pengajuan -> gelombang  -> jadwal -> max_similarity);
	
	$data_p = [
	'judul_revisi' => $revisi,
	'similarity2' => $similarity['sim'][0],
	'similarity2_id' => $similarity['sim'][1],
	'similarity2_array' => $similarity['sim_array'],
	'validator' => 'D'
	];
	$data_s = [
	// 'judul' => $revisi,
	'flag_bimbingan' => 'n',
	];
	
	if(!$forced && $rejected)
	{
	return Redirect::back() -> with('warning_raw', 'Pemeriksaan oleh sistem bahwa Revisi Judul Anda 
	<strong>"' . $revisi . '"</strong> 
	<br><span class="label bg-blue"> MEMILIKI KEMIRIPAN MELEBIHI BATAS </span> yang diperbolehkan dengan judul Skripsi yang telah ada. 
	<br><span class="label bg-red"> MOHON PERIKSA KEMBALI Revisi Judul Anda.</span><br/> 
	<br> Jika Anda tetap ingin<strong> MEMAKSA </strong>mengirimkan Revisi Judul tersebut <br/> 
	<a href="'. route('skripsi.revisi.post', $skripsi_id) .'?revisi='. $revisi .'&force=true" class="btn btn-danger btn-flat btn-xs">
	<i class="fa fa-exclamation-triangle"></i> Klik disini.</a> TAPI JUDUL ANDA AKAN KETOLAK!!');
	}
	elseif($forced && $rejected)
	{
	$data_p['diterima'] = 'n';
	$data_p['diterima_dosen'] = 'n';
	$data_p['keterangan'] = 'Revisi Judul ditolak sistem';
	
	//Hapus Data skripsi
	$data_s = [];
	Skripsi::find($skripsi_id) -> delete();
	}
	else
	{
	$data_p['diterima_dosen'] = 'p';
	$data_p['keterangan'] = 'Revisi Judul menunggu pemeriksaan Dosen Pembimbing';
	}
	
	PengajuanSkripsi::find($pengajuan -> id) -> update($data_p);
	if(count($data_s) > 0) Skripsi::find($skripsi_id) -> update($data_s);
	
	return Redirect::to('/skripsi/pengajuan') -> with('success', 'Data Revisi telah disimpan');
	}
	
	public function pendaftaran($id, $jenis)
	{
	$auth = \Auth::user();
	if($auth -> role_id == 512) 
	{
	$mahasiswa = $auth -> authable;
	if($mahasiswa -> statusMhs != 1) return Redirect::back() -> withErrors(['Status Mahasiswa Tidak Aktif.']);
	$jadwal_ujian = \Siakad\PesertaUjianSkripsi::jadwalUjian($mahasiswa -> id, $this -> jenis[$jenis]) -> first();
	
	if($jadwal_ujian)
	{
	return view('mahasiswa.skripsi.pendaftaran.jadwal', compact('jadwal_ujian', 'jenis', 'mahasiswa'));					
	}
	
	$skripsi = Skripsi::find($mahasiswa -> skripsi_id);
	
	if(!$skripsi) return Redirect::back() -> with('warning', ' Data Skripsi tidak ditemukan');
	
	$smt = $jenis == 'proposal' ? 6 : 7;
	$tanggungan = [
	'keu' => 'Tidak mempunyai tanggungan Keuangan',
	'daf' => 'Sudah daftar Wisuda',
	'krs' => 'Sudah melakukan KRS',
	'sks' => 'Tidak mempunyai tanggungan SKS',
	'nil' => 'Tidak mempunyai tanggungan nilai semester 1-' . $smt,
	'val' => 'Sudah di validasi Dosen Pembimbing',
	'cet' => 'Cetak validasi Dosen Pembimbing',
	'frm' => 'Cetak Form Pendaftaran'
	];	
	
	if($jenis == 'proposal') unset($tanggungan['keu']);
	
	$invalid = $this -> checkTanggunganSkripsi($mahasiswa, $skripsi, $jenis);
	
	return view('mahasiswa.skripsi.pendaftaran.syarat', compact('skripsi', 'jenis', 'tanggungan', 'invalid'));
	}	
	
	abort(404);
	}
	
	public function cetakPendaftaran($id, $jenis)
	{
	$skripsi = Skripsi::find($id);
	if(!$skripsi) return Redirect::back() -> with('warning', ' Data Skripsi tidak ditemukan');
	
	$mahasiswa = $skripsi -> pengarang;
	if($mahasiswa -> statusMhs != 1) return Redirect::back() -> withErrors(['Status Mahasiswa Tidak Aktif.']);
	
	if(\Auth::user() -> role_id == 512) 
	{
	if(count($this -> checkTanggunganSkripsi($mahasiswa, $skripsi, $jenis)) > 0) return Redirect::route('skripsi.ujian.pendaftaran', [$id, $jenis]);
	}
	
	$alamat = '';
	if($mahasiswa['jalan'] != '') $alamat .= 'Jl. ' . $mahasiswa['jalan'] . ' ';
	if($mahasiswa['dusun'] != '') $alamat .= $mahasiswa['dusun'] . ' ';
	if($mahasiswa['rt'] != '') $alamat .= 'RT ' . $mahasiswa['rt'] . ' ';
	if($mahasiswa['rw'] != '') $alamat .= 'RW ' . $mahasiswa['rw'] . ' ';
	if($mahasiswa['kelurahan'] != '') $alamat .= $mahasiswa['kelurahan'] . ' ';
	if($mahasiswa['id_wil'] != '') 
	{
	$data = \Siakad\Wilayah::dataKecamatan($mahasiswa['id_wil']) -> first();
	if($data)
	$alamat .= trim($data -> kec) . ' ' . trim($data -> kab) . ' ';
	}
	if($mahasiswa['kodePos'] != '') $alamat .= $mahasiswa['kodePos'];
	
	$total_sks = \Siakad\Aktivitas::where('mahasiswa_id', $mahasiswa -> id) -> where('semester', $mahasiswa -> semesterMhs) -> pluck('total_sks');
	
	$data = \Siakad\PesertaUjianSkripsi::where('mahasiswa_id', $mahasiswa -> id) -> first();
	if(!$data)
	{
	// \Siakad\PesertaUjianSkripsi::where('mahasiswa_id', $mahasiswa -> id) -> delete();			
	$gelombang = \Siakad\JadwalUjianSkripsiGelombang::selectGelombangBuka($this -> jenis[$jenis], $mahasiswa -> prodi_id) -> first();
	if(!$gelombang) return Redirect::back() -> with('warning', 'Maaf, Gelombang Pendaftaran belum dibuka');
	
	$datap = [
	'jusg_id' => $gelombang ->id, 
	'mahasiswa_id' => $mahasiswa -> id				
	];
	$pembimbing = $skripsi -> pembimbing;
	if($pembimbing)
	{
	$datap['ketua'] = $pembimbing[0] -> id;
	// $datap['sekretaris'] = $pembimbing[0] -> id;
	}
	\Siakad\PesertaUjianSkripsi::create($datap);
	}
	
	if($jenis == 'proposal')
	{
	//mark as daftar
	$skripsi -> update(['cetak_form_proposal' => 'y']); //can be deleted??
	return view('mahasiswa.skripsi.pendaftaran.proposal', compact('skripsi', 'jenis', 'mahasiswa', 'alamat', 'total_sks'));
	}
	
	elseif($jenis == 'komprehensif')
	{
	//mark as daftar
	$skripsi -> update(['cetak_form_kompre' => 'y']);
	return view('mahasiswa.skripsi.pendaftaran.kompre', compact('skripsi', 'jenis', 'mahasiswa', 'alamat'));
	}
	}
	
	// tanggungan
	private function checkTanggunganSkripsi($mahasiswa, $skripsi, $jenis)
	{
	$invalid = [];
	
	//Biaya Kelulusan
	if($jenis == 'proposal')
	{
	$biaya = [22]; // PPL & PKM					
	if($this -> getTanggunganKeuangan($mahasiswa, $biaya)) $invalid['keu'] = 1;
	}
	else //kompre
	{
	$biaya = [20, 22, 24]; // PPL & PKM			
	if($this -> getTanggunganKeuangan($mahasiswa, $biaya)) $invalid['daf'] = 1;	
	}
	
	//Biaya SELAIN Kelulusan
	if($jenis == 'komprehensif')
	{
	$jenis_biaya = \Siakad\JenisBiaya::pluck('id') ->toArray();
	$jenis_biaya = array_diff($jenis_biaya, $biaya);
	if($this -> getTanggunganKeuangan($mahasiswa, $jenis_biaya)) $invalid['keu'] = 1;
	}
	
	//SKS & Nilai
	$smt = $jenis == 'proposal' ? 6 : 7;
	
	if(!cekTanggungan($mahasiswa -> id, 'sks', $smt)) $invalid['sks'] = 1;			
	if(!cekTanggungan($mahasiswa -> id, 'nil', $smt)) $invalid['nil'] = 1;
	
	if($jenis == 'komprehensif')
	{
	if(!cekTanggungan($mahasiswa -> id, 'krs', 8)) $invalid['krs'] = 1;
	}
	
	//Validasi
	if($jenis == 'proposal' && $skripsi -> validasi_proposal != 'y') 
	{
	$invalid['val'] = 1; 
	$invalid['cet'] = 1;
	$invalid['frm'] = 1;
	}
	elseif($jenis == 'komprehensif' && $skripsi -> validasi_kompre != 'y')
	{
	$invalid['val'] = 1; 
	$invalid['cet'] = 1; 
	$invalid['frm'] = 1;
	}
	
	return $invalid;
	}
	
	private function getTanggunganKeuangan($mahasiswa, $jbiaya)
	{
	if(!is_array($jbiaya)) return false;
	
	$tagihan = \Siakad\Tagihan::join('setup_biaya', 'setup_biaya.id', '=', 'setup_biaya_id')
	-> where('mahasiswa_id', $mahasiswa -> id)
	-> where('angkatan', $mahasiswa -> angkatan)
	-> where('prodi_id', $mahasiswa -> prodi_id)
	-> where('kelas_id', $mahasiswa -> kelasMhs)
	-> where('jenisPembayaran', $mahasiswa -> jenisPembayaran)
	-> get([
	'jenis_biaya_id', 
	'tagihan.id', 'tagihan.jumlah', 'tagihan.bayar', 'tagihan.privilege'
	]);
	if(!$tagihan) return false;
	
	$tanggungan = 0;
	$bayar = 0;
	foreach($tagihan as $t)
	{
	// if(in_array($t -> jenis_biaya_id, $jbiaya) && $t -> privilege != 'y')				
	if(in_array($t -> jenis_biaya_id, $jbiaya))
	{
	$tanggungan +=  $t -> jumlah;
	$bayar +=  $t -> bayar;
	}
	}
	if($tanggungan - $bayar <= 0) return false;
	
	return true;
	}
	
	public function validasi($id, $jenis)
	{
	$skripsi = Skripsi::find($id);
	if(!$skripsi) abort(404);
	
	$tapel_aktif = \Siakad\Tapel::whereAktif('y') -> first();
	
	$tgl = date('d-m-Y');
	if($jenis == 'proposal') $data = ['validasi_proposal' => 'y', 'tgl_validasi_proposal' => $tgl, 'tapel_validasi_kompre' => $tapel_aktif -> id];
	elseif($jenis == 'komprehensif') $data = ['validasi_kompre' => 'y', 'tgl_validasi_kompre' => $tgl, 'tapel_validasi_kompre' => $tapel_aktif -> id];
	else abort(404);
	
	$skripsi -> update($data);
	return Redirect::back() -> with('message', 'Validasi berhasil');
	}
	
	public function downloadFile($id) 
	{
	$skripsi = Skripsi::whereId($id) -> first();
	if(!isset($skripsi -> file) or $skripsi -> file == '') abort(404);
	$storage = \Storage::disk('files');	
	if(!$storage -> exists($skripsi -> file)) abort(404);
	$filename = str_slug($skripsi -> judul) . '.' . $skripsi -> ext;
	return \Response::download($storage -> getDriver() -> getAdapter() -> getPathPrefix() . $skripsi -> file, $filename, [$skripsi -> mime]);
	}
	
	public function show($id)
	{
	$skripsi = Skripsi::with('bimbingan.author') -> find($id);
	if(!$skripsi) abort(404);
	return view('mahasiswa.skripsi.show', compact('skripsi'));
	}
	
	/**
	* Search
	**/
	public function search(Request $request)
	{			
	$q = $request -> get('q');
	
	$auth = \Auth::user();
	$prodi = ($auth -> role -> name == 'Prodi') ? $auth -> role -> sub : null;
	
	$skripsi = Skripsi::getList($prodi, $q) 
	-> with('pembimbing') 
	// -> search($query) 
	-> paginate(30);
	$message = 'Ditemukan ' . $skripsi -> total() . ' hasil pencarian';
	
	return view('mahasiswa.skripsi.index', compact('skripsi', 'message'));
	}
	public function index()
	{
	$user = \Auth::user();
	
	// 27062019 - mahasiswa
	if($user -> role_id == 512)
	{
	$mahasiswa = $user -> authable;	
	if(!$mahasiswa -> skripsi) return Redirect::back() -> withErrors(['NOT_FOUND' => 'Data Skripsi tidak ditemukan']);
	return view('mahasiswa.skripsi.my', compact('mahasiswa'));
	}
	
	$prodi = ($user -> role -> name == 'Prodi') ? $user -> role -> sub : null;
	
	$skripsi = Skripsi::getList($prodi) -> with('pembimbing') -> paginate(30);
	
	return view('mahasiswa.skripsi.index', compact('skripsi'));
	}
	
	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create()
	{
	$tmp = \Cache::get($this -> cache);
	$tmp2[0] = '-';
	
	$tmp3 = \Siakad\Dosen::where('id', '>=', 0) -> orderBy('nama') -> get();
	foreach($tmp3 as $d) $dosen[$d -> id] = $d -> gelar_depan . ' ' . $d -> nama . ' ' . $d -> gelar_belakang;
	
	
	$mahasiswa = Mahasiswa::where(function($q){
	$q 
	-> where('skripsi_id', 0) 
	-> orWhereNull('skripsi_id');
	}) 
	-> where('semesterMhs', '>=', 7) 
	-> orderBy('nama') 
	-> get();
	foreach($mahasiswa as $m) $tmp2[$m -> id] = $m -> nama . ' - ' . $m -> NIM;
	$mahasiswa = $tmp2;
	return view('mahasiswa.skripsi.create', compact('tmp', 'dosen', 'mahasiswa'));
	}
	
	public function store_tmp(Request $request)
	{
	$input = $request -> except(['_token']);
	$existing = \Cache::get($this -> cache, []);
	
	$data[$input['mahasiswa_id']] = $input;
	
	$new = $existing + $data;
	\Cache::put($this -> cache, $new, 30);
	return \Response::json($new);
	}
	public function remove_tmp()
	{
	\Cache::forget($this -> cache);
	return Redirect::route('skripsi.create') -> with('success', 'Data berhasil dihapus.');
	}
	public function destroy_tmp($id)
	{
	$existing = \Cache::get($this -> cache);
	$new = array_except($existing, [$id]);
	if(count($new) < 1) $new = [];
	\Cache::put($this -> cache, $new, 30);
	return \Response::json($new);
	}
	
	/**
	* Store a newly created resource in storage.
	*
	// * @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
	public function store(Request $request)
	{
	$data = \Cache::get($this -> cache);
	$c = $e = 0;
	if(count($data) < 1) return Redirect::route('skripsi.create') -> with('warning', 'Data belum diisi.');
	
	foreach($data as $d)
	{
	$skripsi = Skripsi::create(['judul' => $d['judul']]);
	if($skripsi)
	{
	$mahasiswa = \Siakad\Mahasiswa::find($d['mahasiswa_id']);
	if($mahasiswa)
	{
	$result = $mahasiswa -> update(['skripsi_id' => $skripsi -> id]);	
	if($result)
	{
	
	if($d['dosen1_id'] > 0)
	{
	$bimbingan = \Siakad\DosenSkripsi::create(['dosen_id' => $d['dosen1_id'], 'skripsi_id' => $skripsi -> id]);
	if($bimbingan) $c++;
	}
	
	if($d['dosen2_id'] > 0 && $d['dosen2_id'] != $d['dosen1_id'])
	{
	$bimbingan = \Siakad\DosenSkripsi::create(['dosen_id' => $d['dosen2_id'], 'skripsi_id' => $skripsi -> id]);
	if($bimbingan) $c++;
	}
	}
	else $e++;
	}
	else $e++;
	}
	else $e++;
	}
	\Cache::forget($this -> cache);
	return Redirect::route('skripsi.index') -> with('success', $c . ' data Skripsi berhasil dimasukkan.');
	}
	
	/**
	* Show the form for editing the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function edit($id)
	{
	$skripsi = Skripsi::find($id);
	$admin = true;
	$tmp3 = \Siakad\Dosen::where('id', '>=', 0) -> orderBy('nama') -> get();
	foreach($tmp3 as $d) $dosen[$d -> id] = $d -> gelar_depan . ' ' . $d -> nama . ' ' . $d -> gelar_belakang;
	
	$user = \Auth::user();
	if($user -> role_id > 128)
	{
	$skripsi = $user -> authable -> skripsi;
	$admin = false;
	$dosen = [];
	}
	
	$mahasiswa = $skripsi -> pengarang;
	$pembimbing = $skripsi -> pembimbing;
	
	if(isset($pembimbing[0])) $skripsi -> pembimbing1 = $pembimbing[0] -> id;
	if(isset($pembimbing[1])) $skripsi -> pembimbing2 = $pembimbing[1] -> id;
	
	return view('mahasiswa.skripsi.edit', compact('skripsi', 'mahasiswa', 'dosen', 'admin'));
	}
	
	/**
	* Update the specified resource in storage.
	*
	// * @param  \Illuminate\Http\Request  $request
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, $id)
	{
	$input = $request -> except('_method', 'pembimbing1', 'pembimbing2');
	
	$file = $input['softcopy'];
	if(isset($file) and $file != '')
	{
	$softcopy = true;
	$validator = \Validator::make($input, ['softcopy' => 'mimes:pdf,doc,docx']);
	if($validator -> fails())
	{
	$softcopy = false;
	}
	else
	{
	$date = date('Y/m/d/');
	$filename = str_random(9);		
	$storage = \Storage::disk('files');
	$result = $storage -> put($date . $filename, \File::get($file));
	if(!$result)
	{
	$softcopy = false;
	}
	
	$input['file'] = $date . $filename;
	$input['mime'] = $file -> getClientMimeType();
	$input['ext'] = $file -> getClientOriginalExtension();
	}
	}
	unset($input['softcopy']);
	
	$p1 = $request -> get('pembimbing1');
	$p2 = $request -> get('pembimbing2');
	
	if($p1 > 0)
	{
	\Siakad\DosenSkripsi::where('skripsi_id', $id) -> delete();
	\Siakad\DosenSkripsi::create(['dosen_id' => $p1, 'skripsi_id' => $id]);
	}
	
	if($p2 > 0 && $p2 != $p1)
	{
	\Siakad\DosenSkripsi::create(['dosen_id' => $p2, 'skripsi_id' => $id]);
	}
	
	Skripsi::find($id) -> update($input);		
	
	return Redirect::route('skripsi.index') -> with('success', 'Data Skripsi berhasil diperbarui.');
	}
	
	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function destroy($id)
	{
	Skripsi::find($id) -> delete();
	\Siakad\Mahasiswa::where('skripsi_id', $id) -> update(['skripsi_id' => 0]);
	return Redirect::route('skripsi.index') -> with('success', 'Data Skripsi berhasil dihapus.');
	}
	}
		