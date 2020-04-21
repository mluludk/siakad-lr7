<?php
	namespace Siakad;
	
	trait SkripsiTrait{
		private function similarity($judul, $exclude_id=null)
		{
			$judul = strtoupper($judul);
			// $regenerate_daftar = false;
			$smg = new \Siakad\SmithWatermanGotoh;
			$sim_array = []; //18092019
			$sim = [0, '', ''];
			
			// $skripsi = \Cache::get('id_judul_skripsi', function() use($judul) {
				// $c = \Siakad\Skripsi::whereRaw('upper(judul) <> "' . $judul . '"') -> pluck('judul', 'id') -> toArray();
				// \Cache::put('id_judul_skripsi', $c, 60);
				// return $c;
			// });
			
			$skripsi = \Siakad\Skripsi::whereRaw('upper(judul) <> "' . $judul . '"') 
			-> when($exclude_id !== null, function($q) use($exclude_id)
			{
				return $q -> where('id', '<>', $exclude_id);
			})
			-> pluck('judul', 'id') -> toArray();
			
			//mengecualikan judul sendiri
			// if(array_search($judul, $skripsi) != false) $regenerate_daftar = true;
			
			//check if exclusion already listed
			// if($exclude_id !== null)
			// {
				// if(isset($skripsi[$exclude_id]))
				// {
					// $regenerate_daftar = true;
				// }
			// }
			
			// if($regenerate_daftar)
			// {
				// \Cache::forget('id_judul_skripsi');	
				
				// $skripsi = \Cache::get('id_judul_skripsi', function() use ($judul, $exclude_id){
					// $c = \Siakad\Skripsi::whereRaw('upper(judul) <> "' . $judul . '"') -> where('id', '<>', $exclude_id) -> pluck('judul', 'id') -> toArray();
					// \Cache::put('id_judul_skripsi', $c, 60);
					// return $c;
				// });
			// }
			
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