<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Kuesioner extends Model
	{
		protected $table = 'kuesioner';
		protected $guarded = [];
		
		public function mahasiswa()
		{
			return $this -> belongsToMany('Siakad\Mahasiswa');	
		}
		
		public function skor()
		{
			return $this -> hasMany('Siakad\KuesionerMahasiswa');	
		}
	}
