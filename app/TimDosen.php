<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class TimDosen extends Model
	{
   		protected $guarded = [];
		protected $table = 'tim_dosen';
		public $timestamps = false;
		
		public function dosen()
		{
			return $this -> belongsTo('Siakad\Dosen');
		}
	}
