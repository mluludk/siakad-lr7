<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Dosen extends Model
	{
		// use \Sofa\Eloquence\Eloquence;
		// protected $searchableColumns = [
		// 'nama' => 10,
		// 'kode' => 8
		// ];
		protected $guarded = [];
		protected $table = 'dosen';
		
		public function scopeDaftarDosen($query, $search=null, $status_k=null, $status_d=null)
		{
			$query
			-> where('dosen.id', '>', 0);
			
			if($search !== null) $query -> where('dosen.nama', 'like', '%' . $search . '%');
			if($status_k !== null) $query -> where('dosen.statusKepegawaian', $status_k);
			if($status_d !== null) $query -> where('dosen.statusDosen', $status_d);
			
			$query
			-> orderBy('dosen.kode')
			-> select(
			'dosen.id', 'dosen.kode', 'dosen.nama', 'dosen.gelar_depan', 'dosen.gelar_belakang', 'dosen.NIDN', 'dosen.NIY', 'statusDosen', 'statusKepegawaian', 'jenisKelamin', 'homebase'
			);
		}
		
		/* public function bid_matkul()
		{
			return $this-> belongsToMany('Siakad\Matkul');	
		} */
		public function matkul()
		{
			return $this-> belongsToMany('Siakad\Matkul');	
		}
		
		public function matkul_tapel()
		{
			return $this-> belongsToMany('Siakad\MatkulTapel', 'tim_dosen', 'matkul_tapel_id', 'dosen_id');	
		}
		
		
		public function authInfo()
		{
			return $this -> morphOne('\Siakad\User', 'authable');
		}
		
		
		public function scopeGetMahasiswa($query, $dosen_id)
		{
			$query
			-> join('mahasiswa', 'mahasiswa.dosen_wali', '=', 'dosen.id')
			-> join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
			-> join('kelas', 'mahasiswa.kelasMhs', '=', 'kelas.id')
			
			-> leftJoin('nilai', 'nilai.mahasiswa_id', '=', 'mahasiswa.id')
			-> join('matkul_tapel', 'matkul_tapel.id', '=', 'nilai.matkul_tapel_id')
			-> join('kurikulum_matkul', 'matkul_tapel.kurikulum_matkul_id', '=', 'kurikulum_matkul.id')
			-> join('matkul', 'matkul.id', '=', 'kurikulum_matkul.matkul_id')
			
			-> where('dosen_wali', $dosen_id)
			-> where('jenis_nilai_id', 0)
			-> groupBy('mahasiswa.id')
			-> orderBy('NIM', 'desc')
			-> select(\DB::raw(
			'SUM(sks_total) as sks,
			`mahasiswa`.`id`, `mahasiswa`.`NIM`, `mahasiswa`.`nama`, `mahasiswa`.`jenisKelamin`, `mahasiswa`.`semesterMhs`, `mahasiswa`.`statusMhs`, 
			`prodi`.`strata`, `prodi`.`singkatan` as `prodi`, 
			`kelas`.`nama` as `kelas`'
			));
		}
		
		public function skripsibimbingan()
		{
			return $this -> hasMany('Siakad\Skripsi');
		}
		/* 	public function penugasan()
			{
			return $this -> hasMany('Siakad\DosenPenugasan');
		} */
	}
