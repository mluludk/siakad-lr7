<?php
	
	namespace Siakad\Http\View\Composers;
	
	use Illuminate\Support\Facades\View;
	use Illuminate\Support\ServiceProvider;
	use Siakad\Config;
	use Cache;
	
	class ViewServiceProvider extends ServiceProvider
	{
		/**
			* Register bindings in the container.
			*
			* @return void
		*/
		public function boot()
		{		
			$tmp = null;
			$configs = Cache::get('configs');
			
			if($configs == null)
			{
				$tmp['server']['apache'] = $_SERVER['SERVER_SOFTWARE'];
				$tmp['server']['php'] = phpversion();
				$ver = \DB::select('SELECT VERSION() AS version');
				$tmp['server']['mysql'] = $ver[0] -> version;
				Cache::forever('configs', $tmp);
				$configs = $tmp;
			}
			
			view() -> share('configs', $configs); 
			
			//new mail
			View::composer('app', function($view){
				$user = \Auth::user();
				if($user)
				{
					//mail
					$new_mail = \Siakad\Mail::where('recipient', $user -> id) -> where('read', 'n') -> count();
					$view -> with('new_mail', $new_mail);
				}
			});
			
			//notifications
			View::composer('notifications', function($view){
				$user = \Auth::user();
				$notifications = [];
				if(strtolower($user -> role -> name) == 'mahasiswa')
				{
					$total_tanggungan = 0;
					$data = $user -> authable;
					$tagihan = \Siakad\Tagihan::join('setup_biaya', 'setup_biaya.id', '=', 'setup_biaya_id')
					-> where('angkatan', $data -> angkatan)
					-> where('prodi_id', $data -> prodi_id)
					-> where('kelas_id', $data -> kelasMhs)
					-> where('jenisPembayaran', $data -> jenisPembayaran)
					-> where('mahasiswa_id', $data -> id)
					-> get(['tagihan.id', 'tagihan.jumlah', 'tagihan.bayar']);
					
					if($tagihan)
					{
						$tanggungan = 0;
						$bayar = 0;
						foreach($tagihan as $t)
						{
							$tanggungan +=  $t -> jumlah;
							$bayar +=  $t -> bayar;
						}
					}
					$total_tanggungan =  $tanggungan - $bayar;
					
					if($total_tanggungan > 0) 
					$notifications[] = [
					'icon' => ' fa fa-money',
					'status' => 'text-danger',
					'message' => 	'Anda mempunyai tanggungan pembayaran sebesar Rp ' . number_format($total_tanggungan, 0, ',', '.')
					];
				}
				$view -> with('notifications', $notifications);
			});
			
			
		}
		
		/**
			* Register the service provider.
			*
			* @return void
		*/
		public function register()
		{
		
		}
	}																								