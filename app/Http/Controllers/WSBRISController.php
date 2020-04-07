<?php
	
	namespace Siakad\Http\Controllers;
	
	use Response;
	
	use Siakad\Bank;
	use Siakad\Tagihan;
	use Siakad\Pembayaran;
	use Siakad\Mahasiswa;
	
	use Siakad\Http\Controllers\Controller;
	
	class WSBRISController extends Controller
	{
		
		public function __construct()
		{
			$this -> bank_id = 1; //BRIS
			
			$this -> api_key = \Cache::get('api_key_' . $this -> bank_id, function(){
				$data = Bank::find($this -> bank_id);
				
				if($data -> api_key != '')
				{
					\Cache::put('api_key_' . $this -> bank_id, $data -> api_key, 60);
					return $data -> api_key;
				}
				
				return false;
			});
		}
		
		public function update(Request $request, $nim=null, $noref=null, $tagihan_id=null, $password=null)
		{
			$response = [];
			if($nim !== null && $noref !== null && $tagihan_id !== null)
			{
				$mahasiswa = Mahasiswa::with('prodi.fakultas') -> where('NIM', $nim) -> first();
				
				if($mahasiswa) 
				{
					$ids = explode('.', $tagihan_id);					
					$payment = true;
					
					//check if reversal
					$update = $insert = [];
					$pembayaran = Pembayaran::where('noref', $noref) -> get();
					if($pembayaran -> count()) 
					{
						$payment = false;
						foreach($pembayaran as $p)
						{
							$update[$p -> tagihan_id] = ['bayar' => 0];
						}
						
						// hapus pembayaran
						Pembayaran::where('noref', $noref) -> delete();
					}
					else
					{
						$tagihan = Tagihan::where('mahasiswa_id', $mahasiswa -> id) -> whereIn('id', $ids) -> orderBy('nama') -> get();
						
						if(!$tagihan -> count()) 
						{
							$response = ['code' => '99', 'data' => ['message' => 'Tagihan tidak ditemukan.']];
						}
						else
						{
							foreach($tagihan as $t)
							{
								if(substr($t -> nama, 0, 4) == 'HER-') // check if HER
								{
									if($password !== null)
									{
										$user = \Siakad\User::where('authable_type', 'Siakad\Mahasiswa') -> where('authable_id', $t -> mahasiswa_id) -> first();
										if($user)
										{
											$password = bcrypt($password);
											$user -> update(['password' => $password, 'remember_token' => null]);
										}
									}
								}
								
								$update[$t -> id] = ['bayar' => $t -> jumlah];								
								$insert[] = [
								'tagihan_id' => $t -> id,
								'jumlah' => $t -> jumlah,
								'noref' => $noref,
								'user_id' => '0'
								];	
							}
							
						}
					}
					
					if(count($update) > 0)
					{
						$result = true;
						foreach($update as $k => $v)
						{
							if(!Tagihan::find($k) -> update($v)) $result = false;	
						}
						
						if(count($insert) < 1 && $payment == true) $result = false;
						elseif(!Pembayaran::insert($insert)) $result = false;
						
						if($result)
						{
							$response['code'] = '00';
							$response['data'] = [
							'nomorPembayaran' => $nim,
							'idTagihan' => $tagihan_id
							];
						}
						else
						{
							$response = ['code' => '99', 'data' => ['message' => 'Kesalahan System atau kesalahan input param URL.']];
						}
					}
					else
					{
						$response = ['code' => '99', 'data' => ['message' => 'Kesalahan System atau kesalahan input param URL.']];
					}
				}
				else
				{
					$response = ['code' => '99', 'data' => ['message' => 'Mahasiswa tidak ditemukan.']];
				}
			}
			else
			{
				$response = ['code' => '99', 'data' => ['message' => 'Kesalahan System atau kesalahan input param URL.']];
			}
			return \Response::json($response);		
		}
		
		public function getTagihanMhs($nim=null, $api_key=null)
		{			
			$response = [];
			
			$stored_key = $this -> api_key;
			if(!$stored_key or $api_key == null or $api_key != $stored_key) 
			return \Response::json(['code' => '99', 'data' => ['message' => 'API KEY tidak terdaftar.']]);
			
			if($nim !== null)
			{
				$mahasiswa = Mahasiswa::with('prodi.fakultas') -> where('NIM', $nim) -> first();
				
				if($mahasiswa) 
				{
					$tagihan = Tagihan::tanggungan($mahasiswa -> id, null, $this -> bank_id) -> get();
					
					if($tagihan -> count())
					{
						//check TGL CICILAN & OVERRIDE
						$today = strtotime(date('Y-m-d') . ' 00:00:00');
						
						$tmp = $tagihan_id = [];
						
						$tagihan_per_inquiry = 1;
						$jml_tagihan = 0;
						foreach($tagihan as $t)
						{ 
							$jml_tagihan ++;
							$accepted = false;
							$lunas = true;
							
							if($t -> tgl_cicilan_awal != '' && $t -> tgl_cicilan_akhir != '')
							{
								if($today >= strtotime($t -> tgl_cicilan_awal . ' 00:00:00') && $today <= strtotime($t -> tgl_cicilan_akhir . ' 23:59:59'))
								$accepted = true;
								elseif($t -> override == 'y') 
								$accepted = true;
							}
							else
							{
								$accepted = true;
							}
							
							if(($t -> jumlah - $t -> bayar) > 0) $lunas = false;
							
							if($accepted && !$lunas)
							{
								$tmp[$t -> jenis_biaya_id] = [								
								'' . $t -> id,
								$t -> nama_tagihan,
								$t -> jumlah
								];
								$tagihan_id[] = $t -> id;
								
								if($jml_tagihan >= $tagihan_per_inquiry) break;
							}
							
						}
						
						if(count($tmp) > 0)
						{
							$response['code'] = '00';
							$response['data'] = [
							'nomorPembayaran' => $nim,
							'nomorInduk' => $nim,
							'nama' => $mahasiswa -> nama,
							'fakultas' => $mahasiswa -> prodi -> fakultas -> nama,
							'jurusan' => $mahasiswa -> prodi -> nama,
							];
							
							$total = 0;
							foreach($tmp as $t)
							{
								$total += $t[2];
								$response['details']['item'][] = [
								'idItem' => $t[0],
								'namaItem' => $t[1],
								'nominal' => '' . $t[2],
								];	
							}
							$response['data']['idTagihan'] = implode('.', $tagihan_id);
							$response['data']['totalNominal'] = '' . $total;
							
						}
						else
						{
							$response = ['code' => '88', 'data' => ['message' => 'Tagihan tidak ditemukan.']];
						}
						}
						else
						{
						$response = ['code' => '88', 'data' => ['message' => 'Tagihan tidak ditemukan.']];
						}
						}
						else 
						{
						$response = ['code' => '88', 'data' => ['message' => 'Mahasiswa tidak terdaftar.']];
						}
						}
						else
						{
						$response = ['code' => '99', 'data' => ['message' => 'Format Data salah.']];
						}
						return \Response::json($response);		
						}
						}
												