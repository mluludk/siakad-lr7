<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class JadwalUjianSkripsi extends Model
	{
   		protected $guarded = [];
		protected $table = 'jadwal_ujian_skripsi';
		public $timestamps = false;
		
		public function prodi()
		{
			return $this -> belongsTo(Prodi::class);
		}
		public function tapel()
		{
			return $this -> belongsTo(Tapel::class);
		}
		
		public function gelombang()
		{
			return $this -> hasMany(JadwalUjianSkripsiGelombang::class, 'jadwal_ujian_skripsi_id');
		}
	}
