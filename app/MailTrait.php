<?php
	namespace Siakad;
	
	trait MailTrait{
		public function sendMail($from, $to, $subject, $body)
		{
			$send = \Siakad\Mail::create([
				'sender' => $from,
				'recipient' => $to,
				'subject' => $subject,
				'body' => $body,
				'time' => date('Y-m-d H:i:s')
			]);
			
			if($send) return true;
			
			return false;
		}
	}			
	