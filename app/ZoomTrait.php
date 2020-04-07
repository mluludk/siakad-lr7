<?php
	namespace Siakad;
	
	use Firebase\JWT\JWT;
	use GuzzleHttp\Client;
	
	trait ZoomTrait{
		public function sendRequest($uri='/v2/users/me/meetings', $data) 
		{			
			$data = json_encode($data);
			$options = [
            'base_uri' => 'https://api.zoom.us/',
			'verify' => false,
            'headers' => 
			[
			'Authorization' => 'Bearer ' . $this -> generateJWTKey(),
			'Content-Type' => 'application/json',
			'Accept' => 'application/json',
			],
			];
			$client = new Client($options);
			$response = $client -> post($uri, ['body' => $data]);
			
			if($response -> getStatusCode() == 201)			
			return json_decode($response -> getBody() -> getContents());
			
			return false;
		}
		
		//function to generate JWT
        private function generateJWTKey() {
            $key = env('ZOOM_KEY');
            $secret = env('ZOOM_SECRET');
            $token = array(
			"iss" => $key,
			"exp" => time() + 3600 //60 seconds as suggested
            );
            return JWT::encode( $token, $secret );
		}
	}							