<?php
	namespace Siakad;
	
	use Cache;
	
	use Artisaninweb\SoapWrapper\SoapWrapper;
	
	trait WebServiceSoapTrait
	{
		
		protected $soapWrapper;
		public function __construct(SoapWrapper $soapWrapper)
		{
			$this->soapWrapper = $soapWrapper;
			
			if(!$this -> soapWrapper -> has('PDDIKTI'))
			{
				$this -> soapWrapper -> add('PDDIKTI', function($service){
					$config = config('custom.feeder');
					$url = 'http://' . str_replace('http://', '', $config['host']) . ':' . $config['port'] . '/ws/live.php?wsdl';
					$service
					-> wsdl($url)
					-> trace(true);
				});
			}
		}
		
		public function ListTable($token=null)
		{
			$response = $this -> soapWrapper -> call('PDDIKTI.ListTable', [$token]);
			
			if(isset($response -> error_code)) 
			{
				if($response -> error_code == 0) 
				{
					$return = ['success' => true, 'data' => $response -> result];
				}
				else
				{
					if($response -> error_code == 100)
					{
						Cache::forget('ws_token');
					}
					$return = ['success' => false, 'error_desc' => $response -> error_desc];
				}
			}
			else
			{
				$return = ['success' => false, 'error_desc' => 'Tidak dapat mengakses Web Service'];
			}
			return $return;
		}
		public function getDictionary($token, $table)
		{
			$response = $this -> soapWrapper -> call('PDDIKTI.GetDictionary', [$token, $table]);
			
			if(isset($response -> error_code)) 
			{
				if($response -> error_code == 0) 
				{
					$return = ['success' => true, 'data' => $response -> result];
				}
				else
				{
					if($response -> error_code == 100)
					{
						Cache::forget('ws_token');
					}
					$return = ['success' => false, 'error_desc' => $response -> error_desc];
				}
			}
			else
			{
				$return = ['success' => false, 'error_desc' => 'Tidak dapat mengakses Web Service'];
			}
			return $return;
		}
		
		public function getRecord($token, $table, $filter='')
		{
			$response = $this -> soapWrapper -> call('PDDIKTI.GetRecordSet', [$token, $table, $filter]);
			
			if(isset($response -> error_code)) 
			{
				if($response -> error_code == 0) 
				{
					$return = ['success' => true, 'data' => $response -> result];
				}
				else
				{
					if($response -> error_code == 100)
					{
						Cache::forget('ws_token');
					}
					$return = ['success' => false, 'error_desc' => $response -> error_desc];
				}
			}
			else
			{
				$return = ['success' => false, 'error_desc' => 'Tidak dapat mengakses Web Service'];
			}
			return $return;
		}
		
		public function insertRecord($token, $table, $data)
		{
			return $this -> soapWrapper -> call('PDDIKTI.InsertRecordset', [$token, $table, $data]);
		}
		
		public function updateRecord($token, $table, $data)
		{
			return $this -> soapWrapper -> call('PDDIKTI.UpdateRecordset', [$token, $table, $data]);
		}
		
		public function deleteRecord($token, $table, $data)
		{
			return $this -> soapWrapper -> call('PDDIKTI.DeleteRecordset', [$token, $table, $data]);
		}
		
		public function getDeletedRecord($token, $table, $data)
		{
			return $this -> soapWrapper -> call('PDDIKTI.GetDeletedRecordset', [$token, $table, $data]);
		}
		
		public function restoreRecord($token, $table, $data)
		{
			return $this -> soapWrapper -> call('PDDIKTI.RestoreRecordset', [$token, $table, $data]);
		}
	}
