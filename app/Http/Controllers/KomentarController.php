<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	use Siakad\Komentar;
	
	use Siakad\Http\Controllers\Controller;
	
	class KomentarController extends Controller
	{
		
		public function store(Request $request, $model, $id)
		{
			$auth = \Auth::user();
			$waktu = date('Y-m-d H:i:s');
			$input = [
			'commentable_type' => 'Siakad\\' . $model,
			'commentable_id' => $id,
			'komentar' => $request -> get('komentar'),
			'waktu' => $waktu,
			'user_id' => $auth -> id
			];
			
			$submit = Komentar::create($input);
			if($submit)
			{
				$komentar = $this -> getKomentar($model, $id, $request -> get('last_id'));
				
				return [
				'success' => true,
				'items' => $komentar
				];
			}
			
			return ['success' => false, 'error' => 'Gagal menyimpan Komentar.'];
		}
		
		public function destroy(MatkulTapel $kelas, Komentar $sesi)
		{
			$sesi -> delete();			
			return Redirect::route('matkul.tapel.sesi.index', $kelas -> id) -> with('success', 'Data Sesi Pembelajaran berhasil dihapus.');
		}
		
		public function getKomentar($model, $id, $last_id=0)
		{
			$komentar = [];
			$url = url('/');
			$foto = $url . '/images/logo.png';
			
			//Get "new" chat				
			$new = Komentar::with('author')
			-> where('commentable_type', 'Siakad\\' . $model)
			-> where('commentable_id', $id)
			-> where('id', '>', $last_id)
			-> orderBy('waktu')
			-> get();
			
			foreach($new as $n)
			{
				if($n -> author -> authable -> foto != '' and file_exists(storage_path('app/upload/images/') . $n -> author -> authable -> foto))
				{
					$foto = $url . '/getimage/' . $n -> author -> authable -> foto;
				}
				
				//attachment
				$k = '';
				if(substr($n -> komentar, 0, 3) == 'att')
				{
					$att = explode(';', $n -> komentar);
					if($att[0] == 'att:img') 
					{
						foreach(explode(',', substr($att[1], 5)) as $file)
						{
							$k .= '<a target="_blank" href="' . url('/getfile' . $file) . '">';
							$k .= '<img src="' . url('/getfile' . $file) . '" style="width: 200px; display: inline-block;"/>';
							$k .= '</a>';
						}
					}
					elseif($att[0] == 'att:doc') 
					{
						foreach(explode(',', substr($att[1], 5)) as $file)
						{
							$k .= '<a target="_blank" href="' . url('/getfile' . $file) . '">';
							$k .= '<i class="fa fa-file-text-o"></i> ' . substr($file, 12);
							$k .= '</a>';
						}
					}
					elseif($att[0] == 'att:vid') 
					{
						foreach(explode(',', substr($att[1], 5)) as $file)
						{
							$k .= '<video controls style="display: block; width: 200px;">';
							$k .= '<source src="' . url('/getfile' . $file) . '">';
							$k .= 'Your browser does not support the video tag.
							</video>';
						}
					}
					
				}
				else $k = $n -> komentar;
				
				$komentar[] = [
				'id' => $n -> id,
				'image' => $foto,
				'user' => $n -> author -> authable -> nama,
				'status' => strtotime($n -> author -> last_login) >= strtotime('5 minutes ago') ? 'online' : 'offline',
				'waktu' => $n -> waktu,
				'komentar' => $k
				];
			}
			
			return $komentar;
		}
		}
										