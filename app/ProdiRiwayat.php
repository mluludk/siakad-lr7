<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class ProdiRiwayat extends Model
	{
   		protected $guarded = [];
		protected $table = 'prodi_riwayat';
		public $timestamps = false;
		
		public function prodi()
		{
			return $this -> belongsTo(Prodi::class);
		}
	}
