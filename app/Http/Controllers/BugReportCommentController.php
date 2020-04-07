<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\BugReportComment;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class BugReportCommentController extends Controller
	{		
		public function store(Request $request)
		{
			$input= $request -> except('_token');
			$input['time'] = date('Y-m-d H:i:s');
			$input['user_id'] = \Auth::user() -> id;
			BugReportComment::create($input);
			
			//send mail
			$this -> sendEmail($input);
			
			return Redirect::back() -> with('success', 'Komentar berhasil ditambahkan.');
		}
		
		private function sendEmail($input)
		{
			$report = \Siakad\BugReport::find($input['bug_report_id']);
			
			if($report -> reporter != $input['user_id']) //no self-email
			{
				$user = \Siakad\User::find($input['user_id']);
				
				$data = [
				'sender' => $input['user_id'],
				'recipient' => $report -> reporter,
				'subject' => 'Balasan untuk Laporan Bug #' . $report -> id . ' ' . $report -> title,
				'body' => 'Hai, saya telah membalas Laporan Bug anda tentang '. $report -> title .'. 
				Klik <a href="'. url('/report/' . $report -> id) .'">disini</a> untuk melihat balasan secara lengkap.<br/><br/>--' . $user -> authable -> nama,
				'time' => date('Y-m-d H:i:s')
				];
				
				\Siakad\Mail::create($data);
			}
		}
		
	}
