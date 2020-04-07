<?php
	
	namespace Siakad\Jobs;
	
	use Siakad\Jobs\Job;
	use Illuminate\Contracts\Mail\Mailer;
	// use Illuminate\Contracts\Bus\SelfHandling;
	
	use Siakad\PmbPeserta;
	
	class SendPmbNotificationEmail extends Job/*  implements SelfHandling */
	{
		/**
			* Create a new job instance.
			*
			* @return void
		*/
		public function __construct($no)
		{
			$this -> no = $no;
		}
		
		/**
			* Execute the job.
			*
			* @return void
		*/
		public function handle(Mailer $mailer)
		{
			$data = PmbPeserta::where('noPendaftaran', $this -> no) -> first();
			$to = explode(',', config('custom.profil.email'));
			
			// $ch = curl_init(env('MAIL_HOST'));
			// curl_setopt($ch, CURLOPT_NOBODY, true);
			// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			// curl_exec($ch);
			// $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			// curl_close($ch);
			
			// if (200==$retcode) {
				// All's well
				foreach($to as $email)
				{
					$mailer -> send('pmb.peserta.notifpendaftaran', ['data' => $data], function($message) use ($email)
					{
						$message->from(html_entity_decode('pmb@staima-alhikam.ac.id'), config('custom.app.abbr') ." " . htmlspecialchars_decode(config('custom.app.title'), ENT_QUOTES));
						$message->subject('Pendaftaran Mahasiswa Baru Sukses!');
						$message->to(trim($email));
					});
				}
				// return 'OK';
				// } else {
				// not so much
				// return 'Error';
			// }
		}
	}
