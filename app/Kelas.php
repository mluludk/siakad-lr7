<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Kelas extends Model
	{
   		protected $guarded = [];
		protected $table = 'kelas';
		public $timestamps = false;
		
		public function mahasiswa()
		{
			return $this->hasMany('Siakad\Mahasiswa');	
		}
		
		public function matkul_tapel()
		{
			return $this -> hasMany('Siakad\MatkulTapel');
		}
	}
