<?php
	
	namespace Siakad;
	
	use Illuminate\Http\Request;
	// use Illuminate\Support\Facades\Input;
	trait FileEntryTrait{
		
		public function upload($input, $kategori = 'lain')
		{
			// if($input == null)
			// {
				// $input = $request -> all();
			// }
			// dd($input);
			$file = $input['file'];
			
			$rules = [
			'gambar' => 'mimes:jpg,jpeg,png|max:2048',
			'video' => 'mimes:mp4,ogg|max:20480',
			'dokumen' => 'mimes:docx,doc,pptx,ppt,xlsx,xls,pdf|max:5120',
			'lain' => 'mimes:docx,doc,pptx,ppt,xlsx,xls,pdf,jpg,jpeg,png'
			];
			
			$validator = \Validator::make($input, ['file' => $rules[$kategori]]);
			if($validator -> fails())
			{
				$info = explode('|', $rules[$kategori]);
				$mimes = substr($info[0], 6, strlen($info[0]));	
				$max = isset($info[1]) ? ' Maks. ' . $this -> human_filesize(substr($info[1], 4, strlen($info[1])) * 1024) : '';
				return ['success' => false, 'error' => 'File yang diperbolehkan adalah: ' . $mimes . $max];	
			}
			else
			{
				$path = storage_path('app/upload/');
				$date = date('Y/m/d/');
				$tipe = isset($input['tipe']) ? $input['tipe'] : '99';
				
				if(isset($input['nama'])) 
				$filename = $input['nama'];
				else
				$filename = substr($file -> getClientOriginalName(), 0, -3) . '-' . str_random(3);			
				
				$filename = str_slug($filename) . '.' . $file -> extension();		
				$size = $this -> human_filesize($file -> getSize());
				$mime = $file->getClientMimeType();
				$result = $file -> move($path . 'files/' . $date, $filename);					
				if(!$result)
				{
					return ['success' => false, 'error' => 'File tidak dapat disimpan.'];			
				}	
				
				$entry = new FileEntry();
				$entry -> mime = $mime;
				$entry -> namafile = $date . $filename;
				$entry -> ukuran = $size;
				$entry -> nama = $filename;
				$entry -> user_id = \Auth::user() -> id;
				$entry -> tipe = $tipe;
				
				
				if(isset($input['akses'])) $entry -> akses = json_encode($input['akses']);
				
				if($entry->save())
				{ 
					return [
					'success' => true,
					'id' => $entry -> id, 
					'filename' => $date . $filename, 
					'name' => $filename, 
					'filesize' => $size, 
					'mime' => $mime, 
					'created_at' => date('Y-m-d H:i:s')
					];
				} 
				
				return ['success' => false, 'error' => 'Database Error'];
			}
		}
		
		/* http://stackoverflow.com/a/23888858 */
		private function human_filesize($bytes, $dec = 2) 
		{
			$size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
			$factor = floor((strlen($bytes) - 1) / 3);
			
			return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) . @$size[$factor];
		}
	}
