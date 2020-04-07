<?php
	
	namespace Siakad\Http\Controllers;
	
	
	use Response;
	
	use Illuminate\Http\Request;
	
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Imagine\Image\Box;
	use Imagine\Image\ImageInterface;
	use Orchestra\Imagine\Facade as Imagine;
	
	class UploadController extends Controller
	{
		public function getImage($y, $m, $f) {
			$storage = \Storage::disk('images');
			
			$filename = $y . '/' . $m . '/' . $f;
			if(!$storage -> exists($filename)) 
			{
				$mime = 'Image/PNG';
				$file = file_get_contents(getcwd() . '/images/untitled.png');					
				$response = \Response::make($file, 200);				
				$response -> header('Content-type', $mime);				
				return $response;
			}
			
			$file = $storage -> get($filename);
			
			$mime = class_exists('finfo') ? $storage -> mimeType($filename) : 'Image/PNG';
			
			$response = \Response::make($file, 200);
			
			$response -> header('Content-type', $mime);
			
			return $response;
		}
		
		public function storeImage(Request $request)
		{
			$input = $request -> all();
			$file = $input['image'];
			
			$validator = \Validator::make($input, ['image' => 'image|max:2048']);
			if($validator -> fails())
			{
				return Response::json(['success' => false, 'error' => 'File ini bukan file foto / Ukuran file lebih besar dari 2 Mb']);	
			}
			else
			{
				$twidth = $input['width'];
				$theight = $input['height'];
				
				$date = date('Y/m/');
				$path = storage_path('app/upload/images/') . $date;
				if(!is_dir($path)) mkdir($path, 0777, true);
				
				$filename = str_random(9) . '.' . strtolower($file->getClientOriginalExtension());
				
				//Imagine
				$size = new Box($twidth, $theight);
				$image = Imagine::open($file);
				$result = $image
				-> thumbnail($size, ImageInterface::THUMBNAIL_OUTBOUND)
				-> save($path . $filename);
				
				// free memory;
				// $image->destroy();
				
				if($result)
				{ 
					return Response::json(['success' => true, 'filename' => $date . $filename]);
				}
				else
				{
					return Response::json(['success' => false, 'error' => 'An error occured while trying to save data.']);			
				} 
				
			}
		}
	}
