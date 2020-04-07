<?php 
	/* 
		13 Sept 2019
		cek status kunci Nilai
	*/
	namespace Siakad\Http\Middleware;
	
	use Cache;
	use Closure;
	use Siakad\MatkulTapel;
	
	class CheckLockedNilai{
		public function handle($request, Closure $next)
		{
			if(!Cache::has('update_locked_nilai'))
			{
				$aktif = \Siakad\Tapel::whereAktif('y') -> first(['id']);
				
				$open = MatkulTapel::whereRaw('now() BETWEEN tanggal_mulai AND tanggal_selesai');
				
				if($aktif) $open = $open -> orWhere(function($w) use($aktif){
				$w -> where('tapel_id', $aktif -> id)
				-> whereNull('tanggal_mulai')
				-> whereNull('tanggal_selesai');
				});
				
				$open = $open -> pluck('id');
				
				if($open)
				{
					MatkulTapel::whereIn('id', $open) -> update(['locked' => 'n']);
					MatkulTapel::whereNotIn('id', $open) -> update(['locked' => 'y']);
				}
				else
				{
					MatkulTapel::update(['locked' => 'y']);
				}
				Cache::forever('update_locked_nilai', true);
			}
			return $next($request);
		}
	}																				