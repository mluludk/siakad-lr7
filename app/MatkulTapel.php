<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class MatkulTapel extends Model
	{
		protected $guarded = [];
		protected $table = 'matkul_tapel';
		public $timestamps = false;
		
		//FEEDER
		public function scopeKelasKuliahFeeder($query, $tapel_id, $prodi_id_dikti)
		{
			$query 
			-> join('tapel', 'tapel.id', '=', 'matkul_tapel.tapel_id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')			
			-> join('matkul', 'matkul.id', '=', 'kurikulum_matkul.matkul_id')
			-> join('prodi', 'prodi.id', '=', 'matkul_tapel.prodi_id')
			-> where('tapel.nama2', $tapel_id)
			-> where('prodi.kode_dikti', $prodi_id_dikti)
			-> orderBy('kode')
			-> orderBy('kelas')
			-> orderBy('kelas2')
			-> select(
			'matkul_tapel.id as id', 'nama2 as semester', 'matkul.kode', 'matkul.nama as nama_matkul', 
			'kurikulum_matkul.semester as kelas', 'kelas2', 'matkul.sks_total as sks',
			'strata', 'prodi.nama as nama_prodi'
			);	
		}
		
		//PKM / PPL
		public function scopeMatkulList($query, $prodi_id=null)
		{
			$query
			-> join('prodi', 'prodi.id', '=', 'matkul_tapel.prodi_id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('kurikulum', 'kurikulum_matkul.kurikulum_id', '=', 'kurikulum.id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id');
			
			if($prodi_id !== null)
			$query -> where('matkul_tapel.prodi_id', $prodi_id);
			
			$query
			// -> where('tapel.aktif', 'y')
			-> where('semester', 7)
			-> distinct()
			-> select(
			'matkul_tapel.prodi_id',
			'matkul.id AS matkul_id', 'matkul.nama AS matkul', 'kode',
			'prodi.singkatan', 'prodi.strata'
			);
		}
		
		public function scopeTawaranMatkul($query, $prodi_id, $program, $semester, $mahasiswa_id, $in_program=true)
		{
			$query
			-> leftJoin('krs_detail', 'krs_detail.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> leftJoin('krs', 'krs_detail.krs_id', '=', 'krs.id')
			-> join('tapel', 'matkul_tapel.tapel_id', '=', 'tapel.id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id')
			-> join('kelas', 'matkul_tapel.kelas', '=', 'kelas.id')
			
			-> where('matkul_tapel.prodi_id', $prodi_id)
			-> whereRaw('matkul_tapel.id NOT IN (
			SELECT matkul_tapel_id 
			FROM krs_detail 
			INNER JOIN krs ON krs.id = krs_detail.krs_id  
			WHERE krs.mahasiswa_id = '. $mahasiswa_id .'
			)')
			-> whereRaw('matkul.id NOT IN (
			SELECT matkul.id 
			FROM matkul 
			INNER JOIN `kurikulum_matkul` on `kurikulum_matkul`.`matkul_id` = `matkul`.`id`
			INNER JOIN matkul_tapel ON `kurikulum_matkul`.`id` = `matkul_tapel`.`kurikulum_matkul_id`
			INNER JOIN krs_detail ON krs_detail.matkul_tapel_id = matkul_tapel.id
			INNER JOIN krs ON krs.id = krs_detail.krs_id 
			WHERE krs.mahasiswa_id = '. $mahasiswa_id .'
			)');
			
			if($in_program) $query -> where('matkul_tapel.kelas', $program);
			else $query -> where('matkul_tapel.kelas', '<>', $program);
			
			$query 
			-> where('semester', $semester)
			-> where('tapel.aktif', 'y')
			-> groupBy('matkul_tapel.id')
			-> select(\DB::raw('
			matkul_tapel.id, matkul_tapel.kelas2, matkul_tapel.kuota, matkul_tapel.id AS mtid,
			matkul.id AS mid, matkul.kode, matkul.nama AS nama_matkul, matkul.sks_total AS sks, 
			prodi.singkatan AS nama_prodi,
			kelas.nama AS program,
			kurikulum_matkul.semester,
			tapel.id AS tapel_id,
			(SELECT COUNT(krs_detail.matkul_tapel_id) FROM krs_detail WHERE matkul_tapel_id = mtid) peserta
			'));
		}
		public function scopeMatkulProdi($query, $prodi, $angkatan)
		{
			$query
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('kurikulum', 'kurikulum_matkul.kurikulum_id', '=', 'kurikulum.id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> where('matkul_tapel.prodi_id', $prodi)
			-> where('kurikulum.angkatan', $angkatan)
			-> orderBy('semester')
			-> distinct()
			-> select('matkul.nama');
		}
		public function scopeExportDataKelas($query, $prodi, $ta=null)
		{
			$query
			-> join('tapel', 'tapel_id', '=', 'tapel.id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id');
			
			if($ta == null) $query -> where('tapel.aktif', 'y');
			else $query -> where('tapel.id', $ta);
			
			$query
			-> where('matkul_tapel.prodi_id', $prodi)
			-> select('tapel.nama2 AS tapel', 'matkul.kode', 'matkul.nama', 'kurikulum_matkul.semester', 'kelas2 AS kelas', 'kode_dikti');
		}
		
		public function scopeKelasKuliah($query, $ta = null, $prodi = null, $dosen = null)
		{
			$query
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			
			// -> join('dosen', 'dosen.id', '=', 'matkul_tapel.dosen_id')
			-> join('tim_dosen', 'tim_dosen.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('dosen', 'dosen.id', '=', 'tim_dosen.dosen_id')
			
			-> join('tapel', 'tapel_id', '=', 'tapel.id')
			-> join('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id')
			-> join('kelas', 'matkul_tapel.kelas', '=', 'kelas.id')
			
			-> leftJoin('nilai', 'nilai.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> leftJoin('jadwal', 'jadwal.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> leftJoin('ruang', 'jadwal.ruang_id', '=', 'ruang.id');
			
			if($ta == null) $query -> where('tapel.aktif', 'y');
			else $query -> where('tapel.id', $ta);
			
			if($prodi != null) $query -> where('matkul_tapel.prodi_id', $prodi);
			
			if($dosen != null) $query -> where('tim_dosen.dosen_id', $dosen);
			
			$query	
			-> where('kurikulum_matkul.matkul_id', '<>', 0)
			-> where(function($query){
				$query -> where('nilai.jenis_nilai_id', 0)
				-> orWhere('nilai.jenis_nilai_id', NULL);		
			}) 
			
			-> groupBy('matkul_tapel.id')
			-> groupBy('matkul_tapel.kelas')
			// -> groupBy('dosen.id')
			
			-> orderBy('kurikulum_matkul.semester')
			-> orderBy('kelas.id')		
			
			-> select(\DB::raw('IFNULL(COUNT(mahasiswa_id), 0) AS `peserta`,
			`matkul_tapel`.`keterangan`,`matkul_tapel`.`kuota`, `matkul_tapel`.`kelas2` as `kelas`, `matkul_tapel`.`id` as `mtid`, 
			(select count(nilai) from nilai where jenis_nilai_id = 1 and matkul_tapel_id = mtid and CAST(nilai AS UNSIGNED) > 0) nilai,
			`matkul`.`sks_total` as `sks`,
			`kurikulum_matkul`.`semester`,
			`tapel`.`id` as `tapel_id`,
			`tapel`.`nama` as `tapel`,
			`tapel`.`nama2` as `nama_tapel2`,
			`matkul`.`nama` as `matkul`,`matkul`.`kode`,
			`locked`,
			`ruang`.`nama` AS `ruangan`,
			
			`prodi`.`singkatan` as `prodi`,
			`kelas`.`nama` as `program`, `matkul_tapel`.`id`'));
			//`dosen`.`nama` as `dosen`,
		}
		
		public function scopeMatkulAktif($query, $prodi = false)
		{
			$query
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('kurikulum', 'kurikulum.id', '=', 'kurikulum_matkul.kurikulum_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('tapel', 'tapel_id', '=', 'tapel.id')
			-> join('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id')
			-> join('tim_dosen', 'tim_dosen.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('dosen', 'dosen.id', '=', 'tim_dosen.dosen_id')
			// -> join('dosen', 'matkul_tapel.dosen_id', '=', 'dosen.id')
			-> join('kelas', 'matkul_tapel.kelas', '=', 'kelas.id')
			-> where('tapel.aktif', 'y');
			
			if($prodi) $query -> where('prodi.singkatan', \Auth::user() -> role -> sub);
			
			$query -> orderBy('matkul')
			-> select(
			'kurikulum.nama AS kur',
			'matkul.nama AS matkul', 'matkul.kode AS kd', 'matkul.sks_total AS sks', 
			'matkul_tapel.id as mtid', 'matkul_tapel.kelas2 AS kelas', 
			'kurikulum_matkul.semester',
			'prodi.singkatan AS prodi', 
			'dosen.nama AS dosen', 
			'kelas.nama AS program', 
			'kelas.id AS program_id');
		}
		
		public function scopeMataKuliahDosen($query, $dosen = null)
		{
			$query
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('tim_dosen', 'tim_dosen.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('dosen', 'dosen.id', '=', 'tim_dosen.dosen_id')
			// -> join('dosen', 'dosen_id', '=', 'dosen.id')
			-> join('tapel', 'tapel_id', '=', 'tapel.id')
			-> join('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id');
			
			if($dosen == null) 
			{
				$query 
				-> where('tim_dosen.dosen_id', \Auth::user() -> authable -> id)
				-> where(function($q){
					$q
					-> where('tapel.aktif', 'y')
					-> orWhere('tapel.check_nilai_sem_non_aktif', 'y')
					-> orWhere(function($q){
						$q
						-> where('matkul_tapel.locked', 'n')
						-> orWhereRaw('("' . date('Y-m-d') . '" BETWEEN matkul_tapel.tanggal_mulai AND matkul_tapel.tanggal_selesai)');
					});
				});
				
			}
			else
			{
				$query -> where('tim_dosen.dosen_id', $dosen);
			}
			
			$query 
			-> orderBy('tapel.nama2', 'desc')
			-> select(
			'tapel.nama AS ta', 
			'prodi.nama AS prodi', 'prodi.singkatan AS singk', 'prodi.strata AS strata', 
			'matkul.nama AS matkul', 'matkul.kode', 
			'matkul_tapel.*', 
			'matkul.sks_total as sks', 
			'kurikulum_matkul.semester'
			);
		}
		
		public function scopeGetMatkulId($query, $matkul_tapel_id)
		{
			$query 
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> where('matkul_tapel.id', $matkul_tapel_id)
			-> select('matkul_id');
		}
		
		public function scopeGetDataMataKuliah($query, $matkul_tapel_id)
		{
			$query 
			-> leftJoin('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> leftJoin('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('tim_dosen', 'tim_dosen.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('dosen', 'dosen.id', '=', 'tim_dosen.dosen_id')
			-> leftJoin('tapel', 'matkul_tapel.tapel_id', '=', 'tapel.id')
			-> leftJoin('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id')
			-> join('dosen AS kaprodi', 'kaprodi.id', '=', 'prodi.kaprodi_id')
			-> leftJoin('jadwal', 'jadwal.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> leftJoin('ruang', 'ruang_id', '=', 'ruang.id')
			-> join('kelas', 'matkul_tapel.kelas', '=', 'kelas.id')
			-> where('matkul_tapel.id', '=', $matkul_tapel_id)	
			-> select(
			'matkul.nama AS matkul', 'matkul.kode AS kd', 'matkul.id AS idmk', 'matkul.sks_total',
			'matkul_tapel.*', 'matkul_tapel.kelas2 AS kelas', 'matkul_tapel.kelas AS program_id', 'tanggal_mulai', 'tanggal_selesai',
			'kurikulum_matkul.semester', 
			'dosen.nama AS dosen', 'dosen.id AS dosen_id', 'dosen.*', 
			'tapel.nama AS ta', 'tapel.id AS tapel_id', 'tapel.nama2 AS ta2',
			'prodi.nama AS prodi', 'prodi.singkatan', 'prodi.id AS prodi_id', 'prodi.kode_dikti', 'strata',
			'kaprodi.gelar_depan AS k_gelar_depan', 'kaprodi.nama AS k_nama', 'kaprodi.gelar_belakang AS k_gelar_belakang','kaprodi.ttd AS k_ttd',
			'jadwal.*', 
			'ruang.nama as ruang', 
			'kelas.nama AS program',
			'matkul.kode',
			'matkul_tapel.id'
			);
		}
		
		public function sesi()
		{
			return $this-> hasMany('Siakad\SesiPembelajaran', 'matkul_tapel_id');	
		}
		
		public function meeting()
		{
			return $this-> hasMany('Siakad\MatkulTapelMeeting', 'matkul_tapel_id');	
		}
		
		public function jadwal()
		{
			return $this-> hasMany('Siakad\Jadwal');	
		}
		
		public function jurnal()
		{
			return $this-> hasMany('Siakad\Jurnal');	
		}
		
		public function nilaiAkhir()
		{
			return $this-> hasMany('Siakad\Nilai') -> where('jenis_nilai_id', 0);	
		}
		
		public function mahasiswa()
		{
			return $this-> belongsToMany('Siakad\Mahasiswa', 'nilai') -> where('jenis_nilai_id', 0) -> orderBy('nama') ;	
		}
		
		public function kurikulum()
		{
			return $this -> belongsTo('Siakad\KurikulumMatkul', 'kurikulum_matkul_id');
		}
		
		public function prodi()
		{
			return $this -> belongsTo('Siakad\Prodi');
		}
		
		public function program()
		{
			return $this -> belongsTo('Siakad\Kelas', 'kelas');
		}
		
		public function ruang()
		{
			return $this -> belongsTo('Siakad\Ruang');
		}
		
		public function tapel()
		{
			return $this -> belongsTo('Siakad\Tapel');
		}
		
		public function tim_dosen()
		{
			return $this -> belongsToMany('Siakad\Dosen', 'tim_dosen');
		}
	}
