<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	
	use Siakad\Matkul;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	class MatkulController extends Controller
	{
		public function __construct()
		{
			$this->middleware('auth');	
		}
		
		
		/**
			* Query building for search
		**/
		public function preSearch()
		{
			$q = strtolower($request -> get('q'));
			$qclean = preg_replace("[^ 0-9a-zA-Z]", " ", $q);
			
			while (strstr($qclean, "  ")) {
				$qclean = str_replace("  ", " ", $qclean);
			}
			
			$qclean = str_replace(" ", "_", $qclean);
			
			if ($q != '') {
				return redirect( '/matkul/search/' . $qclean);
			}
			return Redirect::back() -> withErrors(['q' => 'Isi kata kunci pencarian yang diinginkan terlebih dahulu']);
		}
		
		/**
			* Search
		**/
		public function search($query)
		{			
			$query =str_replace('_', ' ', $query);
			$matkul = Matkul::search($query) -> paginate(20);
			
			$prodi = $this -> filter('prodi');
			$kelompok = $this -> filter('kelompok');
			$jenis = $this -> filter('jenis');
			
			$message = 'Ditemukan ' . $matkul -> total() . ' hasil pencarian';
			return view('matkul.index', compact('message', 'matkul', 'jenis', 'kelompok', 'prodi'));
		}
		
		private function filter($type)
		{
			$data = [];
			switch($type)
			{
				case 'prodi':
				$prodi = \Siakad\Prodi::all();
				$data['-'] = '-- PRODI --';
				foreach($prodi as $k) $data[$k -> id] = $k -> strata . ' ' . $k -> nama;
				break;
				
				case 'jenis':
				$data['-'] = '-- JENIS --';
				foreach(config('custom.pilihan.jenisMatkul') as $k => $v) $data[$k] = $v;
				break;
				
				case 'kelompok':
				$data['-'] = '-- KELOMPOK --';
				foreach(config('custom.pilihan.kelompokMatkul') as $k => $v) $data[$k] = $v;
				break;	
			}
			
			return $data;
		}
		
		public function filtering()
		{
			$input = $request -> all();
			$matkul = Matkul::with('prodi');
			if($input['jenis'] !== '-') $matkul = $matkul -> where('jenis', $input['jenis']);
			if($input['kelompok'] !== '-') $matkul = $matkul -> where('kelompok', $input['kelompok']);
			if($input['prodi'] !== '-') $matkul = $matkul -> where('prodi_id', $input['prodi']);
			$perpage = intval($request -> get('perpage')) > 0 ? $request -> get('perpage') : 200;
			$matkul = $matkul -> orderBy('kode', 'asc') -> paginate($perpage);	
			
			$prodi = $this -> filter('prodi');
			$kelompok = $this -> filter('kelompok');
			$jenis = $this -> filter('jenis');
			
			return view('matkul.index', compact('matkul', 'jenis', 'kelompok', 'prodi'));
		}
		
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$prodi = $this -> filter('prodi');
			$kelompok = $this -> filter('kelompok');
			$jenis = $this -> filter('jenis');
			$matkul = Matkul::with('prodi') -> orderBy('kode', 'asc') -> paginate(25);
			
			return view('matkul.index', compact('matkul', 'jenis', 'kelompok', 'prodi'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$prodi = \Siakad\Prodi::all();
			foreach($prodi as $p)	$tmp[$p -> id] = $p -> strata . ' ' . $p -> nama;
			$prodi = $tmp;
			
			return view('matkul.create', compact('prodi'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{			
			$input = $request -> all();
			$input['sks_total'] = $input['sks_tm'] + $input['sks_prak'] + $input['sks_prak_lap'] + $input['sks_sim'];
			Matkul::create($input);
			return Redirect::route('matkul.index') -> with('message', 'Data mata kuliah berhasil dimasukkan');
		}
		
		/**
			* Display the specified resource.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function show($id)
		{
		$matkul = Matkul::find($id);
		return view('matkul.show', compact('matkul'));
		}
		
		/**
		* Show the form for editing the specified resource.
		*
		* @param $id
		* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
		$matkul = Matkul::find($id);
		$prodi = \Siakad\Prodi::all();
		foreach($prodi as $p)	$tmp[$p -> id] = $p -> strata . ' ' . $p -> nama;
		$prodi = $tmp;
		return view('matkul.edit', compact('matkul', 'prodi'));
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
		$input = array_except($request -> all(), '_method');
		$input['sks_total'] = $input['sks_tm'] + $input['sks_prak'] + $input['sks_prak_lap'] + $input['sks_sim'];
		
		Matkul::find($id) -> update($input);
		
		return Redirect::route('matkul.index') -> with('message', 'Data mata kuliah berhasil diperbarui.');
		}
		
		/**
		* Remove the specified resource from storage.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
		$matkul = Matkul::find($id) -> delete();
		return Redirect::route('matkul.index') -> with('success', 'Data mata kuliah berhasil dihapus.');
		}
		}
				