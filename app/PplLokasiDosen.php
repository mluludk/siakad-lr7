<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class PplLokasiDosen extends Model {
		protected $guarded = [];
		protected $table = 'ppl_lokasi_dosen';
		public $timestamps = false;
		
		public function scopePendampingPpl($query, $ppl_id)
		{
			$query 
			-> join('ppl_lokasi', 'ppl_lokasi.id', '=', 'ppl_lokasi_dosen.ppl_lokasi_id')
			-> join('dosen', 'dosen.id', '=', 'ppl_lokasi_dosen.dosen_id')
			-> where('ppl_id', $ppl_id)
			-> select(
			'ppl_lokasi_id', 'dosen_id',
			'dosen.nama', 'dosen.gelar_belakang', 'dosen.gelar_depan'
			);
		}
		public function dosen()
		{	
			return $this -> hasOne(Dosen::class, 'id', 'dosen_id');	
		}
	}
