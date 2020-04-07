<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class AbsensiDosen extends Model
	{
		protected $table = 'absensi_dosen';
		protected $guarded = [];
		public $timestamps = false;
		
		public function dosen()
		{
			return $this -> belongsTo('Siakad\Dosen', 'dosen_id', 'id');	
		}
	}
