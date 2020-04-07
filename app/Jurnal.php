<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Jurnal extends Model
	{
		protected $guarded = [];
		protected $table = 'jurnal';
		public $timestamps = false;
		
		public function ruang()
		{
			return $this -> belongsTo('Siakad\Ruang');	
		}
	}
