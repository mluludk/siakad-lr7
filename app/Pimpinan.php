<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Pimpinan extends Model
	{
		protected $guarded = [];
		protected $table = 'pimpinan';
		public $timestamps = false;
		
		public function dosen()
		{
			return $this -> belongsTo('\Siakad\Dosen');	
		}
	}
