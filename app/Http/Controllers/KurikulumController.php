<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Kurikulum;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class KurikulumController extends Controller
	{
		public function detail($id)
		{
			$kurikulum = Kurikulum::find($id);
			$tmp = Kurikulum::where('id', '<>', $id) -> orderBy('prodi_id') -> orderBy('angkatan') -> get();
			foreach($tmp as $t) $kurikulums[$t -> id] = $t -> nama . ' - akt ' . $t -> angkatan;
			
			$matkul = \Siakad\KurikulumMatkul::detailKurikulum($kurikulum -> id) -> get();
			
			return view('prodi.kurikulum.detail', compact('kurikulum', 'matkul', 'kurikulums'));
		}
		
		public function index(Request $request)
		{
			$mode = $request -> get('nama');
			
			$role = auth() -> user() -> role;
			if($role -> name == 'Prodi') $mode = $role -> sub;
			
			if(!isset($mode))
			{
				$kurikulum = \DB::select("
				SELECT DISTINCT k.`nama`, p.strata, p.nama AS prodi FROM kurikulum k
				left join prodi p on p.id = k.prodi_id
				ORDER BY p.id
				");
				return view('prodi.kurikulum.index0', compact('kurikulum'));
			}
			else
			{
				$kurikulum = \DB::select("
				SELECT k.*, p.strata, p.nama AS prodi, t.nama as tapel,
				SUM(CASE WHEN km.wajib='y' then m.sks_total else 0 end) as j_sks_wajib,
				SUM(CASE WHEN km.wajib='n' then m.sks_total else 0 end) as j_sks_pilihan 
				from `kurikulum` k
				left join kurikulum_matkul km on km.kurikulum_id = k.id
				left join matkul m on m.id = km.matkul_id
				left join prodi p on p.id = k.prodi_id
				left join tapel t on t.id = k.tapel_mulai
				WHERE k.nama = :nama
				group by k.id
				ORDER BY p.id, k.angkatan
				", ['nama' => $mode]);
				return view('prodi.kurikulum.index', compact('kurikulum'));
			}
			
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$prodi = \Siakad\Prodi::all();
			$tmp = [];
			if($prodi)
			foreach($prodi as $p) $tmp[$p -> id] = $p -> strata . ' ' . $p -> nama;
			$prodi = $tmp;
			
			$tapel = \Siakad\Tapel::pluck('nama', 'id');
			
			return view('prodi.kurikulum.create', compact('prodi', 'tapel'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$input= $request -> except('_token');
			$input['sks_total'] = $input['sks_pilihan'] + $input['sks_wajib'];
			Kurikulum::create($input);			
			return Redirect::route('prodi.kurikulum.index') -> with('message', 'Data Kurikulum berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$kurikulum = Kurikulum::find($id);
			
			$prodi = \Siakad\Prodi::all();
			foreach($prodi as $p) $tmp[$p -> id] = $p -> strata . ' ' . $p -> nama;
			$prodi = $tmp;
			
			$tapel = \Siakad\Tapel::pluck('nama', 'id');
			
			$total_sks = $kurikulum -> sks_wajib + $kurikulum -> sks_pilihan;
			return view('prodi.kurikulum.edit', compact('kurikulum', 'prodi', 'tapel', 'total_sks'));
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
			$input = $request -> except('_method');
			$input['sks_total'] = $input['sks_pilihan'] + $input['sks_wajib'];
			Kurikulum::find($id) -> update($input);			
			return Redirect::route('prodi.kurikulum.index') -> with('message', 'Data Kurikulum berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			Kurikulum::find($id) -> delete($id);			
			return Redirect::route('prodi.kurikulum.index') -> with('message', 'Data Kurikulum berhasil dihapus.');
		}
	}
