<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class PkmLokasi extends Model {
		protected $guarded = [];
		protected $table = 'pkm_lokasi';
		public $timestamps = false;
		
		public function scopeLokasiDetail($query, $lokasi_id)
		{
			$query
			-> join('pkm', 'pkm.id', '=', 'pkm_lokasi.pkm_id')
			-> join('tapel', 'pkm.tapel_id', '=', 'tapel.id')
			-> leftJoin('mahasiswa_pkm_lokasi', 'mahasiswa_pkm_lokasi.pkm_lokasi_id', '=', 'pkm_lokasi.id')
			-> orderBy('pkm_lokasi.nama')
			-> groupBy('pkm_lokasi.id')
			-> where('pkm_lokasi.id', $lokasi_id)
			-> select(
			\DB::raw('
			pkm_lokasi.nama AS lokasi, kuota, pkm_lokasi.id AS lokasi_id,
			pkm.*,
			tapel.nama AS tapel,
			count(mahasiswa_id) AS terdaftar
			')			
			);
		}
		
		public function scopeLokasiList($query, $pkm_id)
		{
			$query
			-> join('pkm', 'pkm.id', '=', 'pkm_lokasi.pkm_id')
			-> leftJoin('mahasiswa_pkm_lokasi', 'mahasiswa_pkm_lokasi.pkm_lokasi_id', '=', 'pkm_lokasi.id')
			-> orderBy('pkm_lokasi.nama')
			-> groupBy('pkm_lokasi.id')
			-> whereRaw('date(now()) BETWEEN STR_TO_DATE(tgl_mulai_daftar, "%d-%m-%Y") AND STR_TO_DATE(tgl_selesai_daftar, "%d-%m-%Y")') 
			-> where('pkm_id', $pkm_id)
			-> select(
			\DB::raw('
			pkm_lokasi.nama AS lokasi, kuota, pkm_lokasi.id AS lokasi_id,
			pkm.*,
			count(mahasiswa_id) AS terdaftar
			'));
		}
		
		public function pkm()
		{	
			return $this -> belongsTo(Pkm::class);	
		}
		
		public function peserta()
		{	
			return $this -> belongsToMany(Mahasiswa::class, 'mahasiswa_pkm_lokasi');	
		}
		
		public function pendamping()
		{	
			return $this -> hasMany(PkmLokasiDosen::class);	
		}
		
		public function matkul()
		{	
			return $this -> hasMany(PkmLokasiMatkul::class);	
		}
	}
