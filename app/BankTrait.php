<?php
	
	namespace Siakad;
	
	trait BankTrait{
		public function getBankSelection($add_pilih=true)
		{
			$data = [];
			if($add_pilih) $data['-'] = '-- Metode Pembayaran --';
			$tmp = \Siakad\Bank::orderBy('id') -> get();
			
			foreach($tmp as $k) $data[$k -> id] = $k -> nama;
			
			return $data;
		}
	}
