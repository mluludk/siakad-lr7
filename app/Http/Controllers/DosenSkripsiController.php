<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Skripsi;
	use Siakad\Dosen;
	use Siakad\Mahasiswa;
	use Siakad\DosenSkripsi;
	use Siakad\PengajuanSkripsi;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class DosenSkripsiController extends Controller
	{		
		use \Siakad\DosenTrait;
		use \Siakad\MahasiswaTrait;
		
		public function pembagianPembimbing()
		{
			$data = DosenSkripsi::pembagianPembimbing() -> get();
			$pembimbing = [];
			foreach($data as $d)
			{
				$pembimbing[substr($d -> semester, 0, 9)][$d -> id_dosen][] = [
				'id_dosen' => $d -> id_dosen,
				'dosen' => $d -> gelar_depan . ' ' . $d -> nama_dosen . ' ' . $d -> gelar_belakang,
				'nim' => $d -> NIM,
				'nama' => $d -> nama_mhs,
				'prodi' => $d -> singkatan,
				'judul' => $d -> judul,
				'sudah' => $d -> sudah
				];
			}
			return view('mahasiswa.skripsi.bimbingan.pembagian', compact('pembimbing'));
		}
		
		public function selesaiBimbingan()
		{
			$dosen_id = \Auth::user() -> authable_id;
			$selesai = DosenSkripsi::bimbingan($dosen_id, 'selesai') -> get();
			return view('mahasiswa.skripsi.bimbingan.selesai', compact('selesai'));
		}
		
		public function kandidatBimbingan()
		{
			$dosen_id = \Auth::user() -> authable_id;
			$kandidat = DosenSkripsi::bimbingan($dosen_id, 'kandidat') -> get();
			return view('mahasiswa.skripsi.bimbingan.kandidat', compact('kandidat'));
		}
		
		public function kandidatBimbinganAksi($skripsi_id, $aksi)
		{
			$mahasiswa = Mahasiswa::where('skripsi_id', $skripsi_id) -> first();
			if(!$mahasiswa) abort(404);
			
			$pengajuan = PengajuanSkripsi::where('mahasiswa_id', $mahasiswa -> id) -> where('diterima', 'y') -> first();
			if(!$pengajuan) abort(404);
			
			switch($aksi)
			{
				case 'revisi':
				$input = [
				'diterima_dosen' => 'r',
				'validator' => 'D',
				'keterangan' => 'Revisi Pengajuan Judul Skripsi.'
				];
				break;
				
				case 'bimbingan':
				$input = [
				'diterima_dosen' => 'y',
				'validator' => 'D',
				'keterangan' => 'Pengajuan Judul Skripsi telah diterima Dosen Pembimbing. Lanjutkan proses bimbingan Skripsi.'
				];
				$skripsi = Skripsi::find($mahasiswa -> skripsi_id) -> update(['judul' => $pengajuan -> judul_revisi]);
				break;
				
				default:
				$input = [
				'diterima_dosen' => 'n',
				'validator' => 'D',
				'keterangan' => 'Pengajuan Judul Skripsi ini ditolak oleh Dosen Pembimbing. Ulangi kembali proses pengajuan Judul Skripsi'
				];
				break;				
			}
			
			$result = $pengajuan -> update($input);
			
			if($result) return Redirect::route('skripsi.bimbingan.kandidat') -> with('success', 'Data telah disimpan');
			return Redirect::route('skripsi.bimbingan.kandidat') -> with('warning', 'Data gagal disimpan');
		}
		
		public function bimbingan()
		{
			$dosen_id = \Auth::user() -> authable_id;
			$bimbingan = DosenSkripsi::bimbingan($dosen_id) -> get();
			return view('mahasiswa.skripsi.bimbingan.index', compact('bimbingan'));
		}		
		
		private function refreshList($dosen_id, $angkatan_id)
		{
			$role = \Auth::user() -> role;
			$to = $from = $to_array = [];
			$to = \Siakad\Mahasiswa::join('dosen_skripsi', 'dosen_skripsi.skripsi_id', '=', 'mahasiswa.skripsi_id');
			if(intval($angkatan_id) > 0) $to = $to -> where('angkatan', $angkatan_id);
			$to = $to -> where('dosen_id', $dosen_id)
			-> select('id', 'NIM', 'nama')
			-> orderBy('NIM')
		-> get();
		
		foreach($to as $t) $to_array[] = $t -> id;
		
		$from = \Siakad\Mahasiswa::join('skripsi', 'skripsi.id', '=', 'mahasiswa.skripsi_id') 
		-> whereNotNull('skripsi.id');
		
		if(strtolower($role -> name) == 'prodi') 
		{
		$prodi = \Siakad\Prodi::where('singkatan', $role -> sub) -> first();
		$from = $from -> where('prodi_id', $prodi -> id);
		}
		
		if(count($to_array) > 0) $from = $from -> whereNotIn('mahasiswa.id', $to_array);
		
		$from = $from -> select('skripsi.id', 'NIM', 'nama') -> orderBy('angkatan', 'desc') -> get();
		
		return ['to' => $to, 'from' => $from];
		
		}
		public function pembimbing()
		{
		$role = \Auth::user() -> role;
		
		$dosen = $this -> getDosenSelection();
		$angkatan = $this -> getGolongan('angkatan');
		
		$mahasiswa = \Siakad\Mahasiswa::join('skripsi', 'skripsi.id', '=', 'mahasiswa.skripsi_id');
		if(strtolower($role -> name) == 'prodi') 
		{
		$prodi = \Siakad\Prodi::where('singkatan', $role -> sub) -> first();
		$mahasiswa = $mahasiswa -> where('prodi_id', $prodi -> id);
		}
		$mahasiswa = $mahasiswa
		-> whereNotNull('skripsi.id')
		-> select('skripsi.id', 'NIM', 'nama')
		-> orderBy('angkatan', 'DESC') 
		-> get();
		
		$tmp = DosenSkripsi::select('skripsi_id') -> groupBy('skripsi_id') -> get();
		$terdaftar = [];
		foreach($tmp as $t) $terdaftar[] = $t -> skripsi_id;
		
		return view('mahasiswa.skripsi.pembimbing', compact('dosen', 'mahasiswa', 'terdaftar', 'angkatan'));
		}
		public function pembimbingupdate(Request $request)
		{
		$aktif = \Siakad\Tapel::whereAktif('y') -> first();
		$input = $request -> all();
		$data = [];
		foreach($input['id'] as $sid)
		{
		if(!DosenSkripsi::where('skripsi_id', $sid) -> where('dosen_id', $input['dosen']) -> exists())
		$data[] = ['dosen_id' => $input['dosen'], 'skripsi_id' => $sid, 'tapel_id' => $aktif -> id];
		}
		
		if(count($data) > 0) 
		{
		DosenSkripsi::insertIgnore($data);
		$list = $this -> refreshList($input['dosen'], $input['angkatan']);
		
		return \Response::json($list);
		}
		
		return false;
		}
		
		public function pembimbingAnggota()
		{
		$input = $request -> all();
		$list = $this -> refreshList($input['dosen'], $input['angkatan']);
		
		return \Response::json($list);
		}
		
		public function store(Request $request, $dosen_id)
		{
		$aktif = \Siakad\Tapel::whereAktif('y') -> first();
		$data['tapel_id'] = $aktif -> id;
		$data['dosen_id'] = $dosen_id;
		$data['skripsi_id'] = $request -> get('skripsi_id');
		DosenSkripsi::insert($data);
		return Redirect::route('dosen.skripsi.mahasiswa', $dosen_id) -> with('success', 'Mahasiswa berhasil ditambahkan.');			
		}
		
		public function index($dosen_id)
		{
		$dosen = Dosen::find($dosen_id);
		
		$query_calon = "
		SELECT d.nama as pembimbing, d.id, NIM, m.nama as mahasiswa, m.id as m_id, s.id as s_id, judul
		FROM skripsi s
		LEFT JOIN dosen_skripsi ds on ds.skripsi_id = s.id
		INNER JOIN mahasiswa m on s.id = m.skripsi_id
		LEFT JOIN dosen d on d.id = ds.dosen_id
		WHERE (ds.skripsi_id IS NULL OR ds.skripsi_id NOT IN (SELECT ds.skripsi_id FROM dosen_skripsi ds WHERE ds.dosen_id = :dosen_id))
		";
		$calon = \DB::select($query_calon, ['dosen_id' => $dosen_id]);
		foreach($calon as $c) $tmp[$c -> s_id] = $c -> mahasiswa . ' - ' . $c -> NIM;
		$calon = $tmp;
		
		$data = \DB::select('
		select 
		NIM, m.nama as mhs, m.skripsi_id,
		p.nama as prodi, p.strata, 
		k.nama as program, judul,  
		(select min(tglBimbingan) from bimbingan_skripsi where skripsi_id = m.skripsi_id) as tglAwal,
		(select max(tglBimbingan) from bimbingan_skripsi where skripsi_id = m.skripsi_id) as tglAkhir
		from dosen_skripsi ds
		left join dosen d on d.id = ds.dosen_id
		left join mahasiswa m on m.skripsi_id = ds.skripsi_id
		left join skripsi s on s.id = ds.skripsi_id
		left join prodi p on p.id = m.prodi_id
		left join kelas k on k.id = m.kelasMhs
		left join bimbingan_skripsi bs on bs.skripsi_id = s.id
		where d.id = :id
		group by s.id;', 
		['id' => $dosen_id]
		);
		return view('dosen.skripsi.mahasiswa', compact('data', 'dosen', 'calon'));
		}
		public function destroy($dosen_id, $skripsi_id)
		{
		\Siakad\DosenSkripsi::where('dosen_id', $dosen_id) -> where('skripsi_id', $skripsi_id) -> delete();
		return Redirect::route('dosen.skripsi.mahasiswa', $dosen_id) -> with('success', 'Mahasiswa berhasil dihapus.');
		}
		}
				