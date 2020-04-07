<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Tapel extends Model
	{
		protected $guarded = [];
		protected $table = 'tapel';
		public $timestamps = false;
		
		public function matkul()
		{
			return $this -> belongsToMany('Siakad\Matkul');
		}
		public function matkul_tapel()
		{
			return $this -> hasMany('Siakad\MatkulTapel');
		}
		public function setting()
		{
			return $this -> hasMany('Siakad\SettingTapel');
		}
		
	}
