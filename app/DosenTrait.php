<?php
	namespace Siakad;
	
	trait DosenTrait{
		public function getDosenSelection($modifier='nama', $add_pilih = true, $placeholder = '-- Dosen --')
		{
			$data = [];
			if($modifier == 'nama')
			{
				if($add_pilih) $data['-'] = $placeholder;
				$tmp = \Siakad\Dosen::orderBy('nama') -> get();
				foreach($tmp as $k) $data[$k -> id] = $k -> gelar_depan . ' ' . $k -> nama . ' ' . $k -> gelar_belakang;
			}
			return $data;
		}
	}