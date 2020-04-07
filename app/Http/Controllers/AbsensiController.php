<?php
	
	namespace Siakad\Http\Controllers;
	
	
	use Illuminate\Http\Request;
	
	
	use Siakad\Absensi;
	
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class AbsensiController extends Controller
	{
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index($matkul_tapel_id)
		{
			$data = [];
			$mata_kuliah = \Siakad\MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			if($mata_kuliah -> dosen_id != \Auth::user() -> authable -> id) abort(401);
			
			$jurnals = \Siakad\Jurnal::where('matkul_tapel_id', $matkul_tapel_id) -> get();
			$anggota = \Siakad\Nilai::getNilaiAkhirMahasiswa($matkul_tapel_id) -> get();
			if($anggota -> count() < 1) return view('matkul.tapel.absensi', compact('mata_kuliah', 'data', 'anggota', 'matkul_tapel_id', 'jurnals')) -> withErrors(['error' => 'Belum ada mahasiswa yang mengambil Mata Kuliah ini']);
			
			if($jurnals -> count())
			{
				$this -> populateAbsensi($matkul_tapel_id, $jurnals);
			}
			$status = Absensi::Status($matkul_tapel_id) -> orderBy('jurnal_id') -> get();
			foreach($status as $s) 
			{
				$data[$s -> mhs_id]['nim'] = $s -> NIM;
				$data[$s -> mhs_id]['nama'] = $s -> nama;
				$data[$s -> mhs_id]['status'][$s -> jurnal_id] = $s -> status . ':' . $s -> new;
			}
			return view('matkul.tapel.absensi', compact('mata_kuliah', 'data', 'anggota', 'matkul_tapel_id', 'jurnals'));
		}
		
		private function populateAbsensi($matkul_tapel_id, $jurnals)
		{
			$mahasiswa = \Siakad\Nilai::getNilaiAkhirMahasiswa($matkul_tapel_id) -> pluck('mhs_id');
			foreach($jurnals as $jurnal) foreach($mahasiswa as $m) $data[] = '(' . $jurnal -> id . ',' . $m . ')';
			
			\DB::insert('INSERT IGNORE INTO `absensi` (`jurnal_id`, `mahasiswa_id`) VALUES ' . implode(', ', $data));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$input = $request -> except('_token');
			Absensi::where('jurnal_id', $input['jid']) -> where('mahasiswa_id', $input['mid']) -> update(['status' => $input['status'], 'new' => 'N']);
			return \Response::json(['success' => true]);
		}
		
		public function cetak($matkul_tapel_id)
		{
			$data = [];
			$mata_kuliah = \Siakad\MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			$anggota = \Siakad\Nilai::getNilaiAkhirMahasiswa($matkul_tapel_id) -> get();
			$jurnals = \Siakad\Jurnal::where('matkul_tapel_id', $matkul_tapel_id) -> get();
			if(!$jurnals -> count()) return back() -> with('warning', 'Jurnal untuk mata kuliah ' . $mata_kuliah -> matkul . ' belum diisi.');
			
			$status = Absensi::Status($matkul_tapel_id) -> orderBy('jurnal_id') -> get();
			foreach($status as $s) 
			{
				$data[$s -> mhs_id]['nim'] = $s -> NIM;
				$data[$s -> mhs_id]['nama'] = $s -> nama;
				$data[$s -> mhs_id]['status'][$s -> jurnal_id] = $s -> status . ':' . $s -> new;
			}
			return view('matkul.tapel.cetakabsensi', compact('mata_kuliah', 'data', 'anggota', 'matkul_tapel_id', 'jurnals'));
		}
	}
