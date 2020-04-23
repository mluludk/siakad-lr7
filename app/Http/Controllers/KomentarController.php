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
				$url = url('/');
					$foto = $url . '/images/logo.png';
				if($auth -> authable -> foto != '')
				{
					if(file_exists(storage_path('app/upload/images/') . $auth -> authable -> foto))
					{
						$foto = $url . '/getimage/' . $auth -> authable -> foto;
					}
				}
				
				return [
				'success' => true,
				'image' => $foto,
				'user' => $auth -> authable -> nama,
				'status' => strtotime($auth -> last_login) >= strtotime('5 minutes ago') ? 'online' : 'offline',
				'waktu' => $waktu,
				'komentar' => $request -> get('komentar')
				];
			}
			
			return ['success' => false, 'error' => 'Gagal menyimpan Komentar.'];
		}
		
		public function destroy(MatkulTapel $kelas, Komentar $sesi)
		{
			$sesi -> delete();			
			return Redirect::route('matkul.tapel.sesi.index', $kelas -> id) -> with('success', 'Data Sesi Pembelajaran berhasil dihapus.');
		}
	}
