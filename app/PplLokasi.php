<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class PplLokasi extends Model {
		protected $guarded = [];
		protected $table = 'ppl_lokasi';
		public $timestamps = false;
		
		public function scopeLokasiDetail($query, $lokasi_id)
		{
			$query
			-> join('ppl', 'ppl.id', '=', 'ppl_lokasi.ppl_id')
			-> join('tapel', 'ppl.tapel_id', '=', 'tapel.id')
			-> leftJoin('mahasiswa_ppl_lokasi', 'mahasiswa_ppl_lokasi.ppl_lokasi_id', '=', 'ppl_lokasi.id')
			-> orderBy('ppl_lokasi.nama')
			-> groupBy('ppl_lokasi.id')
			-> where('ppl_lokasi.id', $lokasi_id)
			-> select(
			\DB::raw('
			ppl_lokasi.nama AS lokasi, kuota, ppl_lokasi.id AS lokasi_id,
			ppl.*,
			tapel.nama AS tapel,
			count(mahasiswa_id) AS terdaftar
			')			
			);
		}
		
		public function scopeLokasiList($query, $ppl_id)
		{
			$query
			-> join('ppl', 'ppl.id', '=', 'ppl_lokasi.ppl_id')
			-> leftJoin('mahasiswa_ppl_lokasi', 'mahasiswa_ppl_lokasi.ppl_lokasi_id', '=', 'ppl_lokasi.id')
			-> orderBy('ppl_lokasi.nama')
			-> groupBy('ppl_lokasi.id')
			-> whereRaw('date(now()) BETWEEN STR_TO_DATE(tgl_mulai_daftar, "%d-%m-%Y") AND STR_TO_DATE(tgl_selesai_daftar, "%d-%m-%Y")') 
			// -> where('ppl.daftar', 'y')
			-> where('ppl_id', $ppl_id)
			-> select(
			\DB::raw('
			ppl_lokasi.nama AS lokasi, kuota, ppl_lokasi.id AS lokasi_id,
			ppl.*,
			count(mahasiswa_id) AS terdaftar
			')			
			);
		}
		
		public function ppl()
		{	
			return $this -> belongsTo(Ppl::class);	
		}
		
		public function peserta()
		{	
			return $this -> belongsToMany(Mahasiswa::class, 'mahasiswa_ppl_lokasi');	
		}
		
		public function pendamping()
		{	
			return $this -> hasMany(PplLokasiDosen::class);	
		}
	}
