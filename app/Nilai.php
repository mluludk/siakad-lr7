<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Nilai extends Model {
		
		protected $guarded = [];
		protected $table = 'nilai';
		
		//Export FEEDER Nilai Kelas
		public function scopeNilaiKelas($query, $id_prodi_dikti, $tapel_nama2)
		{
			$query
			-> join('matkul_tapel', 'nilai.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('tapel', 'matkul_tapel.tapel_id', '=', 'tapel.id')
			-> join('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id')
			-> where('nilai.jenis_nilai_id', 0)
			-> where('tapel.nama2', $tapel_nama2)
			-> where('prodi.kode_dikti', $id_prodi_dikti)
			-> groupBy('matkul_tapel.id')
			-> orderBy('kode')
			-> orderBy('kelas2')
			-> select(
			\DB::raw(
			"`matkul_tapel`.`id` as `id_mt`, 
			`kode`, `matkul`.`nama` as `nama_matkul`, `kurikulum_matkul`.`semester` as `kelas`, 
			`kelas2`, `sks_total` as `sks`, count(`mahasiswa_id`) as peserta, 
			sum(if(nilai = '-' OR nilai = '' OR nilai IS NULL, 0, 1)) as `sudah`"
			)
			);
		}
		
		//Kelas Perkuliahan MHS
		public function scopeKelasKuliahMahasiswa($query, $mahasiswa_id)
		{
			$query
			-> join('matkul_tapel', 'nilai.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('tapel', 'matkul_tapel.tapel_id', '=', 'tapel.id')
			-> where('mahasiswa_id', $mahasiswa_id)
			-> where('nilai.jenis_nilai_id', 0)
			-> where('tapel.aktif', 'y')
			-> select('matkul_tapel.id');
		}
		
		//AktivitasController -> generateData()
		public function scopeAktivitasPerkuliahan($query, $mahasiswa_id, $tapel_id)
		{
			$query
			-> join('matkul_tapel', 'nilai.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> where('jenis_nilai_id', 0)
			-> where('nilai.mahasiswa_id', $mahasiswa_id)
			-> where('matkul_tapel.tapel_id', $tapel_id)
			-> select('matkul.sks_total as sks', 'nilai');
		}
		
		public function scopeRiwayatKRS($query, $mhs_id)
		{
			$query
			-> join('matkul_tapel', 'matkul_tapel_id', '=', 'id')
			-> leftJoin('krs_detail', 'krs_detail.matkul_tapel_id', '=', 'nilai.matkul_tapel_id')
			-> leftJoin('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> leftJoin('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> where('nilai.mahasiswa_id', $mhs_id)
			-> where('jenis_nilai_id', 0)
			-> groupBy('nilai.matkul_tapel_id')
			-> orderBy('semester', 'desc')
			-> select('matkul.nama', 'kode', 'kurikulum_matkul.semester', 'krs_id');
		}
		//transkrip merge
		public function scopeTranskripMerge($query, $prodi, $angkatan = null)
		{
			$query
			-> join('matkul_tapel', 'matkul_tapel_id', '=', 'id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('tapel', 'tapel.id', '=', 'matkul_tapel.tapel_id')
			-> join('mahasiswa', 'mahasiswa.id', '=', 'nilai.mahasiswa_id')
			-> leftJoin('skripsi', 'mahasiswa.skripsi_id', '=', 'skripsi.id')
			-> where('jenis_nilai_id', 0)
			-> where('mahasiswa.prodi_id', $prodi);
			
			if($angkatan != null) $query -> where('mahasiswa.angkatan', $angkatan);
			
			$query
			-> orderBy('mahasiswa.NIM')
			-> select(
			'mahasiswa.id', 'mahasiswa.nama', 'mahasiswa.tmpLahir', 'mahasiswa.tglLahir', 'NIM', 'NIRM', 'NIRL1', 'NIRL2',
			'matkul.nama as matkul', 'matkul.sks_total as sks', 
			'nilai.nilai',
			'skripsi.judul'
			);
		}
		
		//transkrip
		public function scopeTranskrip($query, $id, $include_null=false)
		{
			$query
			-> join('matkul_tapel', 'matkul_tapel_id', '=', 'id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('tapel', 'tapel.id', '=', 'matkul_tapel.tapel_id')
			-> where('jenis_nilai_id', 0)
			-> where('mahasiswa_id', $id);
			
			if(!$include_null) $query -> whereNotNull('nilai.nilai');
			
			$query
			-> groupBy('matkul_tapel.id')
			-> orderBy('semester')
			-> select('matkul.nama as matkul', 'matkul.kode', 'matkul.sks_total as sks', 'kurikulum_matkul.semester', 'tapel.nama as tapel', 'nilai.nilai');
		}
		
		// KUESIONER
		// public function scopeGetCompletedCourse($query, $mahasiswa_id=null, $range=1 ???) //range = how many TA will be checked, ([number] or 'all')
		public function scopeGetCompletedCourse($query, $mahasiswa_id=null)
		{			
			if($mahasiswa_id == null) $mahasiswa_id = \Auth::user() -> authable_id;
			
			$query
			-> join('matkul_tapel', 'matkul_tapel_id', '=', 'id')
			
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')			
			-> join('tim_dosen', 'tim_dosen.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('dosen', 'dosen.id', '=', 'tim_dosen.dosen_id')
			-> join('tapel', 'tapel.id', '=', 'matkul_tapel.tapel_id')
			-> join('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id')
			-> join('kelas', 'matkul_tapel.kelas', '=', 'kelas.id')
			-> where('mahasiswa_id', $mahasiswa_id)
			-> where('jenis_nilai_id', 0)
			
			-> where('dosen.id', '>', 0)
			
			-> whereRaw('
			matkul_tapel.tapel_id = (
			SELECT tapel.id 
			FROM tapel 
			WHERE tapel.nama2 < 
			(
			SELECT tapel.nama2 
			FROM tapel 
			WHERE tapel.aktif = "y"
			) 
			ORDER BY nama2 DESC 
			LIMIT 1
			)
			') 			
			->select('prodi.nama AS prodi', 'matkul.nama AS matkul', 'matkul.kode AS kd', 'dosen.nama AS dosen', 'matkul_tapel.id as mtid', 'matkul_tapel.*');
		}
		
		public function scopeGetJadwalMahasiswa($query, $all = false)
		{
			$query
			-> Join('matkul_tapel', 'matkul_tapel_id', '=', 'id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> Join('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id')
			-> Join('dosen', 'dosen_id', '=', 'dosen.id')
			-> Join('tapel', 'tapel.id', '=', 'matkul_tapel.tapel_id')
			
			-> where('jenis_nilai_id', 0)
			-> where('tapel.aktif', 'y');
			
			if(!$all){ 
				$query 
				-> where('mahasiswa_id', \Auth::user() -> authable_id);
			}
			
			$query -> groupBy('matkul_tapel.id')
			->select('prodi.nama AS prodi', 'matkul.nama AS matkul', 'matkul.kode AS kd', 'dosen.nama AS dosen', 'matkul_tapel.id as mtid', 'matkul_tapel.*');
		}
		
		public function scopeDataKHS($query, $nim, $ta = null)
		{
			$query
			-> Join('mahasiswa', 'mahasiswa_id', '=', 'mahasiswa.id')
			-> Join('matkul_tapel', 'nilai.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> Join('tapel', 'tapel.id', '=', 'matkul_tapel.tapel_id')
			-> Join('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id')
			-> Join('kelas', 'kelas.id', '=', 'mahasiswa.kelasMhs')
			-> where('mahasiswa.NIM', $nim)
			-> where('jenis_nilai_id', 0);
			
			if($ta != null) $query -> where('matkul_tapel.tapel_id', $ta);
			
			$query 
			-> orderBy('matkul.kode')
			-> groupBy('matkul_tapel.id')
			-> select(
			'kelas.nama AS program', 
			'mahasiswa.id AS mhsid',  'mahasiswa.semesterMhs', 'NIM', 'NIRM', 'mahasiswa.nama AS mhs', 
			'prodi.nama as prodi', 'prodi.kaprodi', 'kelas2', 'kode', 
			'matkul.nama AS matkul', 'matkul.sks_total AS sks', 
			'kurikulum_matkul.semester AS smt', 
			'tapel.*', 'nilai', 'nilai.matkul_tapel_id'
			);
		}
		
		public function scopeGetNilaiAkhirMahasiswa($query, $matkul_tapel_id)
		{
			$query
			-> Join('mahasiswa', 'mahasiswa_id', '=', 'mahasiswa.id')
			-> Join('matkul_tapel', 'matkul_tapel_id', '=', 'matkul_tapel.id')
			
			//Cek validasi KRS
			-> join('krs', function($join){
				$join
				-> on('krs.mahasiswa_id', '=', 'mahasiswa.id')
				-> on('krs.tapel_id', '=', 'matkul_tapel.tapel_id');
			})			
			-> where('krs.approved', 'y')
			
			-> where('jenis_nilai_id', 0)
			-> where('matkul_tapel.id', '=', $matkul_tapel_id)
			-> orderBy('NIM')
			-> select('NIM', 'nama', 'nilai', 'mahasiswa.id AS mhs_id');
		}
		
		public function mahasiswa()
		{
			return $this -> belongsTo('Siakad\Mahasiswa');	
		}
		
		public function jenis()
		{
			return $this -> belongsTo('Siakad\JenisNilai');	
		}
	}
