<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Biaya extends Model
	{
		use \Sofa\Eloquence\Eloquence;
		protected $searchableColumns = [
		'mahasiswa.nama' => 8,
		'mahasiswa.NIM' => 8,
		'jenis.nama' => 10
		];
		
		protected $guarded = [];
		protected $table = 'biaya';
		
		public function transInfo()
		{
			return $this -> morphMany('\Siakad\Neraca', 'transable');
		}
		
		public function mahasiswa()
		{
			return $this -> belongsTo('\Siakad\Mahasiswa', 'mahasiswa_id', 'id');
		}
		
		public function petugas()
		{
			return $this -> belongsTo('\Siakad\User', 'user_id', 'id');
		}
		
		public function jenis()
		{
			return $this -> belongsTo('Siakad\JenisBiaya', 'jenis_biaya_id');	
		}
	}
