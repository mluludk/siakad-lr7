<?php
	
	namespace Siakad\Http\Controllers;
	
	use Response;
	use Illuminate\Http\Request;
	
	use Siakad\FileEntry;
	use Siakad\Http\Controllers\Controller;
	
	class FileEntryController extends Controller
	{
		use \Siakad\FileEntryTrait;
		
		public function managerUpload(Request $request)
		{
			$input = $request -> all();
			
			$kategori = $input['kategori'];
			
			return $this -> upload($input, $kategori);
		}
		public function manager(Request $request, $type='dokumen')
		{
			$auth = \Auth::user();
			$file = FileEntry::where('user_id', $auth -> id);
			
			$file -> when($type == 'gambar', function ($q) {
				return $q -> where('mime', 'like', 'image%');
			});
			$file -> when($type == 'video', function ($q) {
				return $q-> where('mime', 'like', 'video%');
			});
			$file -> when($type == 'dokumen', function ($q) {
				return $q-> where('mime', 'like', 'application%');
			});
			
			$file = $file -> orderBy('created_at', 'desc') -> get();
			
			$rand = str_random(10); //prevent submit more than once
			
			$icons = [
			'pdf' => 'fa-file-pdf-o', 'docx' => 'fa-file-word-o', 'doc' => 'fa-file-word-o', 
			'xls' => 'fa-file-excel-o', 'xlsx' => 'fa-file-excel-o', 'pptx' => 'fa-file-powerpoint-o', 
			'ppt' => 'fa-file-powerpoint-o', 'mp4' => 'fa-file-video-o', 'ogg' => 'fa-file-video-o'
			];
			
			return view('file.manager', compact('type', 'file', 'rand', 'icons'));
		}
		
		public function index()
		{
			$files = FileEntry::orderBy('created_at', 'desc') -> paginate(20);
			return view('file.index', compact('files'));
		}
		
		public function download($id, $t) 
		{
			if($t != csrf_token()) abort(401);
			$detail = FileEntry::findOrFail($id); 
			
			$a_akses = json_decode($detail -> akses, true);
			$akses = count($a_akses) < 1 ? [] : $a_akses;
			if(!in_array(\Auth::user() -> role_id, $akses))
			{
				if(\Auth::user() -> role_id > 2)
				{
					if($detail -> user_id != \Auth::user() -> id)
					abort(401);
				}
			}
			$mime = $detail -> mime;
			
			$storage = \Storage::disk('files');			
			if(!$storage -> exists($detail -> namafile)) abort(404);
			
			$headers = [$mime];
			return Response::download($storage->getDriver()->getAdapter()->getPathPrefix() . $detail -> namafile, $detail -> nama, $headers);
		}
		
		public function getFileDirect($y, $m, $f)
		{
			$filename = $y . '/' . $m . '/' . $f;
			
			$storage = \Storage::disk('files');			
			if(!$storage -> exists($filename)) abort(404);
			$headers = [];
			
			return Response::download($storage->getDriver()->getAdapter()->getPathPrefix() . $filename, $f, $headers);
		}
		
		public function getFile($y, $m, $d, $f) 
		{
			$filename = $y . '/' . $m . '/' . $d . '/' . $f;
			
			$detail = FileEntry::where('namafile', $filename) -> firstOrFail(); 
			
			$a_akses = json_decode($detail -> akses, true);
			if($a_akses != null)
			{
				$akses = count($a_akses) < 1 ? [] : $a_akses;
				if(!in_array(\Auth::user() -> role_id, $akses))
				{
					if(\Auth::user() -> role_id > 2)
					{
						if($detail -> user_id != \Auth::user() -> id)
						abort(401);
					}
				}
			}
			
			$mime = $detail -> mime;
			
			$storage = \Storage::disk('files');			
			if(!$storage -> exists($filename)) abort(404);
			$file = $storage -> get($filename);
			
			$response = \Response::make($file, 200);
			$response->header('Content-type', $mime);
			
			return $response;
		}
		
		public function delete(Request $request, $id)
		{
			$file = FileEntry::find($id);
			$user_role = \Auth::user() -> role_id;
			
			if(in_array($user_role, [1, 2]) or $file -> user_id == \Auth::user() -> id)
			{
				$storage = \Storage::disk('files');
				if($storage -> exists($file -> namafile))
				{
					$result = $storage -> delete($file -> namafile);
					if($result) 
					{
						$file -> delete();
						
						if($request -> ajax())
						return ['success' => true, 'message' => 'File berhasil dihapus.'];
						else
						return \Redirect::route('indexfile') -> with('message', 'File berhasil dihapus.');
					}
				}
				else
				{
					$file -> delete();
					
					if($request -> ajax())
					return ['success' => false, 'message' => 'File tidak ditemukan.'];
					else
					return \Redirect::route('indexfile') -> withErrors('File tidak ditemukan.');
				}
			}
			if($request -> ajax())
			return ['success' => false, 'message' => 'Anda tidak dapat menghapus file ini.'];
			else
			return \Redirect::route('indexfile') -> withErrors('Maaf, Anda tidak dapat menghapus file ini');
		}
		
		public function uploadForm(Request $request)
		{
			$tmp = \Siakad\Role::whereNotIn('id', [1, 2]) -> get();
			foreach($tmp as $a) $akses[$a->id] = $a -> name . ' ' . $a -> sub; 
			$type = $request -> get('type');
			$nama = $request -> get('name');
			return view('file.create', compact('akses', 'type', 'nama'));
		}
	}
