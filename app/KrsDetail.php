<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class KrsDetail extends Model
	{
   		protected $guarded = [];
		protected $table = 'krs_detail';
		public $timestamps = false;
		
		public function krs()
		{
			return $this -> belongsTo('Siakad\Krs');
		}
		
		
/* 		public function scopeRiwayatKRS($query, $mhs_id)
		{
			$query
			-> join('krs', 'krs.id', '=', 'krs_detail.krs_id')
			-> join('matkul_tapel', 'matkul_tapel.id', '=', 'krs_detail.matkul_tapel_id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> where('krs.mahasiswa_id', $mhs_id)
			-> orderBy('kurikulum_matkul.semester')
			-> select('matkul.nama', 'kode', 'kurikulum_matkul.semester');
		} */
		
		public function scopeExportData($query, $prodi, $ta=null)
		{
			$query
			-> join('krs', 'krs.id', '=', 'krs_detail.krs_id')
			-> join('matkul_tapel', 'matkul_tapel.id', '=', 'krs_detail.matkul_tapel_id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('tapel', 'tapel.id', '=', 'matkul_tapel.tapel_id')
			-> join('mahasiswa', 'mahasiswa.id', '=', 'krs.mahasiswa_id')
			-> join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id');
			
			if($ta == null) $query -> where('tapel.aktif', 'y');
			else $query -> where('tapel.id', $ta);
			
			$query
			-> where('matkul_tapel.prodi_id', $prodi)
			-> select('NIM', 'mahasiswa.nama AS mahasiswa', 'nama2 AS tapel', 'kode', 'matkul.nama AS matkul', 'kelas2 AS kelas', 'kurikulum_matkul.semester', 'kode_dikti');
		}
	}
