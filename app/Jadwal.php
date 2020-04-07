<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Jadwal extends Model
	{
		protected $guarded = [];
		protected $table = 'jadwal';
		public $timestamps = false;

		public function scopeExportTugasDosen($query, $ta_id=null)
		{	
			if($ta_id == 0) $ta_id = null;
			
			$query
			-> rightJoin('matkul_tapel', 'matkul_tapel.id', '=', 'jadwal.matkul_tapel_id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('prodi', 'prodi.id', '=', 'matkul_tapel.prodi_id')
			-> join('tim_dosen', 'tim_dosen.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('dosen', 'dosen.id', '=', 'tim_dosen.dosen_id')
			-> join('tapel', 'tapel.id', '=', 'matkul_tapel.tapel_id')
			-> leftJoin(\DB::raw("(select max(STR_TO_DATE(tmt_surat_tugas, '%d/%m/%Y')), prodi_id, homebase, dosen_id from dosen_penugasan group by dosen_id) AS dp "), 'dp.dosen_id', '=', 'dosen.id');
			
			if($ta_id !== null) $query -> where('tapel.id', $ta_id);
			else $query-> where('tapel.aktif', 'y');
			
			$query
			-> whereNotNull('hari')
			-> orderBy('hari') 
			-> orderBy('jam_mulai') 
			-> select(
			'dosen.nama AS dosen', 'dosen.id AS dosen_id', 'dosen.NIP', 'dosen.NIDN', 'dosen.NIK',
			'prodi.nama AS prodi', 'prodi.strata AS jenjang', 'prodi.id AS prodi_id_matkul', 'dp.prodi_id AS prodi_id_tugas', 
			'matkul.nama AS matkul', 'matkul.id AS matkul_id',
			'dp.homebase',
			'sks_total', 
			'hari', 'jam_mulai'
			);
		}
		
		public function scopeJadwalKuliah($query, $for='admin', $prodi_id=null, $ta_id=null, $kelas=null, $semester=null)
		{			
			if($ta_id == 0) $ta_id = null;
			
			$query 
			-> join('ruang', 'ruang.id', '=', 'ruang_id')
			-> rightjoin('matkul_tapel', 'matkul_tapel.id', '=', 'matkul_tapel_id');
			
			if($for == 'mahasiswa') $query -> rightJoin('krs_detail', 'krs_detail.matkul_tapel_id', '=', 'matkul_tapel.id') -> join('krs', 'krs_detail.krs_id', '=', 'krs.id');
			
			$query
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('tim_dosen', 'tim_dosen.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('dosen', 'dosen.id', '=', 'tim_dosen.dosen_id')
			-> join('tapel', 'tapel.id', '=', 'matkul_tapel.tapel_id')
			-> join('prodi', 'prodi.id', '=', 'matkul_tapel.prodi_id')
			-> join('kelas', 'kelas.id', '=', 'matkul_tapel.kelas');
			
			if($kelas != null) $query -> where('matkul_tapel.kelas', $kelas);
			if($semester != null) $query -> where('kurikulum_matkul.semester', $semester);
			if($prodi_id !== null) $query -> where('matkul_tapel.prodi_id', $prodi_id);
			
			if($for == 'mahasiswa') $query -> where('krs.mahasiswa_id', \Auth::user() -> authable -> id);
			if($for == 'dosen') $query -> where('tim_dosen.dosen_id', \Auth::user() -> authable -> id);
			
			// if prodi
			if(\Auth::user() -> role_id >= 64 and \Auth::user() -> role_id < 128 ) $query -> where('prodi.singkatan', \Auth::user() -> role -> sub);
			if($ta_id !== null) $query -> where('tapel.id', $ta_id);
			else $query-> where('tapel.aktif', 'y');
			
			$query 
			-> whereNotNull('hari')
			-> orderBy('hari') 
			-> orderBy('jam_mulai') 
			-> orderBy('kelas.nama') 
			-> orderBy('kurikulum_matkul.semester') 
			-> select(
			'matkul.nama AS matkul', 'matkul.kode AS kd', 'matkul.sks_total AS sks', 
			'kurikulum_matkul.semester', 
			'matkul_tapel.silabus', 'matkul_tapel.id as mtid', 'matkul_tapel.rpp', 'matkul_tapel.kelas2 AS kelas', 'matkul_tapel.id AS mtid', 'matkul_tapel.keterangan',
			'ruang.nama AS ruang', 
			'jadwal.*', 'jadwal.id as jid', 
			'kelas.nama AS program', 
			'prodi.singkatan AS prodi', 'prodi.nama AS nama_prodi', 'prodi.strata'
			);
		}
		
		public function ruang()
		{
			return $this -> belongsTo('Siakad\Ruang');
		}
		public function matkul_tapel()
		{
			return $this -> belongsTo('Siakad\MatkulTapel');
		}
	}
