<?php
	
	namespace Siakad;
	
	trait TapelTrait{
		public function getTapelSelection($order='asc', $add_pilih = true, $key='id')
		{
			$data = [];
			if($add_pilih) $data['-'] = '-- Tahun Akademik --';
			$tmp = \Siakad\Tapel::orderBy('nama2', $order) -> get();
			
			if($key=='nama2') 
			foreach($tmp as $k) $data[$k -> nama2] = $k -> nama;
			else
			foreach($tmp as $k) $data[$k -> id] = $k -> nama;
			
			return $data;
		}
	}
