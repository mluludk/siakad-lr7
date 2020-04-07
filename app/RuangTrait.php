<?php
	
	namespace Siakad;
	
	trait RuangTrait{
		public function getRuangSelection($order='asc', $add_pilih = true)
		{
			$data = [];
				if($add_pilih) $data['-'] = '-- Ruangan --';
				$tmp = \Siakad\Ruang::orderBy('nama', $order) -> get();
				if($tmp)
				foreach($tmp as $k) $data[$k -> id] = $k -> nama;
			return $data;
		}
	}
	