<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Neraca extends Model
	{
   		protected $guarded = [];
		protected $table = 'neraca';
		
		public function transable()
		{
			return $this -> morphTo();	
		}
		
		//no updated_at
		public function setUpdatedAtAttribute($value)
		{
		
		}
	}
