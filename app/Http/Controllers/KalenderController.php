<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Siakad\Kalender;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	
	class KalenderController extends Controller
	{
		protected $rules = [
		'mulai1' => ['date', 'date_format:d-m-Y'],
		'sampai1' => ['date', 'date_format:d-m-Y', 'after:mulai1'],
		'mulai2' => ['date', 'date_format:d-m-Y'],
		'sampai2' => ['date', 'date_format:d-m-Y', 'after:mulai2']
		];
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index(Request $request)
		{
			$public = in_array(\Auth::user() -> role_id, [1,2,8,16]) ? false : true;
			$aktif = $request -> get('tahun');
			$tahun = [];
			$tmp = \DB::select('SELECT DISTINCT LEFT(`nama`, 9) AS `year`, nama2, aktif FROM `tapel` ORDER BY nama2 DESC');
			foreach($tmp as $t) 
			{
				$tahun[substr($t -> year, 0, 4)] = $t -> year;
				if($t -> aktif == 'y' && $aktif == null) $aktif = substr($t -> nama2, 0, 4);
			}
			
			$query = Kalender::query();
			if($aktif != null) $query = $query -> where('tahun', $aktif);
			$agenda = $query -> orderBy('mulai1') -> get();
			
			$file = \Siakad\FileEntry::where('tipe', '6') -> orderBy('id', 'desc') -> first();
			return view('kalender.index', compact('tahun', 'agenda', 'file', 'aktif', 'public'));
		}
		public function index2(Request $request)
		{
			$public = in_array(\Auth::user() -> role_id, [1,2,8,16]) ? false : true;
			$aktif = $request -> get('tahun');
			$tahun = [];
			$tmp = \DB::select('SELECT DISTINCT LEFT(`nama`, 9) AS `year`, nama2, aktif FROM `tapel` ORDER BY nama2 DESC');
			
			foreach($tmp as $t) 
			{
				$tahun[substr($t -> year, 0, 4)] = $t -> year;
				if($t -> aktif == 'y' && $aktif == null) $aktif = substr($t -> nama2, 0, 4);
			}
			
			$query = Kalender::query();
			if($aktif != null) $query = $query -> where('tahun', $aktif);
			$agenda = $query -> orderBy('mulai1') -> get();
			
			$tmp = [];
			$legends = [];
			foreach($agenda as $cal)
			{
				$legends[] = ['bg' => $cal -> bg, 'fg' => $cal -> fg,'id' => $cal -> id, 'label' => $cal -> kegiatan, 'kode' => $cal -> kode];
				
				if($cal -> sampai1 != '' && $cal -> sampai1 != '0000-00-00')
				{
					$begin = new \DateTime($cal -> mulai1);
					$end = new \DateTime($cal -> sampai1);
					$end = $end -> modify( '+1 day' );
					
					$interval = new \DateInterval('P1D');
					$daterange = new \DatePeriod($begin, $interval ,$end);
					foreach($daterange as $date){
						$tmp[$date->format("Y-m-d")] = ['title' => $cal -> kegiatan . ' Ganjil', 'kode' => $cal -> kode, 'fg' => $cal -> fg, 'bg' => $cal -> bg, 'id' => $cal -> id];
					}
				}
				else 
				{
					if($cal -> mulai1 != '' && $cal -> mulai1 != '0000-00-00') $tmp[$cal -> mulai1] = ['title' => $cal -> kegiatan . ' Ganjil', 'kode' => $cal -> kode, 'fg' => $cal -> fg, 'bg' => $cal -> bg, 'id' => $cal -> id];
				}
				
				if($cal -> sampai2 != '' && $cal -> sampai2 != '0000-00-00')
				{
					$begin = new \DateTime($cal -> mulai2);
					$end = new \DateTime($cal -> sampai2);
					$end = $end -> modify( '+1 day' );
					
					$interval = new \DateInterval('P1D');
					$daterange = new \DatePeriod($begin, $interval ,$end);
					
					foreach($daterange as $date){
						$tmp[$date->format("Y-m-d")] = ['title' => $cal -> kegiatan . ' Genap', 'kode' => $cal -> kode, 'fg' => $cal -> fg, 'bg' => $cal -> bg, 'id' => $cal -> id];
					}
				}
				else 
				{
					if($cal -> mulai2 != '' && $cal -> mulai2 != '0000-00-00') $tmp[$cal -> mulai2] = ['title' => $cal -> kegiatan . ' Genap', 'kode' => $cal -> kode, 'fg' => $cal -> fg, 'bg' => $cal -> bg, 'id' => $cal -> id];
				} 
				
			}
			$agenda = $tmp;
			
			$bulan = config('custom.bulan');
			$th = $aktif;
			$awal = 7;
			for($c = 1; $c <= 12; $c++)
			{
				$_awal = str_pad($awal, 2, '0', STR_PAD_LEFT);
				$bulan_hari[] = $bulan[$_awal] . ' ' . $th . '|' . date('t', strtotime($th . '-' . $_awal . '-' . '01')) . '|' . $th . '-' . $_awal;
				$awal ++;
				if($awal > 12) { $awal = 1; $th ++;}
			}			
			
			return view('kalender.index2', compact('tahun', 'agenda',  'aktif', 'bulan_hari', 'legends', 'public'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			return view('kalender.create');
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request  $request)
		{
			// $this -> validate($request, $this->rules);
			$input = $request -> all();
			
			$input['mulai1'] = isset($input['mulai1']) && $input['mulai1'] != '' ? date_format(date_create_from_format('d-m-Y', $input['mulai1']), 'Y-m-d') : '0000-00-00';
			$input['sampai1'] = isset($input['sampai1']) && $input['sampai1'] != ''  ? date_format(date_create_from_format('d-m-Y', $input['sampai1']), 'Y-m-d') : '0000-00-00';
			
			$input['mulai2'] = isset($input['mulai2']) && $input['mulai2'] != '' ? date_format(date_create_from_format('d-m-Y', $input['mulai2']), 'Y-m-d') : '0000-00-00';
			$input['sampai2'] = isset($input['sampai2'])  && $input['sampai2'] != ''  ? date_format(date_create_from_format('d-m-Y', $input['sampai2']), 'Y-m-d') : '0000-00-00';
			
			Kalender::create($input);
			
			return Redirect::route('kalender.index') -> with('message', 'Data kegiatan berhasil diperbarui.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$agenda = Kalender::where('id', $id) -> first();
			
			$agenda -> mulai1 = $agenda -> mulai1 == '0000-00-00' ? '00-00-0000' : date('d-m-Y', strtotime($agenda -> mulai1));
			$agenda -> sampai1 = $agenda -> sampai1 == '0000-00-00' ? '00-00-0000' : date('d-m-Y', strtotime($agenda -> sampai1));
			
			$agenda -> mulai2 = $agenda -> mulai2 == '0000-00-00' ? '00-00-0000' : date('d-m-Y', strtotime($agenda -> mulai2));
			$agenda -> sampai2 = $agenda -> sampai2 == '0000-00-00' ? '00-00-0000' : date('d-m-Y', strtotime($agenda -> sampai2));
			
			return view('kalender.edit', compact('agenda'));
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
			// $this -> validate($request, $this->rules);
			
			$input = $request -> except('_method', '_token');
			
			if(!$request  -> ajax())
			{
				$input['mulai1'] = isset($input['mulai1']) ? date_format(date_create_from_format('d-m-Y', $input['mulai1']), 'Y-m-d') : '0000-00-00';
				$input['sampai1'] = isset($input['sampai1']) ? date_format(date_create_from_format('d-m-Y', $input['sampai1']), 'Y-m-d') : '0000-00-00';
				
				$input['mulai2'] = isset($input['mulai2']) && $input['mulai2'] != '' ? date_format(date_create_from_format('d-m-Y', $input['mulai2']), 'Y-m-d') : '0000-00-00';
				$input['sampai2'] = isset($input['sampai2'])  && $input['sampai2'] != ''  ? date_format(date_create_from_format('d-m-Y', $input['sampai2']), 'Y-m-d') : '0000-00-00';
			}
			$kalender = Kalender::find($id) -> update($input);
			
			return Redirect::route('kalender.index') -> with('message', 'Data kegiatan berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
	{
	$kegiatan = Kalender::find($id)  -> delete();
	return Redirect::route('kalender.index') -> with('message', 'Data kegiatan berhasil dihapus.');
	}
	}
		