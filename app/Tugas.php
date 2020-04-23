<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Tugas extends Model
	{
   		protected $guarded = [];
		protected $table = 'tugas';
		public $timestamps = false;
		
		public function scopeTugasMahasiswa($query, $mahasiswa_id)
		{
			$query
			-> join('matkul_tapel', 'matkul_tapel.id', '=', 'tugas.matkul_tapel_id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('mahasiswa', function ($join) {
				$join 
				-> on('matkul_tapel.prodi_id', '=', 'mahasiswa.prodi_id')
				-> on('matkul_tapel.kelas', '=', 'mahasiswa.kelasMhs')
				-> on('kurikulum_matkul.semester', '=', 'mahasiswa.semesterMhs');
			})
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id')
			-> join('tapel', 'matkul_tapel.tapel_id', '=', 'tapel.id')
			-> join('kelas', 'matkul_tapel.kelas', '=', 'kelas.id')
			-> leftjoin('mahasiswa_tugas', 'mahasiswa_tugas.tugas_id', '=', 'tugas.id')
			-> where('mahasiswa_tugas.mahasiswa_id', $mahasiswa_id)
			-> where('published', 'y')
			-> groupBy('tugas.id')
			-> select(
			'mahasiswa_tugas.status', 'mahasiswa_tugas.nilai',
			'tugas.id', 'tugas.nama as judul', 'tugas.keterangan', 'tugas.matkul_tapel_id','jenis_tugas',
			'matkul.nama AS matkul', 'matkul.kode',
			'semester',
			'kelas.nama AS program'
			);
		}
		public function scopeDaftarTugas($query, $tugas_id=null, $mahasiswa_id=null)
		{
			$query
			-> leftJoin('mahasiswa_tugas', 'mahasiswa_tugas.tugas_id', '=', 'tugas.id')
			-> join('matkul_tapel', 'matkul_tapel.id', '=', 'tugas.matkul_tapel_id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('jenis_nilai', 'jenis_nilai.id', '=', 'jenis_nilai_id')
			-> join('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id')
			-> join('tapel', 'matkul_tapel.tapel_id', '=', 'tapel.id')
			-> join('kelas', 'matkul_tapel.kelas', '=', 'kelas.id');
			
			if($tugas_id !== null)
			$query -> where('tugas.id', $tugas_id);
		
			if($mahasiswa_id !== null)
			$query -> where('mahasiswa_tugas.mahasiswa_id', $mahasiswa_id);
			
			$query
			-> groupBy('tugas.id')
			-> orderBy('tugas.tanggal', 'desc')
			-> select(\DB::raw('
			tugas.id, tugas.nama AS judul, jenis_tugas, tugas.keterangan, matkul_tapel_id, tanggal, batas, published,
			matkul.nama AS matkul, kode,
			prodi.nama AS prodi, strata, prodi.singkatan,
			kelas.nama AS program, kelas2,
			kurikulum_matkul.semester,
			tapel.nama as tapel,
			jenis_nilai.nama as jnilai, bobot,
			count(mahasiswa_tugas.mahasiswa_id) AS jml_mhs,
			sum(if(mahasiswa_tugas.status>0, 1, 0)) AS jml_kirim,
			sum(if(mahasiswa_tugas.nilai is not null, 1, 0)) AS jml_nilai
			')
			);
		}
		
		public function detail()
		{
			return $this -> hasMany('Siakad\TugasDetail', 'tugas_id');
		}
		
		public function perkuliahan()
		{
			return $this -> belongsTo('Siakad\MatkulTapel', 'matkul_tapel_id');
		}
	}
	
