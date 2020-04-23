<?php
	namespace Siakad;
	
	trait SkripsiTrait{
		private function similarity($judul, $exclude_id=null)
		{
			$smg = new \Siakad\SmithWatermanGotoh;
			$sim_array = []; //18092019
			$sim = [0, '', ''];
			\Cache::forget('id_judul_skripsi');
			$skripsi = \Cache::get('id_judul_skripsi', function() {
				$c = \Siakad\Skripsi::pluck('judul', 'id');
				\Cache::put('id_judul_skripsi', $c, 60);
				return $c;
			});
			
			//check if exclusion already listed
			if($exclude_id !== null)
			{
				if(isset($skripsi[$exclude_id]))
				{
					\Cache::forget('id_judul_skripsi');
					
					$skripsi = \Cache::get('id_judul_skripsi', function() use ($exclude_id){
						$c = \Siakad\Skripsi::where('id', '<>', $exclude_id) -> pluck('judul', 'id');
						\Cache::put('id_judul_skripsi', $c, 60);
						return $c;
					});
				}
			}
			
			foreach($skripsi as $i => $j)
			{
				$tmp = $smg -> compare(strtolower($judul), strtolower($j));
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