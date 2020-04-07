<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class DosenPenugasan extends Model {
		protected $guarded = [];
		protected $table = 'dosen_penugasan';
		public $timestamps = false;
		
		public function scopeSKTerakhir($query)
		{
			$query
			-> join('tapel', 'tapel.id', '=', 'dosen_penugasan.tapel_id')
			-> join(\DB::raw(
			"(select max(str_to_date(tgl_surat_tugas, '%d-%m-%Y')) as s_max, max(tapel.nama2) as t_max, dosen_id 
			from dosen_penugasan 
			inner join tapel on tapel.id = dosen_penugasan.tapel_id
			group by dosen_id) as dp"
			), 
			function ($q){
				$q
				-> on('dp.dosen_id', '=', 'dosen_penugasan.dosen_id')
				-> on('dp.s_max', '=', \DB::raw("str_to_date(tgl_surat_tugas, '%d-%m-%Y')"))
				-> on('dp.t_max', '=', 'tapel.nama2');
			})
			-> where('tgl_surat_tugas', '<>', '')
			-> orderBy('dp.dosen_id')
			-> select('dosen_penugasan.*');
		}
		
		/* 
			public function scopeDaftarDosen($query)
			{
			$query
			-> join('dosen', 'dosen.id', '=', 'dosen_id')
			-> join(\DB::raw("(select max(str_to_date(tgl_surat_tugas, '%d-%m-%Y')) as s_max, dosen_id from dosen_penugasan group by dosen_id) as dp"), 
			function ($q){
			$q
			-> on('dp.dosen_id', '=', 'dosen.id')
			-> on('dp.s_max', '=', \DB::raw("str_to_date(tgl_surat_tugas, '%d-%m-%Y')"));
			})
			-> join('prodi', 'prodi.id', '=', 'prodi_id')
			-> groupBy('dosen.id')
			-> orderBy('dosen.nama')
			-> select(
			'dosen.id', 'dosen.kode', 'dosen.nama', 'dosen.gelar_depan', 'dosen.gelar_belakang', 'dosen.NIDN', 'dosen.NIY', 'statusDosen', 'statusKepegawaian', 'jenisKelamin',
			'prodi_id', 'prodi.nama as nama_prodi'
			);
			} 
		*/
		
	 	public function ta_tugas()
		{
			return $this -> belongsTo('Siakad\Tapel', 'tapel_id');
		}
		public function prodi_tugas()
		{
			return $this -> belongsTo('Siakad\Prodi', 'prodi_id');
		} 
		
		public function scopeRiwayatPenugasan($query, $dosen=null, $penugasan=null, $prodi=null, $ta=null)
		{
			$query 
			-> leftJoin('dosen', 'dosen.id', '=', 'dosen_id')
			-> leftJoin('prodi', 'prodi.id', '=', 'prodi_id')
			-> leftJoin('tapel', 'tapel.id', '=', 'tapel_id')
			-> orderBy('tapel.nama', 'desc')
			-> orderBy('prodi.id');
			if($dosen !== null) $query -> where('dosen_id', $dosen);
			if($prodi !== null) $query -> where('prodi_id', $prodi);
			if($ta !== null) $query -> where('tapel_id', $ta);
			if($penugasan !== null) $query -> where('dosen_penugasan.id', $penugasan);
			$query
			-> select('dosen_penugasan.*', 'dosen.id as dosen_id', 'dosen.nama as dosen', 'dosen.NIDN', 'dosen.jenisKelamin', 'tapel.nama as tapel', 'prodi.strata', 'prodi.nama as prodi');
		}
	}
