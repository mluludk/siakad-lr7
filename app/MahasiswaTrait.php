<?php
	
	namespace Siakad;
	
	use Illuminate\Support\Facades\Input;
	trait MahasiswaTrait
	{
		
		public function getMahasiswaSelection($status=null, $prodi=null, $sort_by='NIM', $sort='desc')
		{
			$data = [];
			$mhs = \Siakad\Mahasiswa::where('id', '>', 0);
			if($status !== null) $mhs = $mhs -> where('statusMhs', $status);
			if($prodi !== null) $mhs = $mhs -> where('prodi_id', $prodi);
			
			$mhs = $mhs -> orderBy($sort_by, $sort) -> get();
			
			if($mhs) foreach($mhs as $m) $data[$m -> id] = $m -> nama . ' (' . $m -> NIM . ')';
			
			return $data;
		}
		
		public function getGolongan($modifier, $add_pilih = true, $singkatan=false)
		{
			$data = [];
			if($modifier == 'status')
			{
				if($add_pilih) $data['-'] = '-- Status --';
				foreach(config('custom.pilihan.statusMhs') as $k => $v) $data[$k] = $v;
			}
			
			if($modifier == 'tapel')
			{
				$tapel = \Siakad\Tapel::orderBy('nama2', 'desc') -> get();
				if($add_pilih) $data['-'] = '-- TAHUN AKADEMIK --';
				foreach($tapel as $k) $data[$k -> id] = $k -> nama;
			}
			
			if($modifier == 'prodi')
			{
				$prodi = \Siakad\Prodi::all();
				if($add_pilih) $data['-'] = '-- PRODI --';
				if(!$singkatan) foreach($prodi as $k) $data[$k -> id] = $k -> strata . ' ' . $k -> nama;
				else foreach($prodi as $k) $data[$k -> id] = $k -> strata . ' ' . $k -> singkatan;
			}
			
			if($modifier == 'program')
			{
				$kelas = \Siakad\Kelas::all();
				if($add_pilih) $data['-'] = '-- Program --';
				foreach($kelas as $k) $data[$k -> id] = $k -> nama;
			}
			
			if($modifier == 'angkatan')
			{
				$angkatan = \DB::select('
				SELECT DISTINCT angkatan AS `tahun` FROM `mahasiswa` ORDER BY tahun DESC
				');
				if($add_pilih) $data['-'] = '-- Angkatan --';
				foreach($angkatan as $k => $v) $data[$v->tahun] = $v->tahun;
			}
			
			if($modifier == 'semester')
			{
				if($add_pilih) $data['-'] = '-- Semester --';
				foreach(range(1, 10) as $r) $data[$r] = $r;
			}
			return $data;
		}
	}
