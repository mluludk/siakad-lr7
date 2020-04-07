<?php
	
	namespace Siakad\Http\Controllers;
	
	use Cache;
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Ppl;
	use Siakad\Nilai;
	use Siakad\PplLokasi;
	use Siakad\Mahasiswa;
	use Siakad\PplLokasiDosen;
	use Siakad\MahasiswaPplLokasi;
	
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class MahasiswaPplLokasiController extends Controller
	{
		use \Siakad\SkalaTrait;
		
		public function status($mahasiswa_id)
		{
			$status = MahasiswaPplLokasi::status($mahasiswa_id) -> first();
			$mahasiswa = Mahasiswa::find($mahasiswa_id);
			$auth = \Auth::user();
			
			return view('mahasiswa.ppl.status', compact('mahasiswa', 'status', 'auth'));
		}
		
		public function index($id, $print=null)
		{
			$user = \Auth::user();
			
			$ppl = Ppl::daftarPpl(null, $id) -> first();
			
			$skala_o = $this -> skala($ppl -> prodi_id);
			$tmp['-'] = '-';
			foreach($skala_o as $h => $v) $tmp[$h] = $h;
			$skala = $tmp;
			
			$peserta = MahasiswaPplLokasi::peserta($id) -> get();
			
			$tbl = [];
			foreach($peserta as $p)
			{
				$tbl[$p -> ppl_lokasi_id][] = $p;
			}
			$peserta = $tbl;
			
			$pendamping = PplLokasiDosen::pendampingPpl($id) -> get();
			
			$pd = [];
			foreach($pendamping as $p)
			{
				$pd[$p -> ppl_lokasi_id][$p -> dosen_id] = $p -> gelar_depan . ' ' . $p -> nama . ' ' . $p -> gelar_belakang;
			}
			$pendamping = $pd;
			
			if($print == 'print')
			return view('mahasiswa.ppl.lokasi.peserta.print', compact('ppl', 'peserta', 'pendamping', 'user'));
			else
			return view('mahasiswa.ppl.lokasi.peserta.index', compact('ppl', 'peserta', 'pendamping', 'skala', 'skala_o', 'user'));
		}
		
		public function storeNilai(Request $request, $id)
		{
			$input = $request -> all();
			
		$nilai = explode('|', $input['nilai']);
		
		$return = ['success' => 0, 'message' => 'Unknown Error.'];
		$matkul_tapel = Ppl::getMatkulTapelId($input['matkul_id'], $input['mahasiswa_id']) -> first();
		
		if(!$matkul_tapel)
		{
		return ['success' => 0, 'message' => 'Mahasiswa belum melakukan KRS pada Mata Kuliah PPL.'];
		}
		
		//check KRS
		/* $krs = \Siakad\KrsDetail::where('matkul_tapel_id', $matkul_tapel -> id) -> first();
		if(!$krs)
		{
		$return = ['success' => 0, 'message' => 'Mahasiswa belum melakukan KRS pada Mata Kuliah PPL.'];
		} */
		
		//MahasiswaPplLokasi
		$nilai_ppl = MahasiswaPplLokasi::where('mahasiswa_id', $input['mahasiswa_id'])
		-> where('ppl_lokasi_id', $input['lokasi_id']);
		if(!$nilai_ppl -> exists())
		{
		$return = ['success' => 0, 'message' => 'Mahasiswa belum mendaftar PPL.'];
		}
		else
		{
		if($nilai_ppl -> update(['nilai' => $nilai[0], 'nilai_angka' => $nilai[1]]))
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
		$return = ['success' => 1, 'message' => 'Nilai PPL Mahasiswa '. $input['mahasiswa_nim'] .' telah diperbarui.'];
		}
		else
		{
		$return = ['success' => 0, 'message' => 'Transfer Nilai PPL gagal.'];
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
		$return = ['success' => 1, 'message' => 'Nilai PPL Mahasiswa '. $input['mahasiswa_nim'] .' telah disimpan.'];
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
		$return = ['success' => 1, 'message' => 'Nilai PPL Mahasiswa '. $input['mahasiswa_nim'] .' telah diperbarui.'];
		}
		else
		{
		$return = ['success' => 0, 'message' => 'Transfer Nilai PPL gagal.'];
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
		$return = ['success' => 1, 'message' => 'Nilai PPL Mahasiswa '. $input['mahasiswa_nim'] .' telah disimpan.'];
		}
		else
		{
		$return = ['success' => 0, 'message' => 'Data Nilai tidak ditemukan.'];
		}
		}
		}
		else
		{
		$return = ['success' => 0, 'message' => 'Gagal menyimpan Nilai PPL.'];
		}
		}
		
		return $return;
		}
		
		/**
		* Show the form for creating a new resource.
		*
		* @return \Illuminate\Http\Response
		*/
		public function create()
		{
		$show = $admin = false;
		$data = \Auth::user() -> authable;
		if($data == null or !$data) return Redirect::back() -> with('warning', 'Terjadi kesalahan pada Data Mahasiswa. Silahkan menghubungi Bagian Akademik.');
		
		if($data -> statusMhs != 1) return Redirect::to('home') -> withErrors(['ERROR' => 'Status Mahasiswa Non-Aktif']);
		
		$ppl = Ppl::where('prodi_id', $data -> prodi_id)
		-> whereRaw('date(now()) BETWEEN STR_TO_DATE(tgl_mulai_daftar, "%d-%m-%Y") AND STR_TO_DATE(tgl_selesai_daftar, "%d-%m-%Y")')
		-> first();
		
		if($ppl)
		{
		$lokasi = PplLokasi::lokasiList($ppl -> id) -> get();
		if($lokasi -> count() > 0)
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
		return view('mahasiswa.ppl.syarat', compact('tanggungan', 'invalid', 'data'));
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
		
		$lokasi_ppl_mhs = MahasiswaPplLokasi::where('mahasiswa_id', $data -> id) -> first();
		if($lokasi_ppl_mhs)
		{
		$show = true;
		}
		
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
		
		return view('mahasiswa.ppl.daftar', compact('data', 'wilayah', 'alamat',  'show', 'ppl', 'admin', 'negara', 'lokasi', 'lokasi_ppl_mhs'));
		}
		return Redirect::back() -> with('warning', 'Lokasi PPL belum ditentukan. Hubungi Admin / Bagian Akademik');
		}
		return Redirect::back() -> with('warning', 'Pendaftaran PPL belum dibuka.');
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
		
		if(MahasiswaPplLokasi::where('mahasiswa_id', $data -> id) -> exists())
		{
		return Redirect::back() -> with('warning', 'Anda sudah Terdaftar.');
		}
		else
		{
		$input = $request -> except('_method', 'ppl_lokasi_id', 'matkul_id');
		
		if(isset($input['kemampuan']) && is_array($input['kemampuan']))
		{
		$input['kemampuan'] = implode('[]', $input['kemampuan']);
		}
		if(isset($input['kekurangan']) && is_array($input['kekurangan']))
		{
		$input['kekurangan'] = implode('[]', $input['kekurangan']);
		}
		
		
		$lokasi['ppl_lokasi_id'] = $request -> get('ppl_lokasi_id');
		$lokasi['mahasiswa_id'] = $data -> id;
		
		//nilai
		$nilai = Ppl::getNilai($request -> get('matkul_id'), $data -> id) -> first();
		if($nilai) $lokasi['nilai'] = $nilai -> nilai;
		
		\Siakad\Mahasiswa::find($data -> id) -> update($input);
		
		
		MahasiswaPplLokasi::create($lokasi);
		
		
		return Redirect::back() -> with('success', 'Pendaftaran PPL sukses.');
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
		
		$lokasi = PplLokasi::lokasiDetail($lokasi_id) -> first();
		if(!$lokasi) return Redirect::back() -> with('warning', 'Maaf, Anda belum terdaftar mengikuti PPL.');
		
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
		
		return view('mahasiswa.ppl.printFormulir', compact('mahasiswa', 'lokasi', 'alamat', 'prodi'));
		}
		
		public function destroy($ppl_id, $ppl_lokasi_id, $mahasiswa_id)
		{
		MahasiswaPplLokasi::where('ppl_lokasi_id', $ppl_lokasi_id)
		-> where('mahasiswa_id', $mahasiswa_id) -> delete();
		
		return Redirect::route('mahasiswa.ppl.lokasi.peserta.index', $ppl_id) -> with('success', 'Peserta telah dihapus');
		}
		}
				