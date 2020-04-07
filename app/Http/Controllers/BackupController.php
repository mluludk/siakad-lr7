<?php namespace Siakad\Http\Controllers;
	
	use Input;
	use Redirect;
	
	use Siakad\Backup;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	class BackupController extends Controller 
	{
		
		public function destroy($id)
		{
			$backup = Backup::find($id);
			if($backup -> delete())
			{
				$storage = \Storage::disk('backups');	
				if($storage -> exists($backup -> file)) $storage -> delete($backup -> file);
			}
			return Redirect::route('backup.index') -> with('success', 'File telah dihapus');
		}
		public function index()
		{
			$backups = Backup::with('executor') -> orderBy('id', 'desc') -> get();
			return view('backup.index', compact('backups'));
		}
		
		public function create()
		{
			$name = env('DB_DATABASE') . '_' . date('YmdHis') . '.sql.gz';
			$db = $this -> EXPORT_TABLES('localhost', env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'), false, $name, true);
			
			$storage = \Storage::disk('backups');
			$result = $storage -> put($name, $db);
			if($result)
			{
				if(Backup::insert(['date' => date('Y-m-d H:i:s'), 'user' => \Auth::user() -> id, 'file' => $name]))
				return Redirect::route('backup.index') -> with('success', 'Backup sukses!');
			}
			return Redirect::route('backup.index') -> withErrors(['BACKUP_FAILED' => 'Backup gagal!']);
		}
		
		public function export($id) 
		{
			if($request -> get('_token') != csrf_token()) abort(404);
			
			$backup = Backup::whereId($id) -> first();
			if(!isset($backup -> file) or $backup -> file == '') abort(404);
			
			$storage = \Storage::disk('backups');	
			if(!$storage -> exists($backup -> file)) abort(404);
			return \Response::download($storage -> getDriver() -> getAdapter() -> getPathPrefix() . $backup -> file, $backup -> file, ['application/x-gzip']);
		}
		
		public function restore($id) 
		{
			if($request -> get('_token') != csrf_token()) abort(404);
			
			$backup = Backup::whereId($id) -> first();
			if(!isset($backup -> file) or $backup -> file == '') return Redirect::route('backup.index') -> withErrors(['NO_BACKUP_FILE' => 'Tidak ada catatan file backup!']);
			
			$storage = \Storage::disk('backups');	
			if(!$storage -> exists($backup -> file)) return Redirect::route('backup.index') -> withErrors(['FILE_NOT_FOUND' => 'File tidak ditemukan!']);
			
			$file = $storage -> getDriver() -> getAdapter() -> getPathPrefix() . $backup -> file;
			if(pathinfo($file, PATHINFO_EXTENSION) == 'sql')
			$sql = \File::get($file);
			else
			$sql = gzdecode(\File::get($file));
			
			\Artisan::call('down');
			
			$this -> IMPORT_TABLES('localhost', env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'), $sql);
			
			\Artisan::call('up');
			
			return Redirect::back() -> with('success', 'Import sukses!');
		}
		
		public function importForm()
		{
			return view('backup.import');
		}
		
		public function import()
		{
			$input = $request -> all();
			
			$ext = $input['dbfile'] -> getClientOriginalExtension();
			if($ext != 'sql' and $ext != 'gz') return Redirect::back() -> withErrors(['INVALID_FILE' => 'File tidak valid. Pastikan file ber-ekstensi .sql / .sql.gz']);
			
			$mime = $input['dbfile'] -> getClientMimeType();
			if($mime != 'application/x-gzip' and $ext != 'application/octet-stream') return Redirect::back() -> withErrors(['INVALID_FILE' => 'File tidak valid']);
			
			$uploaded = \File::get($input['dbfile']);
			if($mime == 'application/x-gzip') $uploaded = gzdecode($uploaded);
			
			\Artisan::call('down');
			
			$this -> IMPORT_TABLES('localhost', env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'), $uploaded);
			
			\Artisan::call('up');
			
			return Redirect::back() -> with('success', 'Import sukses!');
		}
		
		// https://github.com/tazotodua/useful-php-scripts 
		//optional: 5th parameter - backup specific tables only: array("mytable1","mytable2",...)   
		//optional: 6th parameter - backup filename
		// NOTE! to adequatelly replace strings in DB, MUST READ:   goo.gl/nCwWsS
		private function EXPORT_TABLES($host,$user,$pass,$name, $tables=false, $backup_name=false, $gzipped=false){ 
			set_time_limit(3000); 
			$mysqli = new \mysqli($host,$user,$pass,$name); 
			$mysqli->select_db($name); $mysqli->query("SET NAMES 'utf8'");
			$queryTables = $mysqli->query('SHOW TABLES');
			while($row = $queryTables->fetch_row()) 
			{ 
				$target_tables[] = $row[0]; 
			}  
			if($tables !== false)
			{ 
				$target_tables = array_intersect( $target_tables, $tables); 
			} 
			$content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+07:00\";\r\nSET FOREIGN_KEY_CHECKS=0;\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `".$name."`\r\n--\r\n\r\n\r\n";			
			foreach($target_tables as $table){
				if (empty($table))
				{ 
					continue; 
				} 
				$result = $mysqli->query('SELECT * FROM `'.$table.'`');     
				$fields_amount=$result->field_count;  
				$rows_num=$mysqli->affected_rows;     
				$content .= "DROP TABLE IF EXISTS `". $table ."`;";
				$res = $mysqli->query('SHOW CREATE TABLE '.$table); 
				$TableMLine=$res->fetch_row(); 
				$content .= "\n\n".$TableMLine[1].";\n\n";
				for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) {
					while($row = $result->fetch_row())  { //when started (and every after 100 command cycle):
						if ($st_counter%100 == 0 || $st_counter == 0 )  {$content .= "\nINSERT INTO ".$table." VALUES";}
						$content .= "\n(";    for($j=0; $j<$fields_amount; $j++){ $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); if (isset($row[$j])){$content .= '"'.$row[$j].'"' ;}  else{$content .= '""';}     if ($j<($fields_amount-1)){$content.= ',';}   }        $content .=")";
						//every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
						if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) {$content .= ";";} else {$content .= ",";} $st_counter=$st_counter+1;
					}
				} $content .="\n\n\n";
			}
			// function
			// $content .="\r\n\r\nDELIMITER ;;\r\n\r\nDROP FUNCTION IF EXISTS `numberToLetter`;;\r\nCREATE FUNCTION `numberToLetter`(`number` DOUBLE) RETURNS varchar(2) CHARSET latin1\r\nDETERMINISTIC\r\nBEGIN\r\nDECLARE ltr varchar(2);\r\n\r\nIF(number <= 100 AND number > 90) THEN SET ltr = 'A+';\r\nELSEIF(number <= 90 AND number > 85) THEN SET ltr = 'A';\r\nELSEIF(number <= 85 AND number > 80) THEN SET ltr = 'A-';\r\nELSEIF(number <= 80 AND number > 75) THEN SET ltr = 'B+';\r\nELSEIF(number <= 75 AND number > 70) THEN SET ltr = 'B';\r\nELSEIF(number <= 70 AND number > 65) THEN SET ltr = 'B-';\r\nELSEIF(number <= 65 AND number > 60) THEN SET ltr = 'C+';\r\nELSEIF(number <= 60 AND number > 55) THEN SET ltr = 'C';\r\nELSEIF(number <= 55 AND number > 50) THEN SET ltr = 'C-';\r\nELSE SET ltr = 'D';\r\nEND IF;\r\nRETURN (ltr);\r\nEND;;\r\n\r\n\r\nDELIMITER ;";
			
			$content .= "\r\nSET FOREIGN_KEY_CHECKS=1;\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
			
			// $backup_name = $backup_name ? $backup_name : $name."___(".date('H-i-s')."_".date('d-m-Y').")__rand".rand(1,11111111).".sql";
			
			//compress
			if($gzipped) 
			{
				if (function_exists('gzencode'))
				{
					$content = gzencode($content, 9);
					$backup_name = $backup_name . '.gz';
				}
			}
			
			// ob_get_clean(); header('Content-Type: application/octet-stream');   header("Content-Transfer-Encoding: Binary"); header("Content-disposition: attachment; filename=\"".$backup_name."\"");
			// echo $content;
			// exit;
			
			return $content;
		}      //see import.php too
		
		// EXAMPLE:	IMPORT_TABLES("localhost","user","pass","db_name", "my_baseeee.sql"); //TABLES WILL BE OVERWRITTEN
		// P.S. IMPORTANT NOTE for people who try to change/replace some strings  in SQL FILE before importing, MUST READ:  https://goo.gl/2fZDQL
		// https://github.com/tazotodua/useful-php-scripts 
		private function IMPORT_TABLES($host,$user,$pass,$dbname, $sql_file_OR_content){
			set_time_limit(3000);
			$SQL_CONTENT = (strlen($sql_file_OR_content) > 300 ?  $sql_file_OR_content : file_get_contents($sql_file_OR_content)  );  
			$allLines = explode("\n",$SQL_CONTENT); 
			$mysqli = new \mysqli($host, $user, $pass, $dbname); if (mysqli_connect_errno()){echo "Failed to connect to MySQL: " . mysqli_connect_error();} 
			$zzzzzz = $mysqli->query('SET foreign_key_checks = 0');	        preg_match_all("/\nCREATE TABLE(.*?)\`(.*?)\`/si", "\n". $SQL_CONTENT, $target_tables); foreach ($target_tables[2] as $table){$mysqli->query('DROP TABLE IF EXISTS '.$table);}         $zzzzzz = $mysqli->query('SET foreign_key_checks = 1');    $mysqli->query("SET NAMES 'utf8'");	
			$templine = '';	// Temporary variable, used to store current query
			foreach ($allLines as $line)	{											// Loop through each line
				if (substr($line, 0, 2) != '--' && $line != '') {$templine .= $line; 	// (if it is not a comment..) Add this line to the current segment
					if (substr(trim($line), -1, 1) == ';') {		// If it has a semicolon at the end, it's the end of the query
						if(!$mysqli->query($templine)){ print('Error performing query \'<strong>' . $templine . '\': ' . $mysqli->error . '<br /><br />');  }  $templine = ''; // set variable to empty, to start picking up the lines after ";"
					}
				}
			}	return 'Importing finished. Now, Delete the import file.';
		}
	}
