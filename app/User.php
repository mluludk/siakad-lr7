<?php namespace Siakad;
	
	use Illuminate\Auth\Authenticatable;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Auth\Passwords\CanResetPassword;
	use Illuminate\Foundation\Auth\Access\Authorizable;
	use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
	use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
	use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
	
	class User extends Model implements 
	AuthenticatableContract,
	AuthorizableContract,
	CanResetPasswordContract
	{
		use Authenticatable, Authorizable, CanResetPassword;
		
		/* use \Sofa\Eloquence\Eloquence;
			protected $searchableColumns = [
			'username' => 10,
			// 'authable.nama' => 9,
			'role.name' => 9
		]; */
		/**
			* The database table used by the model.
			*
			* @var string
		*/
		protected $table = 'users';
		
		/**
			* The attributes that are mass assignable.
			*
			* @var array
		*/
		protected $guarded = [];
		
		/**
			* The attributes excluded from the model's JSON form.
			*
			* @var array
		*/
		protected $hidden = ['password', 'remember_token'];
		
		public function isAdministrator()
		{
			$role = $this -> role() -> getResults();
			return $role -> id < 2;
		}
		
		public function isImpersonating()
		{
			return \Session::has('orig_user');
		}
		
		public function authable()
		{
			return $this -> morphTo();	
		}
		
		/* http://alexsears.com/article/adding-roles-to-laravel-users/ */
		/* https://gist.github.com/drawmyattention/8cb599ee5dc0af5f4246 */
		public function role()
		{
			return $this -> hasOne('Siakad\Role', 'id', 'role_id');
		}
		
		public function hasRole($roles)
		{
			$this->have_role = $this -> getUserRole();
			if($this -> have_role -> name == 'Root') return true;
			
			
			if(is_array($roles))
			{
				foreach($roles as $needed_role)
				{
					if($this->checkIfUserHasRole($needed_role)) return true;	
				}
			}
			else
			{
				$this->checkIfUserHasRole($roles);
			}
			return false;
		}
		
		private function getUserRole()
		{	
			return $this -> role() -> getResults();	
		}
		
		private function checkIfUserHasRole($needed_role)
		{
			return (strtolower($needed_role) == strtolower($this -> have_role -> name)) ? true : false;	
		}
		
		// public function getId()
		// {
		// return $this->id;
		// }
		
		// public function getUsername()
		// {
		// return $this -> username;
		// }
	}
