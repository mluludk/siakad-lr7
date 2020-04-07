<?php
	
	namespace Siakad;
	
	trait ProdiTrait{
		public function getProdiSelection($by='id', $order='asc', $add_pilih = true, $key = 'id', $selected=null)
		{
			$data = [];
			if($add_pilih)
			{
				$data['-'] = '-- Program Studi --';
			}
			$tmp = \Siakad\Prodi::orderBy($by, $order) -> get();
			if($tmp)
			{
				foreach($tmp as $k)
				{
					if($selected !== null)
					{
						if($k -> $key == $selected) $data[$k -> $key] = $k -> strata . ' - ' . $k -> nama;
					}
					else
					{
						$data[$k -> $key] = $k -> strata . ' - ' . $k -> nama;
					}
				}
			}
			return $data;
		}
	}
