<?php
	
	namespace Siakad;
	
	use Illuminate\Support\Facades\Input;
	trait NeracaTrait{
		public function deleteFromNeraca($input)
		{
			if(is_array($input['transable_id']))
			{
				$data = \Siakad\Neraca::whereIn('transable_id', $input['transable_id']);
			}
			else
			{
				$data = \Siakad\Neraca::where('transable_id', $input['transable_id']);
			}
			$data -> where('transable_type', $input['transable_type']) -> delete();
		}
		
		public function storeIntoNeraca($input)
		{		
			if(is_array($input['transable_id']))
			{
				$c = 0;
				foreach($input['transable_id'] as $t => $j)
				{
					$data[$c] = ['created_at' => date('Y-m-d H:i:s'), 'transable_id' => $t, 'transable_type' => $input['transable_type']];
					if($input['jenis'] == 'masuk')
					{
						$data[$c]['masuk'] = $j;
					}
					elseif($input['jenis'] == 'keluar')
					{
						$data[$c]['keluar'] = $j;
					}	
					$c++;
				}
			}
			else
			{
				$data = ['created_at' => date('Y-m-d H:i:s'), 'transable_id' => $input['transable_id'], 'transable_type' => $input['transable_type']];
				if($input['jenis'] == 'masuk')
				{
					$data['masuk'] = $input['jumlah'];
				}
				elseif($input['jenis'] == 'keluar')
				{
					$data['keluar'] = $input['jumlah'];
				}
			}
			\Siakad\Neraca::Insert($data);
		}
	}
