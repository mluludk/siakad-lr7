<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Krs extends Model
	{
   		protected $guarded = [];
		protected $table = 'krs';
		public $timestamps = false;
		
		//Validasi KRS
		public function scopeValidasiKrs($query, $tapel_id=null, $dosen_wali=null, $q='')
		{
			$query
			-> join('mahasiswa', 'mahasiswa.id', '=', 'krs.mahasiswa_id')
			-> leftJoin('aktivitas_perkuliahan', function($j){
				$j 
				-> on('aktivitas_perkuliahan.mahasiswa_id', '=', 'mahasiswa.id')
				-> on('aktivitas_perkuliahan.tapel_id', '=', 'krs.tapel_id');
			})
			-> join('kelas', 'kelas.id', '=', 'mahasiswa.kelasMhs')
			-> join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
			-> join('krs_detail', 'krs.id', '=', 'krs_detail.krs_id')
			-> join('matkul_tapel', 'matkul_tapel.id', '=', 'krs_detail.matkul_tapel_id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'matkul.id', '=', 'kurikulum_matkul.matkul_id');
			
			if($q != '') $query -> where(function($query) use($q)
			{
				$query -> where('mahasiswa.NIM', 'LIKE', '%' . $q .'%') -> orWhere('mahasiswa.nama', 'LIKE', '%' . $q .'%');
			});
			
			if($tapel_id != null) $query -> where('aktivitas_perkuliahan.tapel_id', $tapel_id);
			if($dosen_wali != null) $query -> where('dosen_wali', $dosen_wali);
			
			$query
			-> groupBy('krs.mahasiswa_id')
			-> orderBy('mahasiswa.NIM', 'desc')
			-> select(\DB::raw('
			mahasiswa.id, mahasiswa.angkatan, NIM, statusMhs, semesterMhs, hp, mahasiswa.nama, aktivitas_perkuliahan.status,
			aktivitas_perkuliahan.semester,
			kelas.nama as program,
			prodi.nama AS jurusan, prodi.strata, prodi.singkatan, 
			sum(sks_total) as jml_sks, approved'));
		}
		
		public function scopeGetKRS($query, $mhs_id = null, $tapel_id = null)
		{
			$query
			-> join('tapel', 'tapel.id', '=', 'krs.tapel_id')
			-> join('krs_detail', 'krs.id', '=', 'krs_detail.krs_id')
			-> join('matkul_tapel', 'matkul_tapel.id', '=', 'krs_detail.matkul_tapel_id')
			-> join('kelas', 'matkul_tapel.kelas', '=', 'kelas.id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'matkul.id', '=', 'kurikulum_matkul.matkul_id')
			
			-> join('tim_dosen', 'tim_dosen.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('dosen', 'dosen.id', '=', 'tim_dosen.dosen_id')
			
			-> leftjoin('jadwal', 'jadwal.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> leftjoin('ruang', 'jadwal.ruang_id', '=', 'ruang.id');
			
			if($mhs_id != null) $query -> where('krs.mahasiswa_id', $mhs_id);
			if($tapel_id != null) $query -> where('tapel.id', $tapel_id); elseif($tapel_id == 'all') $query -> where('tapel.id', '>', 0); else $query -> where('tapel.aktif', 'y');
			
			$query
			-> groupBy('matkul.nama')
			-> select(\DB::raw('
			krs.id AS krs_id,
			matkul.nama AS nama_matkul, matkul.kode, matkul.sks_total AS sks,
			matkul_tapel.id AS mtid, matkul_tapel.*,
			kelas.nama AS program,
			ruang.nama AS ruangan,
			jadwal.*,
			dosen.nama AS dosen, gelar_depan, gelar_belakang,
			(select count(krs_detail.matkul_tapel_id) from krs_detail where matkul_tapel_id = mtid) peserta
			'));
		}
		
		public function detail()
		{
			return $this -> hasMany('Siakad\KrsDetail');
		}
		
		public function mahasiswa()
		{
			return $this -> belongsTo('Siakad\Mahasiswa');
		}
		public function tapel()
		{
			return $this -> belongsTo('Siakad\Tapel');
		}
	}
