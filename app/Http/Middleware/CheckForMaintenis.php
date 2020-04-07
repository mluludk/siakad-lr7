<?php namespace Siakad\Http\Middleware;
	
	use Auth;
	use Closure;
	use Illuminate\Contracts\Foundation\Application;
	use Symfony\Component\HttpKernel\Exception\HttpException;
	
	class CheckForMaintenis
	{
		public function __construct(Application $app)
		{
			$this -> app = $app;
		}
		
		private function isMaintenis()
		{
			return file_exists($this -> app -> storagePath() . '/framework/maintenis');
		}
		
		public function handle($request, Closure $next)
		{
			if ($this -> isMaintenis()) 
			{
				$down = parse_ini_file($this -> app -> storagePath() . '/framework/maintenis');
				
				if(Auth::check())
				{
					$role = Auth::user() -> role_id;
					if($role <= 2) return $next($request);
					if(isset($down['allowed']) && in_array($role, $down['allowed'])) return $next($request);
				}
				
				throw new HttpException(503);
			}
			
			return $next($request);
		}
	}																						