<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class MahasiswaTulisan extends Model {
		protected $guarded = [];
		protected $table = 'mahasiswa_tulisan';
		public $timestamps = false;
		
		public function author()
		{
			return $this -> belongsTo(\Siakad\Mahasiswa::class, 'mahasiswa_id');	
		}
		public function scopeTulisan($query, $mahasiswa=null)
		{
			$query 
			-> leftJoin('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_id')
			-> orderBy('tahun', 'desc');
			if($mahasiswa !== null) $query -> where('mahasiswa_id', $mahasiswa);
			$query
			-> select('mahasiswa_tulisan.*', 'mahasiswa_tulisan.id AS tulisan_id', 'mahasiswa.id as mahasiswa_id', 'mahasiswa.nama as mahasiswa', 'mahasiswa.*');
		}
	}
