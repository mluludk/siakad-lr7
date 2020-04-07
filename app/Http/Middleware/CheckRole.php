<?php namespace Siakad\Http\Middleware;
	
	use Closure;
	
	/* https://gist.github.com/drawmyattention/8cb599ee5dc0af5f4246 */
	class CheckRole{
		public function handle($request, Closure $next)
		{
			$roles = $this -> getRequiredRoleForRoute($request -> route());
			if($request -> user() -> hasRole($roles) || !$roles)
			{
				return $next($request);
			}
			abort('401');
			/* return response([
				'error' => [
				'code' => 'INSUFFICIENT_ROLE',
				'description' => 'Anda tidak diperbolehkan mengakses sumber daya ini.'
				]
			], 401); */			
		}
		
		private function getRequiredRoleForRoute($route)
		{
			$actions = $route -> getAction();
			return isset($actions['roles']) ? $actions['roles'] : null;
		}
	}					