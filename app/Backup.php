<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Backup extends Model {
		protected $guarded = [];
		protected $table = 'backup';
		public $timestamp = false;
		
		public function executor()
		{
			return $this -> belongsTo('Siakad\User', 'user');		
		}
	}
