<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class JadwalPengajuanSkripsi extends Model
	{
   		protected $guarded = [];
		protected $table = 'jadwal_pengajuan_skripsi';
		public $timestamps = false;
		
		public function prodi()
		{
			return $this -> belongsTo(Prodi::class);
		}
		public function gelombang()
		{
			return $this -> hasMany(JadwalPengajuanSkripsiGelombang::class, 'jadwal_pengajuan_skripsi_id');
		}
	}
