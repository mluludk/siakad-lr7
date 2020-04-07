<?php
	
	namespace Siakad\Exports;
	
	use Siakad\DosenPenelitian;
	use Illuminate\Contracts\View\View;
	
	use Maatwebsite\Excel\Concerns\FromView;	
	
	class DosenPenelitianExport implements FromView
	{
		public function view(): View
		{
			$data = DosenPenelitian::RiwayatPenelitian() -> get();
			
			return view('dosen.penelitian.export', [
            'rdata' => $data
			]);
		}
	}
	