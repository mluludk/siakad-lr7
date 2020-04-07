<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class MahasiswaBuku extends Model {
		protected $guarded = [];
		protected $table = 'mahasiswa_buku';
		public $timestamps = false;
		
		public function author()
		{
			return $this -> belongsTo(\Siakad\Mahasiswa::class, 'mahasiswa_id');	
		}
		public function scopeBuku($query, $mahasiswa=null)
		{
			$query 
			-> leftJoin('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_id')
			-> orderBy('tahun_terbit', 'desc');
			if($mahasiswa !== null) $query -> where('mahasiswa_id', $mahasiswa);
			$query
			-> select('mahasiswa_buku.*', 'mahasiswa_buku.id AS buku_id', 'mahasiswa.id as mahasiswa_id', 'mahasiswa.nama as mahasiswa', 'mahasiswa.*');
		}
	}
