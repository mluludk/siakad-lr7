<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Prodi extends Model
	{
		protected $guarded = [];
		protected $table = 'prodi';
		public $timestamps = false;
		
		public function fakultas()
		{
			return $this -> belongsTo(Fakultas::class);
		}
		
		public function kepala()
		{
			return $this -> belongsTo(Dosen::class, 'kaprodi_id');
		}
		
		public function riwayat()
		{
			return $this -> hasMany(ProdiRiwayat::class);
		}
	}
