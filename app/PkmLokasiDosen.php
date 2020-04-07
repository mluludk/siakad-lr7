<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class PkmLokasiDosen extends Model {
		protected $guarded = [];
		protected $table = 'pkm_lokasi_dosen';
		public $timestamps = false;
		
		public function scopePendampingPkm($query, $pkm_id)
		{
			$query 
			-> join('pkm_lokasi', 'pkm_lokasi.id', '=', 'pkm_lokasi_dosen.pkm_lokasi_id')
			-> join('dosen', 'dosen.id', '=', 'pkm_lokasi_dosen.dosen_id')
			-> where('pkm_id', $pkm_id)
			-> select(
			'pkm_lokasi_id', 'dosen_id',
			'dosen.nama', 'dosen.gelar_belakang', 'dosen.gelar_depan'
			);
		}
		public function dosen()
		{	
			return $this -> hasOne(Dosen::class, 'id', 'dosen_id');	
		}
	}
