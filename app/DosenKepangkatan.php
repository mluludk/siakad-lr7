<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class DosenKepangkatan extends Model {
		protected $guarded = [];
		protected $table = 'dosen_kepangkatan';
		public $timestamps = false;
		
		public function scopeRiwayatKepangkatan($query, $dosen=null, $kepangkatan=null)
		{
			$query 
			-> leftJoin('dosen', 'dosen.id', '=', 'dosen_id')
			-> orderBy(\DB::raw("STR_TO_DATE(tmt,'%d-%m-%Y')"), 'desc');
			if($dosen !== null) $query -> where('dosen_id', $dosen);
			if($kepangkatan !== null) $query -> where('dosen_kepangkatan.id', $kepangkatan);
			$query
			-> select('dosen_kepangkatan.*', 'dosen.id as dosen_id', 'dosen.nama as dosen', 'dosen.NIDN', 'dosen.jenisKelamin');
		}
	}
