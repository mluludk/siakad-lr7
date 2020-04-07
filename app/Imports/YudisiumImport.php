<?php
	
	namespace Siakad\Imports;
	
	use Redirect;
	
	use Siakad\Wisuda;
	use Siakad\Skripsi;
	use Siakad\Mahasiswa;
	
	use Illuminate\Support\Collection;
	
	use Maatwebsite\Excel\Concerns\ToCollection;
	use Maatwebsite\Excel\Concerns\WithHeadingRow;
	use Maatwebsite\Excel\Concerns\WithChunkReading;
	
	class YudisiumImport implements ToCollection, WithHeadingRow, WithChunkReading
	{
		protected $success;
		protected $failed;
		protected $not_registered;
		
		public function chunkSize(): int
		{
			return 50;
		}
		
		public function collection(Collection $rows)
		{
			$this -> success = $this -> failed = $this -> not_registered = 0;
			
			foreach ($rows as $row) 
			{
				if($row['sk_yudisium'] == '') continue;
				$tmp[$row['sk_yudisium']][] = [
				'sk_yudisium' => $row['sk_yudisium'],
				'tgl_sk_yudisium' => strtotime($row['tgl_sk_yudisium']) === false ? '' : date('d-m-Y', strtotime($row['tgl_sk_yudisium'])),
				'nim' => $row['nim'],
				'tgl_lulus' => strtotime($row['tgl_lulus']) === false ? '' : date('d-m-Y', strtotime($row['tgl_lulus'])),
				'no_ijasah' => $row['no_ijasah'],						
				'judul_skripsi' => $row['judul_skripsi']		
				];
			}
			
			$wisuda = false;
			$skripsi = false;
			foreach($tmp as $yd)
			{
				foreach($yd as $ws)
				{
					if($ws['sk_yudisium'] != '')
					{
						if($wisuda == false)
						{
							$wisuda = Wisuda::where('SKYudisium', $ws['sk_yudisium']) -> first();
							if($wisuda == null)
							{
								$wisuda = Wisuda::create(
								[
								'nama' => $ws['sk_yudisium'],
								'tanggal' => date('Y-m-d', strtotime($ws['tgl_sk_yudisium'])),
								'SKYudisium' => $ws['sk_yudisium'],
								'tglSKYudisium' => $ws['tgl_sk_yudisium']
								]							
								);
							}
						}
						
						$skripsi = Skripsi::whereJudul(trim($ws['judul_skripsi'])) -> first();
						if($skripsi == null)
						{
							$skripsi = Skripsi::create([
							'judul' => trim($ws['judul_skripsi'])
							]);
						}
						
						$nim = preg_replace('/[^0-9\.]/', '', $ws['nim']);
						$mhs = Mahasiswa::where('NIM', $nim) -> first();
						if($mhs == null)
						{
							$this -> failed ++;
							$this -> not_registered ++;	
						}
						else
						{
							$proses = $mhs -> update(
							[
							'wisuda_id' => $wisuda -> id,
							'skripsi_id' => $skripsi -> id,
							'noIjazah' => $ws['no_ijasah'],
							'tglIjazah' => $ws['tgl_lulus'],
							'tglKeluar' => $ws['tgl_lulus']
							]
							);
							if($proses)
							{
								$this -> success++;
							}
							else
							{
								$this -> failed ++;		
							}
						}
					}
				}
				$wisuda = false;
				
			}
		}
		
		public function getSuccess()
		{
			return $this -> success;	
		}
		public function getFailed()
		{
			return $this -> failed;	
		}
		public function getNotRegistered()
		{
			return $this -> not_registered;	
		}
	}
