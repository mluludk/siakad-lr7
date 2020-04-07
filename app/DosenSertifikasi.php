<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class DosenSertifikasi extends Model {
		protected $guarded = [];
		protected $table = 'dosen_sertifikasi';
		public $timestamps = false;
		
		public function scopeRiwayatSertifikasi($query, $dosen=null, $sertifikasi=null)
		{
			$query 
			-> leftJoin('dosen', 'dosen.id', '=', 'dosen_id')
			-> orderBy('tahun', 'desc');
			if($dosen !== null) $query -> where('dosen_id', $dosen);
			if($sertifikasi !== null) $query -> where('dosen_sertifikasi.id', $sertifikasi);
			$query
			-> select('dosen_sertifikasi.*', 'dosen.id as dosen_id', 'dosen.nama as dosen', 'dosen.NIDN', 'dosen.jenisKelamin');
		}
	}
