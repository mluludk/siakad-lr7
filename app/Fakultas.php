<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Fakultas extends Model
	{
		public $guarded = [];
		public $table = 'fakultas';
		public $timestamps = false;
		
		public function prodi()
		{
			return $this -> hasMany(Prodi::class);
		}
	}
