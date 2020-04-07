<?php
	namespace Siakad;
	
	use Cache;
	trait WebServiceTrait
	{
		private function runWs($data, $type='json')
		{					
			// $url = env('PDDIKTI_WS_HOST', 'http://localhost:8082') . '/ws/live2.php';
			$config = config('custom.feeder');
			$url = 'http://' . str_replace('http://', '', $config['host']) . ':' . $config['port'] . '/ws/live2.php';
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_POST, 1);
			
			$headers[] = 'Content-Type: application/' . $type;
			
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			
			if($data)
			{
				$data = $type == 'xml' ? stringXML($data) : json_encode($data);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			}
			
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			$result = curl_exec($ch);
			curl_close($ch);
			
			return json_decode($result);
		}
		
		private function getToken()
		{
			$token = Cache::get('ws_token', function(){			
				$config = config('custom.feeder');
				$token = '';
				
				$input = [
				'act' => 'GetToken', 
				'username' => $config['username'], 
				'password' => $config['password']
				];
				
				$response = $this -> runWs($input);
				if(isset($response -> error_code)) 
				{
					if($response -> error_code == 0) 
					{
						$token = $response -> data -> token;
						Cache::put('ws_token', $token, 5);
					}
				}
				
				return $token;
			});
			
			return $token;
		}
		
		public function getWSData($param)
		{
			$param['token'] = $this -> getToken();
			
			if($param['token'] == '') return json_decode('{"error_code": 2, "error_desc":"Invalid Token"}');
			
			$return = null;
			if(!isset($param['act'])) return json_decode('{"error_code": 3, "error_desc":"BAD PARAM"}');
			
			$response = $this -> runWs($param);
			
			if(isset($response -> error_code)) 
			{
				if($response -> error_code == 0) 
				{
					$return = ['success' => true, 'data' => $response -> data];
				}
				else
				{
					if($response -> error_code == 100)
					{
						Cache::forget('ws_token');
					}
					$return = ['success' => false, 'error_code' => $response -> error_code, 'error_desc' => $response -> error_desc];
				}
			}
			else
			{
				$return = ['success' => false, 'error_code' => 999, 'error_desc' => 'Tidak dapat mengakses Web Service'];
			}
			return $return;
		}
	}
