<?php
	namespace Siakad;
	
	use Firebase\JWT\JWT;
	use GuzzleHttp\Client;
	
	trait ZoomTrait{
		public function sendRequest($uri='/v2/users/me/meetings', $data) 
		{			
			$client = new Client([
            'base_uri' => 'https://api.zoom.us/',
			'verify' => false,
            'headers' => 
			[
			'Authorization' => 'Bearer ' . $this -> generateJWTKey(),
			'Content-Type' => 'application/json',
			'Accept' => 'application/json',
			],
			]);
			$response = $client -> post($uri, ['body' => json_encode($data)]);
			
			if($response -> getStatusCode() == 201)			
			return json_decode($response -> getBody() -> getContents());
			
			//regenerate token
			// if($response -> getStatusCode() == 401)
			
			
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