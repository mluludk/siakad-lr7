<?php namespace Siakad\Http\Middleware;
	
	use Closure;
	
	class CheckKuesioner{
		public function handle($request, Closure $next)
		{
			//pass another user type
			if(\Auth::user() -> role_id != 512) return $next($request);
			
			//pass if outside time range
			$config = config('custom.kuesioner');			
			$now = time();
			if($now < strtotime($config['tgl-mulai'] . ' 00:00:00') || $now > strtotime($config['tgl-selesai'] . ' 23:59:59')) return $next($request);
			
			$mhs_id = \Auth::user() -> authable_id;	
			
			$result =  \Cache::get('completed_course_' . $mhs_id, function() use($mhs_id){
				
				$result = \Siakad\Nilai::getCompletedCourse($mhs_id) -> get();
				
				\Cache::put('completed_course_' . $mhs_id, $result, 10); return $result;
			});
			
			if($result -> count())
			{
				//seed
				$this -> seedKuesioner($mhs_id, $result);
				
				if($this ->  checkKuesioner($mhs_id) > 0)
				{
					// check null
					if($this ->  checkNullKuesioner($mhs_id) > 0)
					{
						return redirect('/penilaian');	
					}
				}
				else
				{
					return redirect('/penilaian');	
				}
			}	
			return $next($request);
		}
		
		//seed 
		private function seedKuesioner($mhs_id, $completed_course)
		{
			foreach($completed_course as $c)
			{
				\DB::insert("
				INSERT IGNORE INTO kuesioner_mahasiswa (kuesioner_id, mahasiswa_id, matkul_tapel_id, created_at) 
				SELECT id, :mhs_id, :mt_id, now() FROM kuesioner WHERE tampil = 'y'", 
				['mhs_id' => $mhs_id, 'mt_id' => $c -> mtid]
				);	
			}
		}
		
		private function checkKuesioner($mhs_id)
		{			
			$result = \DB::select("
			SELECT COUNT(*) AS poin
			FROM kuesioner_mahasiswa km 
			WHERE km.mahasiswa_id = :mhs_id AND km.matkul_tapel_id IN (
			SELECT mt.id 
			FROM matkul_tapel mt 
			INNER JOIN tapel 
			ON tapel.id = mt.tapel_id 
			WHERE mt.tapel_id in (select tapel.id from tapel where tapel.nama2 < (select tapel.nama2 from tapel where tapel.aktif = 'y'))
			)",
			['mhs_id' => $mhs_id]
			);
			return $result[0] -> poin;
		}
		
		private function checkNullKuesioner($mhs_id)
		{
			$result = \DB::select("
			SELECT COUNT(*) AS isnull 
			FROM kuesioner_mahasiswa km 
			WHERE km.mahasiswa_id = :mhs_id AND (km.skor IS NULL OR km.skor = 0) AND km.matkul_tapel_id IN (
			SELECT mt.id 
			FROM matkul_tapel mt 
			INNER JOIN tapel 
			ON tapel.id = mt.tapel_id 
			WHERE mt.tapel_id in (select tapel.id from tapel where tapel.nama2 < (select tapel.nama2 from tapel where tapel.aktif = 'y'))
			)",
			['mhs_id' => $mhs_id]
			);
			return $result[0] -> isnull;
		}
	}	
	