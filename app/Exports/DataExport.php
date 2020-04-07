<?php
	
	namespace Siakad\Exports;
	
	use Illuminate\Contracts\View\View;
	
	use Maatwebsite\Excel\Concerns\FromView;	
	
	class DataExport implements FromView
	{
		private $tpl;
		private $rdata;
		
		public function __construct($tpl, $rdata) 
		{
			$this -> tpl = $tpl;
			$this -> rdata = $rdata;
		}
		
		public function view(): View
		{			
			return view($this -> tpl, [
            'rdata' => $this -> rdata
			]);
		}
	}
