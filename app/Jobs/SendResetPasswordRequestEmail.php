<?php
	
	namespace Siakad\Jobs;
	
	use Siakad\Jobs\Job;
	use Illuminate\Contracts\Mail\Mailer;
	// use Illuminate\Contracts\Bus\SelfHandling;
	
	class SendResetPasswordRequestEmail extends Job /* implements SelfHandling */
	{
		/**
			* Create a new job instance.
			*
			* @return void
		*/
		public function __construct($data)
		{
			$this -> data = $data;
		}
		
		/**
			* Execute the job.
			*
			* @return void
		*/
		public function handle(Mailer $mailer)
		{
			$time = date('G');
			
			if($time > 3 and $time <= 9) $greeting = 'Pagi';
			elseif($time > 9 and $time <= 15) $greeting = 'Siang';
			elseif($time > 15 and $time <= 18) $greeting = 'Sore';
			else $greeting = 'Malam';
			
			// $ch = curl_init(env('MAIL_HOST'));
			// curl_setopt($ch, CURLOPT_NOBODY, true);
			// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);			
			// curl_exec($ch);
			// $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			// curl_close($ch);
			
			// if (200==$retcode) {
				$mailer -> send('auth.passwords.resetrequest', ['data' => $this -> data, 'greeting' => $greeting], function($message)
				{
					$message -> from(html_entity_decode(env('MAIL_USERNAME')), $this -> data['config']['app']['abbr'] ." " . htmlspecialchars_decode($this -> data['config']['app']['title'], ENT_QUOTES));
					$message -> subject('Reset Password ' . $this -> data['config']['app']['abbr'] ." " . htmlspecialchars_decode($this -> data['config']['app']['title'], ENT_QUOTES));
					$message -> to(trim($this -> data['email']));
				});
				// return true;
				// } else {
				// not so much
				// return false;
			// }
		}
	}
