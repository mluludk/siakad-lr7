<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class DosenJurnal extends Model {
		protected $guarded = [];
		protected $table = 'dosen_jurnal';
		public $timestamps = false;
		
		public function scopeJurnal($query, $dosen=null, $jurnal=null)
		{
			$query 
			-> leftJoin('dosen', 'dosen.id', '=', 'dosen_id')
			-> orderBy('tahun_terbit', 'desc');
			if($dosen !== null) $query -> where('dosen_id', $dosen);
			if($jurnal !== null) $query -> where('dosen_jurnal.id', $jurnal);
			$query
			-> select('dosen_jurnal.*', 'dosen_jurnal.id AS jurnal_id', 'dosen.id as dosen_id', 'dosen.nama as dosen', 'dosen.*', 'dosen.jenisKelamin');
		}
	}
