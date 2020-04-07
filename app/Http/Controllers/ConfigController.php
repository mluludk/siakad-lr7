<?php namespace Siakad\Http\Controllers;
	
	use Input;
	use Redirect;
	
	use Imagine\Image\Box;
	use Imagine\Image\ImageInterface;
	use Orchestra\Imagine\Facade as Imagine;
	
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Contracts\Foundation\Application;
	use Illuminate\Http\Request;
	class ConfigController extends Controller {
		
		public function __construct(Application $app)
		{
			$this -> maintenis = $app -> storagePath().'/framework/maintenis';
		}
		
		public function edit()
		{
			$configs = config('custom');
			
			$status = '0';	
			$checked_roles = [];
			$message = '';
			
			if(file_exists($this -> maintenis))
			{
				$info = parse_ini_file($this -> maintenis);
				if($info)
				{
					$status = $info['mode'];	
					$checked_roles = isset($info['allowed']) ? $info['allowed'] : [];
					$message = isset($info['message']) ? $info['message'] : '';
				}
			}
			
			$tmp = \Siakad\Role::whereNotIn('id', [1, 2, 1024, 2048, 4096]) -> get();
			foreach($tmp as $a) $roles[$a->id] = $a -> name . ' ' . $a -> sub; 
			
			return view('config.edit', compact('configs', 'status', 'roles', 'checked_roles', 'message'));
		}
		
		public function update(Request $request)
		{
			$input = $request -> except(['_token', '_method']);
			
			//CONFIG
			$input['config_updated'] = time();
			$configs = config('custom');
			
			//Maintenis
			if($input['status'] == 0)
			{
				if(file_exists($this -> maintenis)) unlink($this -> maintenis);
			}
			else
			{
				$str = 'mode=' . $input['status'] . PHP_EOL;
				$str .= 'time=' . date('Y-m-d H:i:s') . PHP_EOL;
				$str .= 'admin=' . PHP_EOL;
				if($input['status'] == 1)
				{
					if(isset($input['roles']))
					{
						foreach($input['roles'] as $role)
						{
							$str .= 'allowed[]=' . $role . PHP_EOL;
						}
					}
					else $str .= 'allowed[]=' . PHP_EOL;
				}
				else
				{
					$str .= 'allowed[]=' . PHP_EOL;
				}
				$str .= 'message=' . $input['message'] . PHP_EOL;
				file_put_contents($this -> maintenis, $str);
			}
			
			unset($input['status']);
			unset($input['roles']);
			unset($input['message']);
			
			//Dosen Homebase
			if(isset($input['d_homebase']))
			{
				$c = 0;
				$homebase = explode(';', trim($input['d_homebase']));
				
				//unset configs Array
				unset($configs['dosen']['homebase']);
				
				foreach($homebase as $h) 
				{
					if($h != '')
					{
						$input['dosen_homebase_' . $c] = $h;
						$c++;
					}
				}
				unset($input['d_homebase']);
			}
			
			//Keuangan
			if(isset($input['jenisPembayaran']))
			{
				$c = 1;
				$jenis = explode(';', $input['jenisPembayaran']);
				
				//unset configs Array
				unset($configs['pilihan']['jenisPembayaran']);
				
				foreach($jenis as $j) 
				{
					if($j != '')
					{
						$input['pilihan_jenisPembayaran_' . $c] = $j;
						$c++;
					}
				}
				unset($input['jenisPembayaran']);
			}
			
			//Aplikasi
			if(isset($input['ku_f']))
			{
				$rules['ku_f'] = ['image'];
				$this -> validate($request, $rules);
				
				$image = Imagine::open($input['ku_f']);
				$ku_f = new Box(321.3, 207.9);
				$image -> thumbnail($ku_f, ImageInterface::THUMBNAIL_OUTBOUND) -> save('images/ku_f.png');
				
				unset($input['ku_f']);
			}
			
			if(isset($input['ktm_f']))
			{
				$rules['ktm_f'] = ['image'];
				$this -> validate($request, $rules);
				
				$image = Imagine::open($input['ktm_f']);
				$ktm_f = new Box(321.3, 207.9);
				$image -> thumbnail($ktm_f, ImageInterface::THUMBNAIL_OUTBOUND) -> save('images/ktm_f.png');
				
				unset($input['ktm_f']);
			}
			if(isset($input['ktm_b']))
			{
				$rules['ktm_b'] = ['image'];
				$this -> validate($request, $rules);
				
				$image = Imagine::open($input['ktm_b']);
				$ktm_b = new Box(321.3, 207.9);
				$image -> thumbnail($ktm_b, ImageInterface::THUMBNAIL_OUTBOUND) -> save('images/ktm_b.png');
				
				unset($input['ktm_b']);
			}
			
			if(isset($input['favicon']))
			{
				$rules['favicon'] = ['image'];
				$this -> validate($request, $rules);
				
				$image = Imagine::open($input['favicon']);
				$favicon = new Box(32, 32);
				$image -> thumbnail($favicon, ImageInterface::THUMBNAIL_OUTBOUND) -> save('favicon32.png');
				
				$favicon192 = new Box(192, 192);
				$image -> thumbnail($favicon192, ImageInterface::THUMBNAIL_OUTBOUND) -> save('favicon192.png');
				
				unset($input['favicon']);
			}
			
			if(isset($input['logo']))
			{
				$rules['logo'] = ['image'];
				$this -> validate($request, $rules);
				
				$image = Imagine::open($input['logo']);
				$logo = new Box(200, 200);
				$image -> thumbnail($logo, ImageInterface::THUMBNAIL_OUTBOUND) -> save('images/logo.png');
				
				$logo64 = new Box(64, 64);
				$image -> thumbnail($logo64, ImageInterface::THUMBNAIL_OUTBOUND) -> save('images/logo64px.png');
				
				unset($input['logo']);
			}
			
			if(isset($input['header']))
			{
				$rules['header'] = ['image'];
				$this -> validate($request, $rules);
				
				$image = Imagine::open($input['header']);
				$header = new Box(780, 150);
				$image -> thumbnail($header, ImageInterface::THUMBNAIL_OUTBOUND) -> save('images/header.png');
				
				unset($input['header']);
			}
			
			$keys = array_keys($input);
			
			foreach($keys as $key)
			{
				array_set($configs, str_replace('_', '.', $key), strip_tags($input[$key], "<b><i><u><strong><em><br><center><ol><ul><li><h1><h2><h3><h4><h5>"));
			}		
			
			$str = '<?php '. PHP_EOL .'$config = ' . var_export($configs, true) . ';';
			file_put_contents(base_path('storage/app/config.php'), $str);
			
			
			return Redirect::route('config.edit') -> with('message', 'Pengaturan berhasil disimpan');
		}
	}
