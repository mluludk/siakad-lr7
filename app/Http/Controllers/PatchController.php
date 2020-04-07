<?php 
	namespace Siakad\Http\Controllers;
	
	class PatchController extends Controller
	{
		public function process($version, $token)
		{
			$config = config('custom');
			$updater_url = env('PATCHER_HOST');
			$result = ['result' => 0, 'message' => 'Undefined Error'];
			
			//cURL
			$ch = curl_init($updater_url . '?g=' . $version . '&token=' . $token);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);    
			$error = curl_error($ch);
			curl_close($ch);
			
			//result check
			if($output === false) $output = '{"result":0, "message": "'. $error .'"}';		
			$result = json_decode($output);
			
			if($result -> success == 1)
			{
				$path = storage_path('app/patch/');
				$bak_dir = storage_path() . '/app/backups/' . $version;
				$base = base_path();
				
				file_put_contents(
				$path . $version . '.tar.gz', 
				fopen('http://' . $updater_url . '/patch/' . $version . '.tar.gz', 'r')
				);
				
				//bring down
				\Artisan::call('down');
				
				//@redundant
				if(file_exists($path . $version . '.tar')) unlink($path . $version . '.tar');
				
				$p = new \PharData($path . $version . '.tar.gz');
				$p -> decompress();
				
				// unarchive from the tar
				$p = new \PharData($path . $version . '.tar');
				$p -> extractTo($base, null, true);
				
				//create Backup	only if not exists, in case of repatching with the same file
				if(mkdir($bak_dir))
				{
					$patch_files = file($base . '/patch_' . $version . '.log');
					foreach($patch_files as $pf)
					{
						$pf = trim($pf);
						$this -> my_copy($base . $pf, $bak_dir . $pf);
					}
				}
				
				//DB
				$db_error = '';
				if(file_exists($base . '/patch_' . $version . '.sql'))
				{					
					$bak_db = $this -> EXPORT_TABLES('localhost', env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'), false, true);
					file_put_contents($bak_dir . '/' . env('DB_DATABASE') . '_' . date('YmdHis') . '.sql.gz', $bak_db);
					
					//import
					if(!$this -> IMPORT_TABLES('localhost', env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'), $base . '/patch_' . $version . '.sql'))
					{
						$db_error = 'Terjadi kesalahan Import Database (patch_' . $version . '.sql). Import Database secara manual.';
					}
					else
					{
						unlink($base . '/patch_' . $version . '.sql');
					}					
				}
				
				//delete tmp
				unset($p);				
				unlink($path . $version . '.tar');
				unlink($path . $version . '.tar.gz');
				
				\Artisan::call('up');
				
				//set update version
				$configs = config('custom');
				array_set($configs, 'app.version_timestamp', $version);				
				$str = '<?php '. PHP_EOL .'$config = ' . var_export($configs, true) . ';';
				file_put_contents(base_path('storage/app/config.php'), $str);
				
				return \Redirect::route('patch.check') -> with('info', 'Pembaruan Aplikasi berhasil!' . $db_error);
			}
			
			return \Redirect::route('patch.check') -> with('warning', 'Pembaruan Aplikasi gagal!');
		}
		
		public function check()
		{
			$config = config('custom');
			$updater_url = env('PATCHER_HOST');
			$version = $config['app']['version_timestamp'];
			
			//cURL
			$ch = curl_init($updater_url . '?v=' . $version);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);    
			$error = curl_error($ch);
			curl_close($ch);
			
			//result check
			if($output === false) $output = '{"result":0, "message": "'. $error .'"}';		
			$result = json_decode($output);
			
			return view('patch.check', compact('config', 'result'));
		}
		
		public function create()
		{		
			if(env('APP_ENV') !== 'local') abort(404);
			
			$base = base_path();
			$base_ln = strlen($base);
			$patch_dir = public_path() . '\patch';
			$master_patch_file = public_path() . '\patch\master_patch.txt';
			$time = time();
			
			$app = $this -> getDirContents($base . '/app');
			$view = $this -> getDirContents($base . '/resources/views');
			$all = array_merge($app, $view);
			
			$master = @unserialize(file_get_contents($master_patch_file));
			if(!$master || count($master) < 1) 
			{
				$this -> seed($master_patch_file, $all);				
				return 'Master Patch OK';
			}	
			
			//find difference
			$diff = [];
			foreach($all as $file)
			{
				$r = $this -> recursive_array_search($file['md5_path'], $master);
				if($r == null)
				{
					$diff[] = $file;
					$master[] = $file;
				}
				elseif($master[$r[0]]['sha1_content'] !== $file['sha1_content'])
				{
					$diff[] = $file;
					$master[$r[0]]['sha1_content'] = $file['sha1_content'];
					$master[$r[0]]['last_modified'] = $file['last_modified'];
				}
			}
			
			if(file_exists($base . '/patch.sql'))
			{
				rename($base . '/patch.sql', $patch_dir . '/tmp/patch_' . $time . '.sql');	
			}
			
			if(count($diff) > 0)
			{
				$this -> seed($master_patch_file, $master);	
				$this -> logger(null, PHP_EOL . 'Patched file list @ ' . date('d-m-Y H:i'));
				
				//copy
				foreach($diff as $d)
				{
					$file = substr($d['path'], $base_ln, strlen($d['path']));
					$this -> my_copy($d['path'], $patch_dir . '\tmp' . $file);
					$this -> logger($patch_dir . '\tmp\patch_'. $time .'.log', $file);
					$this -> logger(null, $file);
				}
				
				// pack
				$pd = new \PharData($patch_dir . '\\' . $time . '.tar');
				$pd -> buildFromDirectory($patch_dir . '\tmp');
				$pd -> compress(\Phar::GZ);
				
				//cleaning
				unset($pd);
				unlink($patch_dir . '\\' . $time . '.tar');
				unlink($patch_dir . '\tmp\patch_' . $time . '.log');
				unlink($patch_dir . '/tmp/patch_' . $time . '.sql');
				$this -> recursive_rmdir($patch_dir . '\tmp\app');
				$this -> recursive_rmdir($patch_dir . '\tmp\resources');
				
				//done
				return \Redirect::to('/home') -> with('success', 'Patch created: ' . $patch_dir . '\\' . $time . '.tar.gz');
			}
			return \Redirect::to('/home') -> with('warning', 'No difference found. No patch created');
		}
		
		
		
		// https://github.com/tazotodua/useful-php-scripts 
		private function IMPORT_TABLES($host,$user,$pass,$dbname, $sql_file_OR_content){
			set_time_limit(3000);
			$SQL_CONTENT = (strlen($sql_file_OR_content) > 300 ?  $sql_file_OR_content : file_get_contents($sql_file_OR_content)  );  
			$allLines = explode("\n",$SQL_CONTENT); 
			$mysqli = new \mysqli($host, $user, $pass, $dbname); 
			if (mysqli_connect_errno()){
				return false;
				// echo "Failed to connect to MySQL: " . mysqli_connect_error();
			} 
			$zzzzzz = $mysqli->query('SET foreign_key_checks = 0');	        preg_match_all("/\nCREATE TABLE(.*?)\`(.*?)\`/si", "\n". $SQL_CONTENT, $target_tables); foreach ($target_tables[2] as $table){$mysqli->query('DROP TABLE IF EXISTS '.$table);}         $zzzzzz = $mysqli->query('SET foreign_key_checks = 1');    $mysqli->query("SET NAMES 'utf8'");	
			$templine = '';	// Temporary variable, used to store current query
			foreach ($allLines as $line)	{											// Loop through each line
				if (substr($line, 0, 2) != '--' && $line != '') {$templine .= $line; 	// (if it is not a comment..) Add this line to the current segment
					if (substr(trim($line), -1, 1) == ';') {		// If it has a semicolon at the end, it's the end of the query
						if(!$mysqli->query($templine))
						{ 
							return false;
							print('Error performing query \'<strong>' . $templine . '\': ' . $mysqli->error . '<br /><br />');  
						}  
						$templine = ''; // set variable to empty, to start picking up the lines after ";"
					}
				}
			}	
			return 'Importing finished. Now, Delete the import file.';
		}
		
		// https://github.com/tazotodua/useful-php-scripts 
		private function EXPORT_TABLES($host,$user,$pass,$name, $tables=false, $gzipped=false){ 
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
			$content .= "\r\nSET FOREIGN_KEY_CHECKS=1;\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
			
			//compress
			if($gzipped) 
			{
			if (function_exists('gzencode'))
			{
			$content = gzencode($content, 9);
			}
			}			
			return $content;
			}      //see import.php too
			
			private function logger($file=null, $str)
			{
			if($file == null) $file = public_path() . '\patch\patched_file.log';
			//write log
			file_put_contents($file, $str . PHP_EOL, FILE_APPEND | LOCK_EX);
			}
			private function recursive_rmdir($dir) { 
			if( is_dir($dir) ) { 
			$objects = array_diff( scandir($dir), array('..', '.') );
			foreach ($objects as $object) { 
			$objectPath = $dir."/".$object;
			if( is_dir($objectPath) )
			$this -> recursive_rmdir($objectPath);
			else
			unlink($objectPath); 
			} 
			rmdir($dir); 
			} 
			elseif(is_file($dir)) unlink($dir);
			}
			
			private function my_copy($s1,$s2) {
			$path = pathinfo($s2);
			if (!file_exists($path['dirname'])) {
			mkdir($path['dirname'], 0777, true);
			}   
			if (!copy($s1,$s2)) {
			echo "copy failed \n";
			}
			}
			
			private function recursive_array_search($needle,$haystack) {
			foreach($haystack as $key=>$value) {
			if($needle===$value) {
			return array($key);
			} else if (is_array($value) && $subkey = $this -> recursive_array_search($needle,$value)) {
			array_unshift($subkey, $key);
			return $subkey;
			}
			}
			}
			
			private function seed($file, $array)
			{
			file_put_contents($file, serialize($array));
			}
			
			private function getDirContents($dir, &$results = array())
			{
			$files = scandir($dir);
			$me = __FILE__;
			
			foreach($files as $key => $value){
			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
			if($path == $me) continue;
			if(!is_dir($path)) 
			{
			$results[] = [
			'md5_path' => md5($path),
			'path' => $path,
			'sha1_content' => sha1(file_get_contents($path)),
			'last_modified' => filemtime($path)
			];
			} 
			else if($value != "." && $value != "..") 
			{
			$this -> getDirContents($path, $results);
			// $results[] = $path . ' ' . filemtime($path);
			}
			}
			return $results;
			}
			}
						