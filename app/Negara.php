<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Negara extends Model
	{
		protected $table = 'negara';
		protected $guarded = [];
		public $timestamps = false;
		
		public function mahasiswa()
		{
			return $this->hasMany('Siakad\Mahasiswa', 'wargaNegara', 'kode');	
		}
	}
