<?php
	
	namespace Siakad\Http\Controllers;
	
	use Illuminate\Http\Request;
	
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Jackiedo\LogReader\LogReader;
	
	class LogReaderController extends Controller
	{
		protected $reader;
		
		public function __construct(LogReader $reader)
		{
			$this->reader = $reader;
		}
		
		public function index()
		{
			$date = [];
			$files = \LogReader::getLogFilenameList();
			foreach($files as $k => $v)
			{
				$d = substr($k, 8, 10);
				if(validateDate($d, 'Y-m-d'))
				$date[$d] = $v;
			}
			krsort($date);
			return view('log.reader.index', compact('date'));
		}
		
		public function getPerDate($date)
		{
			$log = \LogReader::filename('laravel-' . $date . '.log') -> get() -> toArray();
			return \Response::json($log);
		}
		
		public function getDetailById($date, $id)
		{
			$log = \LogReader::filename('laravel-' . $date . '.log') -> find($id);
			return \Response::json($log);
		}
		
	}
