<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class BimbinganSkripsi extends Model {
		protected $guarded = [];
		protected $table = 'bimbingan_skripsi';
		public $timestamps = false;
		
		public function skripsi()
		{
			return $this -> belongsTo('Siakad\Skripsi');		
		}
		
		public function author()
		{
			return $this -> belongsTo('Siakad\User', 'user_id');		
		}
	}
