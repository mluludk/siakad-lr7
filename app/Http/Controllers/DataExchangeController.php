<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	use Maatwebsite\Excel\Facades\Excel;
	
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Siakad\Imports\MahasiswaImport;
	use Siakad\Imports\YudisiumImport;
	
	class DataExchangeController extends Controller
	{		
		use \Siakad\ProdiTrait;		
		use \Siakad\SkalaTrait;		
		
		protected $exportable = [
		'kurikulum' => 'Kurikulum',
		'transkrip_merge' => 'Transkrip Merge'
		];
		
		protected $hasTASelection = ['kelulusan'];
		
		protected $exportableWarning = [
		'akm' => 'Harap melengkapi data Nilai Mahasiswa untuk mendapatkan data yang akurat.',
		];
		
		public function importYudisiumForm()
		{
			return view('dataexchange.import_yudisium');					
		}
		public function importYudisium(Request $request)
		{
			$input = $request -> all();
			$file = $input['excel'];
			
			$ext = strtolower($file -> getClientOriginalExtension());
			$allowed_exts = ['xls', 'xlsx'];
			if(!in_array($ext, $allowed_exts))
			{
				return Redirect::back() -> withErrors(['error' => 'File yang diperbolehkan adalah: XLS dan XLSX']);
			}
			else
			{
				$date = date('Y/m/d/');
				$filename = str_random(7) . '.' . $ext;		
				$size = $file->getSize();
				$storage = \Storage::disk('files');
				$result = $storage -> put($date . $filename, \File::get($file));
				
				if($result)
				{
					$import = new YudisiumImport();
					Excel::import($import, $storage->getDriver()->getAdapter()->getPathPrefix() . $date . $filename);
					
					//delete file
					$storage -> delete($date . $filename);
					
					if($import -> getSuccess() == 0) return Redirect::route('mahasiswa.yudisium.import') 
					-> with(['danger_raw' => '<ul>
					<li>' . $import -> getFailed() . ' data gagal diproses.</li>
					<li>'. $import -> getNotRegistered() .' data Mahasiswa tidak terdaftar.</li>
					</ul>
					Pastikan semua data sudah diisi dengan benar, dan data Mahasiswa sudah terdaftar']);
					
					if($import -> getSuccess() > 0)
					{
						if($import -> getFailed() == 0) 
						return Redirect::route('mahasiswa.yudisium.import') 
						-> with('success_raw', $import -> getSuccess() . ' data berhasil dimasukkan');
						else
						return Redirect::route('mahasiswa.yudisium.import') 
						-> with('warning_raw', '
						<ul>
						<li>' . $import -> getSuccess() . ' data berhasil dimasukkan.</li>
						<li>' . $import -> getFailed() . ' data gagal diproses.</li>
						<li>'. $import -> getNotRegistered() .' data tidak ditemukan.</li>
						</ul>');
					}
					
				}
				return Redirect::route('mahasiswa.yudisium.import') -> withErrors(['error' => 'Upload file gagal']);
			}
		}		
		
		public function importForm()
		{
			$prodi = \Siakad\Prodi::all();
			if(!$prodi -> count()) return Redirect::to('/') -> withErrors(['NOT_FOUND' => 'Data Program Stud belum diisi.']);
			return view('dataexchange.import', compact('prodi'));		
		}
		
		public function import(Request $request)
		{
			$input = $request -> all();
			$file = $input['excel'];
			$prodi_id = $input['prodi_id'];
			
			$ext = strtolower($file -> getClientOriginalExtension());
			$allowed_exts = ['xls', 'xlsx'];
			if(!in_array($ext, $allowed_exts))
			{
				return Redirect::back() -> withErrors(['error' => 'File yang diperbolehkan adalah: XLS dan XLSX']);
			}
			else
			{
				$date = date('Y/m/d/');
				$filename = str_random(7);			
				
				$filename = str_slug($filename) . '.' . $file->getClientOriginalExtension();		
				$size = $file->getSize();
				$storage = \Storage::disk('files');
				$result = $storage -> put($date . $filename, \File::get($file));
				
				if($result)
				{
					$import = new MahasiswaImport($prodi_id);
					Excel::import($import, $storage->getDriver()->getAdapter()->getPathPrefix() . $date . $filename);
				}
				//delete file
				$storage -> delete($date . $filename);
				
				if($import -> getSuccess() == 0) return Redirect::route('mahasiswa.import') 
				-> with(['danger_raw' => '<ul>
				<li>' . $import -> getFailed() . ' data gagal diproses.</li>
				<li>'. $import -> getRegistered() .' data sudah terdaftar.</li>
				</ul>
				Pastikan semua data sudah diisi dengan benar, dan data Mahasiswa belum terdaftar']);
				
				if($import -> getSuccess() > 0)
				{
					if($import -> getFailed() == 0) 
					return Redirect::route('mahasiswa.import') 
					-> with('success_raw', $import -> getSuccess() . ' data berhasil dimasukkan. Login untuk SIAKAD dan SENAYAN berhasil dibuat dengan<br/>
					Username: <strong>[NIM Mahasiswa]</strong><br/>
					Password: <strong>' . $import -> getPassword() . '</strong>');
					else
					return Redirect::route('mahasiswa.import') 
					-> with('warning_raw', '
					<ul>
					<li>' . $import -> getSuccess() . ' data berhasil dimasukkan.</li>
					<li>' . $import -> getFailed() . ' data gagal diproses.</li>
					<li>'. $import -> getRegistered() .' data sudah terdaftar.</li>
					</ul>
					Login untuk SIAKAD dan SENAYAN berhasil dibuat dengan<br/>
					Username: <strong>[NIM Mahasiswa]</strong><br/>
					Password: <strong>' . $import -> getPassword() . '</strong>');
				}
			}
		}
		
		public function exportKurikulum()
		{
			$kurikulum = \DB::select("
			SELECT k.*, p.strata, p.nama AS prodi, p.singkatan AS singk, t.nama as tapel,
			SUM(CASE WHEN km.wajib='y' then m.sks_total else 0 end) as j_sks_wajib,
			SUM(CASE WHEN km.wajib='n' then m.sks_total else 0 end) as j_sks_pilihan 
			from `kurikulum` k
			left join kurikulum_matkul km on km.kurikulum_id = k.id
			left join matkul m on m.id = km.matkul_id
			left join prodi p on p.id = k.prodi_id
			left join tapel t on t.id = k.tapel_mulai
			group by k.id
			order by nama2 desc
			");
			return view('dataexchange.kurikulum', compact('kurikulum'));	
		}
		
		public function export($data)
		{
			$exportable = $this -> exportable;
			$exportableWarning = $this -> exportableWarning;
			
			if(!array_key_exists($data, $exportable)) abort(404);
			$format = ['xlsx'];			
			
			$hasTASelection = $this -> hasTASelection;
			$tapel = null;
			if(in_array($data, $hasTASelection)) 
			{	
				$raw = \Siakad\Tapel::orderBy('nama') -> pluck('nama', 'id');
				$tapel = ['-- Pilih Tahun Akademik --'];
				foreach($raw as $k => $v) $tapel[$k] = $v;
			}
			
			if($data == 'transkrip_merge')
			{
				$angkatan = null;
				$raw = \DB::select('
				select mahasiswa.angkatan, count(angkatan) as jumlah
				from mahasiswa
				group by angkatan
				order by angkatan desc
				');
				$angkatan = ['Semua angkatan'];
				foreach($raw as $a) $angkatan[$a -> angkatan] = $a -> angkatan;
				
				$prodi = $this -> getProdiSelection('id', 'asc', false, 'singkatan');
				
				return view('dataexchange.export_angkatan', compact('exportable', 'exportableWarning', 'data', 'format', 'prodi', 'angkatan'));		
			}
			
			$prodi = \Siakad\Prodi::all();
			return view('dataexchange.export', compact('exportable', 'exportableWarning', 'data', 'format', 'prodi', 'tapel', 'angkatan'));		
		}
		
	public function exportInto($data, $singk, $type, $var=null) // export > pddikti > kurikulum  | kopertais > transkrip
		{
			if(!array_key_exists($data, $this -> exportable)) abort(404);			
			$hasTASelection = $this -> hasTASelection;
			$rdata = null;
			// $title = $this -> exportable[$data] . ' untuk import ke PDDIKTI FEEDER';
			$title = $this -> exportable[$data];
			$tpl = $data . '_tpl';
			
			$prodi = \Siakad\Prodi::whereSingkatan($singk) -> first();
			$ta = '';
			
			if(in_array($data, $hasTASelection)) 
			{
				$tapel2 = isset($tapel) && isset($tapel -> nama2) ? $tapel : ($var == null ? \Siakad\Tapel::whereAktif('y') -> first() : \Siakad\Tapel::whereId($var) -> first()); 
				$ta = $tapel2 -> nama2 . '-';
			}
			
			if($data == 'transkrip_merge')
			{
				$angkatan = null;
				if(intval($var) > 0)
				{
					$angkatan = $var;
					$ta = $var . '-';
				}
			}
			
			$filename = strtoupper(str_slug($this -> exportable[$data])) . '-' . $ta . $prodi -> singkatan  . '-' . date('Y-m-d H-i-s');
			
			switch($data)
			{
				case 'kurikulum':
				$rdata = \DB::select("
				select m.*, km.semester,kode_dikti from kurikulum k
				left join kurikulum_matkul km on k.id = km.kurikulum_id
				left join matkul m on m.id = km.matkul_id
				join prodi p on p.id = k.prodi_id
				where k.id = :kid
				", ['kid' => $var]);	
				break;
				
				case 'transkrip_merge':
				$skala = $this -> skala($prodi -> id);
				$tm = \Siakad\Nilai::TranskripMerge($prodi -> id, $angkatan) -> get();
				$mk = \Siakad\MatkulTapel::matkulProdi($prodi -> id, $angkatan)  -> get();
				$konversi = config('custom.konversi_nilai.base_4');
				$tmp = null;
				foreach($tm as $t)
				{
					$tmp[$t -> id]['nilai'][$t -> matkul] = $t -> nilai;
					$tmp[$t -> id]['nsks'][$t -> matkul] = $t -> nilai != '' && $t -> nilai != '-' ? $konversi[$t -> nilai] * $t -> sks : '';
					$tmp[$t -> id]['sks'][$t -> matkul] = $t -> sks;
					$tmp[$t -> id]['nama'] = htmlspecialchars($t -> nama);
					$tmp[$t -> id]['ttl'] = $t -> tmpLahir . ', ' . $t -> tglLahir;
					$tmp[$t -> id]['npm'] = $t -> NIM;
					$tmp[$t -> id]['nirm'] = $t -> NIRM;
					$tmp[$t -> id]['nirl1'] = $t -> NIRL1;
					$tmp[$t -> id]['nirl2'] = $t -> NIRL2;
					$tmp[$t -> id]['judul'] = htmlspecialchars($t -> judul);
				}
				$rdata = $tmp != null ? compact('tmp', 'mk', 'skala') : null;
				break;
			}
			
			if($rdata === null or $rdata === false) return view('dataexchange.empty');
			// dd($rdata['tmp']);
			if($type == 'xlsx')
			{
		// return view('dataexchange.transkrip_merge_tpl', ['rdata' => $rdata]);
				return \Excel::download(new \Siakad\Exports\DataExport('dataexchange.' . $tpl, $rdata), $title . '.xlsx');
			}	
		}	
	}
