<?php
	
	namespace Siakad\Http\Controllers;
	
	
	use Illuminate\Http\Request;
	
	
	use Siakad\AbsensiDosen;
	
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class AbsensiDosenController extends Controller
	{
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index($month = null, $year = null)
		{
			if($month == null) { $month = date('m'); $year = date('Y'); }
			// $absensi = AbsensiDosen::where('tanggal', 'LIKE', '%-' . $month . '-'. $year) -> with('dosen') -> get();
			$absensi = \DB::select('
			SELECT d.nama AS dosen, ad.dosen_id, ad.tanggal, ad.status, ad.jam, m.nama AS matkul, mt.kelas2 AS kelas 
			FROM absensi_dosen ad 
			JOIN dosen d on d.id = ad.dosen_id
			JOIN matkul_tapel mt on mt.id = ad.matkul_tapel_id
			JOIN kurikulum_matkul km on km.id = mt.kurikulum_matkul_id
			JOIN matkul m on km.matkul_id = m.id
			WHERE ad.tanggal LIKE "%-'. $month .'-'. $year.'"
			');
			
			if(count($absensi))
			{
				foreach($absensi as $a)
				{
					$tmp[$a -> dosen_id]['nama'] = $a -> dosen;
					$tmp[$a -> dosen_id]['absensi'][$a -> tanggal][] = ['status' => $a -> status, 'jam' => $a -> jam, 'matkul' => $a -> matkul, 'kelas' => $a -> kelas];
				}
				$absensi = $tmp;
			}
			$days = cal_days_in_month(0, $month, $year);
			
			$public = \Auth::user() -> role_id <= 8 ? false : true;
			
			$nextmonth = date("m/Y",mktime(0,0,0,date($month)+1,1,date($year)));
			$lastmonth = date("m/Y",mktime(0,0,0,date($month)-1,1,date($year)));
			return view('dosen.absensi.index', compact('absensi', 'month', 'year', 'days', 'public', 'nextmonth', 'lastmonth'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create($d=null, $m=null, $y=null, $id=null, $sta=null, $mtid=null, $jam=0)
		{
			if($d != null)
			{
				$date = $d . '-' . $m . '-' . $y;
			}
			else
			{
				$date = date('d-m-Y');
			}
			if($sta == null) $sta = 'H';
			$dosen = \Siakad\Dosen::orderBy('nama') -> pluck('nama', 'id');
			$matkul = \Siakad\MatkulTapel::mataKuliahDosen($id) -> get();
			$tmp = [];
			if($matkul)
			{
				foreach($matkul as $m)
				{
					$tmp[$m -> id] = $m -> matkul . ' ' . $m -> singk . ' ' . arabicToRoman($m -> semester) . ' ' . $m -> kelas2;
				}
			}
			$matkul = $tmp;
			return view('dosen.absensi.create', compact('dosen', 'date', 'id', 'sta', 'matkul', 'mtid', 'jam'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$input = $request -> except('_token');
			$input['new_status'] = $input['status'];
			$input['new_jam'] = $input['jam'];
			$date = explode('-', $input['tanggal']);
			// AbsensiDosen::create($input);
			\DB::select('INSERT INTO `absensi_dosen` (`dosen_id`, `matkul_tapel_id`, `tanggal`, `status`, `jam`) VALUES (:dosen_id, :matkul_tapel_id, :tanggal, :status, :jam) ON DUPLICATE KEY UPDATE `status`=:new_status, `jam`=:new_jam', $input);
			return \Redirect::route('dosen.absensi.index', [$date[1], $date[2]]) -> with('success', 'Data absensi berhasil disimpan');
		}
		
		/**
			* Display the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function show($id)
		{
			//
		}
		
		/**
		* Show the form for editing the specified resource.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			//
		}
		
		/**
			* Update the specified resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request)
		{
			
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			//
		}
	}
