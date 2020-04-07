<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class MahasiswaJurnal extends Model {
		protected $guarded = [];
		protected $table = 'mahasiswa_jurnal';
		public $timestamps = false;
		
		public function author()
		{
			return $this -> belongsTo(\Siakad\Mahasiswa::class, 'mahasiswa_id');	
		}
		public function scopeJurnal($query, $mahasiswa=null)
		{
			$query 
			-> leftJoin('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_id')
			-> orderBy('tahun_terbit', 'desc');
			if($mahasiswa !== null) $query -> where('mahasiswa_id', $mahasiswa);
			$query
			-> select('mahasiswa_jurnal.*', 'mahasiswa_jurnal.id AS jurnal_id', 'mahasiswa.id as mahasiswa_id', 'mahasiswa.nama as mahasiswa', 'mahasiswa.*');
		}
	}
