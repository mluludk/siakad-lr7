<?php
	
	namespace Siakad\Exports;
	
	use Siakad\DosenJurnal;
	use Illuminate\Contracts\View\View;
	
	use Maatwebsite\Excel\Concerns\FromView;	
	
	class DosenJurnalExport implements FromView
	{
		public function view(): View
		{
			$jurnals = DosenJurnal::jurnal() -> get();
			
			return view('dosen.jurnal.export', [
            'rdata' => $jurnals
			]);
		}
	}
	