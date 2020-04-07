<?php
	
	namespace Siakad\Exports;
	
	use Siakad\DosenBuku;
	use Illuminate\Contracts\View\View;
	
	use Maatwebsite\Excel\Concerns\FromView;	
	
	class DosenBukuExport implements FromView
	{
		public function view(): View
		{
			$bukus = DosenBuku::buku() -> get();
			$data = [];
			foreach($bukus as $j) 
			{
				$klasifikasi = $j -> klasifikasi == 1 ? 'Buku Referensi' : 'Buku Monograf';
				$data [] = [
				$j -> NIP,  
				$j -> NIDN,  
				$j -> NIK,  
				str_replace("'", '`', $j -> nama), 
				str_replace("'", '`', $j -> judul), 
				$klasifikasi, 
				str_replace("'", '`', $j -> penerbit), 
				$j -> isbn, 
				$j -> tahun_terbit
				];
			}
			
			return view('dosen.buku.export', [
            'rdata' => $data
			]);
		}
	}
	