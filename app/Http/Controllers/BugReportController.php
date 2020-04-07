<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\BugReport;
	use Siakad\BugReportComment;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Siakad\Jobs\SendBugReportEmail;
	class BugReportController extends Controller
	{
		
		protected $priority = [1 => 'Santai', 'Rendah', 'Sedang', 'Tinggi', 'Mendesak'];
		protected $severity = [1 => 'Tidak ada', 'Ringan', 'Sedang', 'Berat', 'Gawat'];
		protected $status = ['Pending', 'Selesai', 'Ditolak'];
		
		public function index()
		{
			$report_id = $request -> get('report_id');
			if(null !== $report_id)
			{
				$report = BugReport::find($report_id); 
				$comments = BugReportComment::where('bug_report_id', $report_id) -> orderBy('time', 'desc') -> get();
			}
			else
			$comments = $report = null;
			
			$user = \Auth::user(); 
			$bug = BugReport::with('user') 
			-> orderBy('status') 
			-> orderBy('priority', 'desc') 
			-> orderBy('date', 'desc') 
			-> paginate(30);
			
			$status = $this -> status;
			return view('bug.index', compact('bug', 'status', 'user', 'comments', 'report'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$user = \Auth::user();
			$priority = $this -> priority;
			$severity = $this -> severity;
			return view('bug.create', compact('user', 'priority', 'severity'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$input= $request -> all();
			
			if(isset($input['attachment']) && is_array($input['attachment']))
			{
				$att = [];
				foreach($input['attachment'] as $k => $v)
				{
					if($v !== null)
					{
						$date = date('Y/m/');
						$storage = \Storage::disk('files');
						$file_name = $date . $v -> getClientOriginalName();
						$result = $storage -> put($file_name, \File::get($v));
						
						if($result) 
						{
							$att[] = ['name' => $file_name, 'size' => $v -> getSize(), 'type' => $v -> getMimeType()];
						}
					}
				}
				if(count($att) > 0) $input['attachment'] = json_encode($att);
				else $input['attachment'] = '';
			}
			else $input['attachment'] = '';
			
			$input['date'] = date('Y-m-d');
			$input['reporter'] = \Auth::user() -> id;
			$input['ua'] = $request->header('User-Agent');
			
			$result = BugReport::create($input);
			
			//send email	
			$this -> dispatch(new SendBugReportEmail($result));
			
			return Redirect::route('report.index') -> with('message', 'Laporan telah diterima.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($report_id)
		{
			$bug = BugReport::find($report_id);
			
			$attachment = $bug -> attachment != '' ? json_decode($bug -> attachment, true) : '';
			$bug -> attachment = $attachment;
			
			$user = \Auth::user();
			$priority = $this -> priority;
			$severity = $this -> severity;
			return view('bug.edit', compact('bug', 'user', 'priority', 'severity'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $report_id)
		{
			$bug = BugReport::find($report_id);
			$att = $bug -> attachment != '' ? json_decode($bug -> attachment) : '';
			
			$input = $request -> except('_method');
			
			if(isset($input['attachment']) && is_array($input['attachment']))
			{
				$att = [];
				foreach($input['attachment'] as $k => $v)
				{
					if($v !== null)
					{
						$date = date('Y/m/');
						$storage = \Storage::disk('files');
						$file_name = $date . $v -> getClientOriginalName();
						$result = $storage -> put($file_name, \File::get($v));
						
						if($result) 
						{
							$att[]['name'] = $file_name;
							$att[]['size'] = $v -> getSize();
							$att[]['type'] = $v -> getMimeType();
						}
					}
				}
				if(count($att) > 0) $input['attachment'] = json_encode($att);
				else $input['attachment'] = '';
			}
			else $input['attachment'] = null;
			
			$bug -> update($input);			
			return Redirect::route('report.index') -> with('message', 'Laporan Bug berhasil diperbarui.');
			}
			
			public function show($report_id)
			{
			$report = BugReport::find($report_id);
			
			$user = \Auth::user();
			$status = $this -> status;
			$comments = BugReportComment::where('bug_report_id', $report -> id) -> orderBy('time', 'desc') -> get();
			return view('bug.show', compact('report', 'status', 'user', 'comments'));
			}
			
			public function resolve($report_id)
			{
			$user = \Auth::user();
			$status = $this -> status;
			$report = BugReport::find($report_id);
			return view('bug.resolve', compact('report', 'status', 'user'));
			}
			public function resolved($report_id)
			{
			$input = $request -> except('_method');
			
			BugReport::find($report_id) -> update($input);			
			return Redirect::route('report.index') -> with('message', 'Laporan Bug telah diselesaikan.');
			}
			
			public function resolvedByUser($report_id)
			{
			$input = $request -> except('_method');
			if(isset($input['resolved']))
			{
			BugReport::find($report_id) -> update(['status' => 1]);			
			return Redirect::route('report.index') -> with('success', 'Terima Kasih. Laporan Bug telah diselesaikan.');
			}
			
			return Redirect::back() -> with('warning', 'Anda harus menyetujui pernyataan bahwa Bug telah diselesaikan.');
			}
			}
						