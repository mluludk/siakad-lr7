<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Gaji extends Model
	{
   		protected $guarded = [];
		protected $table = 'gaji';
		
		public function dosen()
		{
			return $this -> belongsTo('\Siakad\Dosen', 'dosen_id', 'id');	
		}
	}
