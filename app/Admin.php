<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Admin extends Model
	{
		protected $guarded = [];
		protected $table = 'admin';
		
		public function authInfo()
		{
			return $this -> morphOne('\Siakad\User', 'authable');
		}
	}
