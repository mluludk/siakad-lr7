<?php
	
	namespace Siakad;
	
	use Illuminate\Support\Facades\Input;
	trait SkalaTrait{
		
		//SKALA NILAI
		public function skala($prodi_id)
		{
			$tmp = \Cache::get('skala_' . $prodi_id, function() use ($prodi_id){
				$skala = \Siakad\Skala::where('prodi_id', $prodi_id) -> orderBy('angka', 'desc') -> get();
				if(!$skala) return null;
				
				$tmp = [];
				foreach($skala as $s)
				{
					$tmp[$s -> huruf] = [
					'angka' => $s -> angka,
					'min' => $s -> bobot_min,
					'max' => $s -> bobot_max,
					'min_100' => $s -> bobot_min_100,
					'max_100' => $s -> bobot_max_100,
					'lulus' => $s -> lulus,
					'predikat' => $s -> predikat
					];
				}
				\Cache::put('skala_' . $prodi_id, $tmp, 30);
				return $tmp;
			});
			
			return $tmp;
		}
		
		public function toHuruf($skala, $angka)
		{
			$angka = intval($angka);
			if($angka > 100 or $angka <= 0) return '-'; 
			foreach($skala as $k => $v) 
			if($angka >= intval($v['min_100']) && $angka <= intval($v['max_100'])) return $k;
			
			return '-'; 
		}
		
		public function toPredikat($skala, $angka)
		{
			if($angka > 4 or $angka < 0) return '';
			foreach($skala as $k => $v) 
			// if($angka >= floatval($v['min']) && $angka <= floatval($v['max'])) return $v['predikat'];
			if($angka >= intval($v['min']) && $angka <= intval($v['max'])) return $v['predikat'];
		}
	}
