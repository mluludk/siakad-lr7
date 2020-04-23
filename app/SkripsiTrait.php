<?php
	namespace Siakad;
	
	trait SkripsiTrait{
		private function similarity($judul, $exclude_id=null, $exclude_judul=null)
		{
			$judul = strtoupper($judul);
			$smg = new \Siakad\SmithWatermanGotoh;
			$sim_array = []; //18092019
			$sim = [0, '', ''];
			
			//clean table
			\Siakad\Skripsi::whereRaw('id NOT IN (SELECT skripsi_id FROM mahasiswa WHERE skripsi_id IS NOT NULL)') -> delete();
			
			$skripsi = \Siakad\Skripsi::whereRaw('upper(judul) <> ? ', $judul) 
			-> when($exclude_id !== null, function($q) use($exclude_id)
			{
				return $q -> where('id', '<>', $exclude_id);
			})
			-> when($exclude_judul !== null, function($q) use($exclude_judul)
			{
				return $q -> whereRaw('upper(judul) <> ?', strtoupper($exclude_judul));
			})
			-> pluck('judul', 'id') -> toArray();
			
			foreach($skripsi as $i => $j)
			{
				$tmp = $smg -> compare(strtoupper($judul), strtoupper($j));
				$percent = $tmp * 100;
				$sim_array[] = ['id' => $i, 'judul' => $j, 'similarity' => $percent]; 
				if($percent > $sim[0]) $sim = [$percent, $i];
			}
			
			usort($sim_array, [$this, 'sortBySimilarity']);			
			$sim_array = array_slice($sim_array, 0, 5);
			
			return ['sim' => $sim, 'sim_array' => $sim_array];
		}
		
		function sortBySimilarity($x, $y) {
			if($x['similarity'] == $y['similarity']) return 0;
			else if($x['similarity'] > $y['similarity']) return -1;
			else return 1;
		}
	}										