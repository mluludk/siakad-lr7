<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class DosenPenelitian extends Model {
		protected $guarded = [];
		protected $table = 'dosen_penelitian';
		public $timestamps = false;
		
		public function scopeRiwayatPenelitian($query, $dosen=null, $penelitian=null)
		{
			$query 
			-> leftJoin('dosen', 'dosen.id', '=', 'dosen_id')
			-> orderBy('tahun', 'desc');
			if($dosen !== null) $query -> where('dosen_id', $dosen);
			if($penelitian !== null) $query -> where('dosen_penelitian.id', $penelitian);
			$query
			-> select('dosen_penelitian.*', 'dosen_penelitian.id AS penelitian_id', 'dosen.id as dosen_id', 'dosen.nama as dosen', 'dosen.*');
		}
	}
