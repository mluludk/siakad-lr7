<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class DosenFungsional extends Model {
		protected $guarded = [];
		protected $table = 'dosen_fungsional';
		public $timestamps = false;
		
		public function scopeRiwayatFungsional($query, $dosen=null, $fungsional=null)
		{
			$query 
			-> leftJoin('dosen', 'dosen.id', '=', 'dosen_id')
			-> orderBy(\DB::raw("STR_TO_DATE(tmt,'%d-%m-%Y')"), 'desc');
			if($dosen !== null) $query -> where('dosen_id', $dosen);
			if($fungsional !== null) $query -> where('dosen_fungsional.id', $fungsional);
			$query
			-> select('dosen_fungsional.*', 'dosen.id as dosen_id', 'dosen.nama as dosen', 'dosen.NIDN', 'dosen.jenisKelamin');
		}
	}
