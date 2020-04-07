<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class KuesionerMahasiswa extends Model
	{
		protected $guarded = [];
		protected $table = 'kuesioner_mahasiswa';
		public $timestamps = false;
		
		public function scopeHasilKuesioner($query, $tapel_id, $prodi_id=null, $dosen_id=null)
		{
			$query
			-> join('matkul_tapel', 'kuesioner_mahasiswa.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('kurikulum_matkul', 'matkul_tapel.kurikulum_matkul_id', '=', 'kurikulum_matkul.id')
			-> join('tapel', 'matkul_tapel.tapel_id', '=', 'tapel.id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			// -> join('dosen', 'matkul_tapel.dosen_id', '=', 'dosen.id')
			
			-> join('tim_dosen', 'tim_dosen.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('dosen', 'dosen.id', '=', 'tim_dosen.dosen_id')
			
			-> join('kelas', 'matkul_tapel.kelas', '=', 'kelas.id')
			-> join('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id')
			-> where('dosen.id', '>', 0)
			-> where('tapel.id', $tapel_id);
			
			if($prodi_id != null) $query -> where('prodi.id', $prodi_id);
			
			if($dosen_id != null) $query -> where('tim_dosen.dosen_id', $dosen_id);
			
			$query
			-> groupBy('matkul_tapel.id')
			-> orderBy('dosen.nama')
			-> select(
			\DB::raw(
			'dosen.nama AS dosen, dosen.kode, dosen.id AS dosen_id, 
			matkul.nama AS matakuliah, matkul_tapel.id AS idmt, 
			prodi.singkatan, prodi.id AS prodi_id,
			kelas.nama AS program, 
			SUM(skor) AS rating'
			)
			);
		}
		public function pertanyaan()
		{
			return $this -> belongsTo('Siakad\Kuesioner');
		}
	}
