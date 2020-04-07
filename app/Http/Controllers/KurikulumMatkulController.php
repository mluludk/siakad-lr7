<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\KurikulumMatkul;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class KurikulumMatkulController extends Controller
	{
		public function addFrom($id)
		{
			\DB::insert('INSERT IGNORE INTO `kurikulum_matkul` (`kurikulum_id`, `matkul_id`, `semester`, `wajib`) SELECT ' . $id . ', `matkul_id`, `semester`, `wajib` FROM `kurikulum_matkul` WHERE `kurikulum_id` = ' . $request -> get('from'));
			return Redirect::route('prodi.kurikulum.detail', $id) -> with('message', 'Data Mata Kuliah berhasil ditambahkan.');
		}
		
		public function edit($id)
		{
			$kurikulum = \Siakad\Kurikulum::find($id);
			$matkul = \DB::select("
			select m.*, km.* 
			from kurikulum_matkul km
			left join matkul m on m.id = km.matkul_id
			WHERE kurikulum_id = :id
			ORDER BY km.semester ASC
			", ['id' => $kurikulum -> id]);
			return view('prodi.kurikulum.matkul.edit', compact('kurikulum', 'matkul'));
		}
		
		public function update(Request $request, $kurikulum_id)
		{
			$input = $request -> except('_token');
			
			foreach($input['c'] as $checked) 
			{
				$wajib = isset($input['wajib'][$checked]) ? 'y' : 'n';
				$s[] = 'WHEN ' . $checked . ' THEN "' . $input['semester'][$checked] . '" ';
				$w[] = 'WHEN ' . $checked . ' THEN "' . $wajib . '" ';
			}
			
			$query = '
			UPDATE kurikulum_matkul 
			SET semester = 
			CASE id ' . implode(' ', $s) . ' 
			END, 
			wajib = 
			CASE id ' . implode(' ', $w) . ' 
			END 
			WHERE id IN (' . implode(', ', $input['c']) . ')';
			
			\DB::update($query);
			
			return Redirect::route('prodi.kurikulum.detail', $kurikulum_id) -> with('message', 'Data Mata Kuliah berhasil diubah.');
		}
		
		public function create($kurikulum_id)
		{
			$kurikulum = \Siakad\Kurikulum::find($kurikulum_id);
			
			$matkul = \Siakad\Matkul::where('prodi_id', $kurikulum -> prodi_id) -> orderBy('nama') -> get();
			foreach($matkul as $m) $tmp[$m -> id] = $m -> kode . ' - ' . $m -> nama . ' (' . $m -> sks_total . ' sks)';
			$matkul = $tmp;
			
			return view('prodi.kurikulum.matkul.create', compact('kurikulum', 'matkul'));
		}
		
		public function store(Request $request, $kurikulum_id)
		{
			$input = $request -> except('_token');
			$km = KurikulumMatkul::where('kurikulum_id', $kurikulum_id) -> where('matkul_id', $input['matkul_id']) -> exists();
			if($km) return Redirect::route('prodi.kurikulum.detail', $kurikulum_id) -> withErrors(['EXISTS' => 'Mata Kuliah sudah terdaftar pada kurikulum ini.']);
			
			$input['kurikulum_id'] = $kurikulum_id;
			$input['wajib'] = isset($input['wajib']) ? 'y' : 'n';
			KurikulumMatkul::create($input);
			return Redirect::route('prodi.kurikulum.detail', $kurikulum_id) -> with('message', 'Mata Kuliah berhasil ditambahkan.');
		}
		
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($kid, $mid_sem)
		{
			$tmp = explode('-', $mid_sem);
			$result = KurikulumMatkul::where('kurikulum_id', $kid) -> where('matkul_id', $tmp[0]) -> where('semester', $tmp[1]) -> delete();
			if($result) return Redirect::back() -> with('success', 'Data berhasil dihapus');
			else return Redirect::back() -> with('danger', 'Data gagal dihapus');
		}
	}
