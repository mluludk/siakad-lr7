<?php 
	/* 
		28 Mei 2017
		Check jika Profil Mahasiswa belum lengkap
	*/
	namespace Siakad\Http\Middleware;
	
	use Closure;
	
	class CheckProfil{
		public function handle($request, Closure $next)
		{
			$user = \Auth::user();
			//Only Mahasiswa
			if($user -> role_id != 512) return $next($request);
			
			$profil = $user -> authable;
			
			$checks = ['tmpLahir','tglLahir','NIK','kelurahan','hp','namaIbu','namaAyah',];
			foreach($checks as $check)
			{
				if($profil -> $check == '') return redirect('/profil/edit') -> with('warning', 'Anda harus melengkapi Data anda untuk dapat menggunakan SIAKAD');	
			}
			return $next($request);
		}
	}																		