<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Skala extends Model
	{
   		protected $guarded = [];
		protected $table = 'skala';
		public $timestamps = false;
		
		public function prodi()
		{
			return $this->belongsTo('Siakad\Prodi');	
		}
	}
