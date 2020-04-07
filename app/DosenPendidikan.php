<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class DosenPendidikan extends Model {
		protected $guarded = [];
		protected $table = 'dosen_pendidikan';
		public $timestamps = false;
		
		public function scopeRiwayatPendidikan($query, $dosen=null, $pendidikan=null)
		{
			$query 
			-> leftJoin('dosen', 'dosen.id', '=', 'dosen_id')
			-> orderBy('jenjang', 'desc');
			if($dosen !== null) $query -> where('dosen_id', $dosen);
			if($pendidikan !== null) $query -> where('dosen_pendidikan.id', $pendidikan);
			$query
			-> select('dosen_pendidikan.*', 'dosen.id as dosen_id', 'dosen.nama as dosen', 'dosen.NIDN', 'dosen.jenisKelamin');
		}
	}
