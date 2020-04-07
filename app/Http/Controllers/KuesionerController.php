<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Cache;
	
	use Illuminate\Http\Request;
	
	
	use Siakad\Kuesioner;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class KuesionerController extends Controller
	{
		
		public function results()
		{
			$tapel = \Siakad\Tapel::orderBy('nama2', 'desc') -> get();
			return view('kuesioner.results', compact('tapel'));
		}
		
		private function getKuesionerResult($tapel_id, $prodi_id=null, $dosen_id=null)
		{
			$tmp = null;
			$result = \Siakad\KuesionerMahasiswa::hasilKuesioner($tapel_id, $prodi_id, $dosen_id) -> get();
			
			if(!$result -> count()) 
			return [];
			else
			{
				$kuesioners = Kuesioner::whereTampil('y') -> count();
				foreach($result as $r)
				{
					$mahasiswa =  \Siakad\MatkulTapel::find($r -> idmt) -> mahasiswa -> count();
					$tmp[$r -> idmt] = [
					'kode' => $r -> kode,
					'dosen' => $r -> dosen,
					'dosen_id' => $r -> dosen_id,
					'matakuliah' => $r -> matakuliah,
					'prodi' => $r -> singkatan,
					'prodi_id' => $r -> prodi_id,
					'program' => $r -> program,
					'NA' => $kuesioners * $mahasiswa > 0 ? round($r -> rating / ($kuesioners * $mahasiswa), 2) : 0
					];
				}
				return $tmp;
			}
		}
		
		public function result(Request $request, $tapel_id, $mode='table')
		{ 
			$cname = md5('kuesioner_ta_' . $tapel_id  . '_result');
			if(null !== $request -> get('prodi'))
			{
				$prodi_id = $request -> get('prodi');
				$cname = md5('kuesioner_ta_' . $tapel_id . '_prodi_' . $prodi_id . '_result');				
			}
			else $prodi_id = null;
			
			if(null !== $request -> get('dosen'))
			{
				$dosen_id = $request -> get('dosen');
				$cname = md5('kuesioner_ta_' . $tapel_id . '_dosen_' . $dosen_id . '_result');
			}
			else $dosen_id = null;
			$tapel = \Siakad\Tapel::whereId($tapel_id) -> first();
			
			$result = Cache::get($cname, function() use($cname, $tapel_id, $prodi_id, $dosen_id){
				$result = $this -> getKuesionerResult($tapel_id, $prodi_id, $dosen_id);
				Cache::put($cname, $result, 30);
				return $result;
			});
			
			$slice = count($result) > 0 ? array_slice($result,0)[0] : null;
			
			$dosen = '';
			if($dosen_id != null) $dosen = $slice['dosen'] . ' ';
			$title = 'Grafik MONEV Dosen ' . $dosen . $tapel -> nama;
			
			if($prodi_id != null) $title .=  ' ' . $slice['prodi'];
			
			if($mode == 'graph')
			{
				return view('kuesioner.result_graph', compact('tapel', 'result', 'prodi_id', 'dosen_id', 'title'));
			}
			elseif($mode == 'graph_print')
			{
				return view('kuesioner.result_graph_print', compact('tapel', 'result', 'prodi_id', 'dosen_id', 'title'));
			}
			elseif($mode == 'graph_pdf')
			{
				$str = '';
				if($prodi_id != null) $str = $slice['prodi'] . '-';
				if($dosen_id != null) $str = str_slug($slice['dosen']) . '-';
				
				return \PDF::loadView('kuesioner.result_graph_print', compact('tapel', 'result', 'prodi_id', 'dosen_id', 'title')) -> download('grafik-monev-' . $str . str_slug($tapel -> nama) . '-' . date('Y-m-d-H-i-s') . '.pdf');
			}
			else
			{
				$prodi_list = \Siakad\Prodi::orderBy('id') -> pluck('singkatan', 'id');
				return view('kuesioner.result', compact('tapel', 'result', 'prodi_list'));
			}
		}
		
		public function detail2($matkul_tapel_id)
		{			
			$page_view = Cache::get(md5('kuesioner_detail2' . $matkul_tapel_id));
			if($page_view != null)
			{
				return $page_view;
			}	
			
			$matkul_tapel = \Siakad\MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			$kuesioners = Kuesioner::whereTampil('y') -> orderBy('kompetensi') -> get();
			foreach($kuesioners as $kuesioner)
			{
				$tmp[$kuesioner -> kompetensi][] = $kuesioner -> id;	
			}
			$kuesioners = $tmp;
			$mahasiswa =  \Siakad\MatkulTapel::find($matkul_tapel_id) -> mahasiswa -> pluck('NIM', 'id');
			$results = \DB::select("
			select mahasiswa.id as mhs_id, NIM, k.id, k.kompetensi, skor
			from kuesioner_mahasiswa km
			inner join kuesioner k on k.id = km.kuesioner_id
			inner join mahasiswa on mahasiswa.id = km.mahasiswa_id
			where matkul_tapel_id = :matkul_tapel_id
			order by mahasiswa.nama, k.id, k.kompetensi asc;
			",
			['matkul_tapel_id' => $matkul_tapel_id]
			);
			foreach($results as $result)
			{
				$tmp2[$result -> mhs_id][$result -> kompetensi][$result -> id] =  $result -> skor;
			}
			$results = $tmp2;
			
			$page_view = view('kuesioner.detail2', compact('matkul_tapel', 'kuesioners', 'mahasiswa', 'results')) -> render();
			Cache::put(md5('kuesioner_detail2' . $matkul_tapel_id), $page_view, 30);
			return $page_view;
		}
		
		public function detail($matkul_tapel_id)
		{
			$matkul_tapel = \Siakad\MatkulTapel::find($matkul_tapel_id);
			$kuesioners = Kuesioner::whereTampil('y') -> orderBy('kompetensi') -> get();
			$result = \DB::select("
			select k.kompetensi, sum(skor) as skor, count(skor) as jml
			from kuesioner_mahasiswa km
			inner join kuesioner k on k.id = km.kuesioner_id
			where matkul_tapel_id = :matkul_tapel_id
			group by kuesioner_id 
			order by k.kompetensi asc
			",
			['matkul_tapel_id' => $matkul_tapel_id]
			);
			$x=0;
			foreach($kuesioners as $kuesioner)
			{
				$skor = $result[$x] -> jml > 0 ? round(($result[$x] -> skor / $result[$x] -> jml) , 2) : 0;
				$poin[$kuesioner -> kompetensi][] =  ['pertanyaan' => $kuesioner -> pertanyaan, 'skor' => $skor] ;
				$x++;
			}
			return view('kuesioner.detail', compact('poin', 'dosen', 'matkul_tapel'));
		}
		
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$kuesioners = Kuesioner::orderBy('kompetensi') -> get();
			return view('kuesioner.index', compact('kuesioners'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			return view('kuesioner.create');
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
			Kuesioner::create($input);
			return Redirect::route('kuesioner.index') -> with('message', 'Data berhasil dimasukkan');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$kuesioner = Kuesioner::find($id);
			return view('kuesioner.edit', compact('kuesioner'));
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
			$kuesioner = Kuesioner::find($id);
			$input = array_except($request -> all(), '_method');
			$kuesioner-> update($input);
			
			return Redirect::route('kuesioner.index') -> with('message', 'Data berhasil diperbarui.');
		}
		
	}
