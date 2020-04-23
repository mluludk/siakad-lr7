<?php
	
	namespace Siakad;
	
	use Illuminate\Support\Facades\Input;
	trait FileEntryTrait{
		
		public function upload($input = null)
		{
			if($input == null)
			{
				$input = Input::all();
			}
			$file = $input['file'];
			
			$validator = \Validator::make($input, ['file' => 'mimes:pdf,doc,docx,jpg,jpeg,png']);
			if($validator -> fails())
			{
				return ['success' => false, 'error' => 'File yang diperbolehkan adalah: PDF, DOC, DOCX, JPG, JPEG, PNG'];	
				}
			else
			{
				$date = date('Y/m/d/');
				
				if(isset($input['nama'])) 
				$filename = $input['nama'];
				else
				$filename = str_random(7);			
				
				$filename = str_slug($filename) . '.' . $file->getClientOriginalExtension();		
				
				$size = $file->getClientSize();
				$storage = \Storage::disk('files');
				$result = $storage -> put($date . $filename, \File::get($file));
				
				if(!$result)
				{
					return ['success' => false, 'error' => 'An error occured while trying to save data.'];			
				}				
				$entry = new FileEntry();
				$entry -> mime = $file->getClientMimeType();
				$entry -> namafile = $date . $filename;
				$entry -> ukuran = $this -> human_filesize($size);
				$entry -> nama = $filename;
				$entry -> user_id = \Auth::user() -> id;
				$entry -> tipe = $input['tipe'] != '' ? $input['tipe'] : '99';
				
				/* 	$tipe = $input['tipe'] != '' ? $input['tipe'] : '99';
					$entry = [
					'mime' => $file->getClientMimeType(),
					'namafile' => $date . $filename,
					'ukuran' => $this -> human_filesize($size),
					'nama' => $filename,
					'tipe' => $tipe,
					'user_id' => \Auth::user() -> id
				]; */
				
				if(isset($input['akses'])) $entry -> akses = json_encode($input['akses']);
				// if(isset($input['akses'])) $entry['akses'] = json_encode($input['akses']);
				
				// $id = FileEntry::insertGetId($entry);
				// if(isset($id))
				if($entry->save())
				{ 
					return ['success' => true, 'id' => $entry -> id, 'filename' => $date . $filename];
					// return ['success' => true, 'id' => $id, 'filename' => $date . $filename];
				} 
				return ['success' => false, 'error' => 'An error occured while trying to save data.'];
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
