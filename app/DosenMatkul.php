<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class DosenMatkul extends Model
	{
		protected $guarded = [];
		protected $table = 'dosen_matkul';
		
		public $timestamps = false;
		
/* 		public function matkul_data()
		{
			return $this -> belongsTo('Siakad\Matkul');	
		} */
	}
