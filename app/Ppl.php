<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Ppl extends Model {
		protected $guarded = [];
		protected $table = 'ppl';
		
 		public function scopeGetNilai($query, $matkul_id, $mahasiswa_id)
		{
			$query
			-> join('matkul', 'matkul.id', '=', 'ppl.matkul_id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('matkul_tapel', 'matkul_tapel.kurikulum_matkul_id', '=', 'kurikulum_matkul.id')
			-> join('krs_detail', 'krs_detail.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('krs', 'krs.id', '=', 'krs_detail.krs_id')
			-> join('nilai', 'nilai.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> where('nilai.jenis_nilai_id', 0)
			-> where('matkul.id', $matkul_id)
			-> where('nilai.mahasiswa_id', $mahasiswa_id)
			-> select('nilai.nilai');
		} 
		
 		public function scopeGetMatkulTapelId($query, $matkul_id, $mahasiswa_id)
		{
			$query
			-> join('matkul', 'matkul.id', '=', 'ppl.matkul_id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('matkul_tapel', 'matkul_tapel.kurikulum_matkul_id', '=', 'kurikulum_matkul.id')
			-> join('krs_detail', 'krs_detail.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('krs', 'krs.id', '=', 'krs_detail.krs_id')
			-> where('matkul.id', $matkul_id)
			-> where('krs.mahasiswa_id', $mahasiswa_id)
			-> select('matkul_tapel.id');
			} 
		
		public function scopeDaftarPpl($query, $prodi_id=null, $ppl_id=null, $dosen_id=null)
		{
			$query
			-> leftJoin('ppl_lokasi', 'ppl.id', '=', 'ppl_lokasi.ppl_id')
			-> leftJoin('ppl_lokasi_dosen', 'ppl_lokasi.id', '=', 'ppl_lokasi_dosen.ppl_lokasi_id')
			-> join('matkul', 'matkul.id', '=', 'ppl.matkul_id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('tapel', 'tapel.id', '=', 'ppl.tapel_id')
			-> join('prodi', 'prodi.id', '=', 'ppl.prodi_id');
			
			if($dosen_id !== null)
			{
				$query 
				-> whereRaw('ppl_lokasi_dosen.dosen_id = '. $dosen_id .' AND
				ppl.tapel_id IN (
				SELECT id from tapel WHERE tapel.nama2 LIKE (SELECT CONCAT(SUBSTR(nama2, 1, 4), "%") FROM tapel WHERE aktif = "y" LIMIT 1)
				)');
			}
			
			if($prodi_id !== null)
			{
				$query -> where('ppl.prodi_id', $prodi_id);
			}
			
			if($ppl_id !== null)
			{
				$query -> where('ppl.id', $ppl_id);
			}
			
			$query
			-> orderBy('tapel.nama2', 'desc')
			-> distinct()
			-> select(			
			'matkul.nama as matkul', 'matkul.kode',
			'tapel.nama as tapel',
			'prodi.id AS prodi_id', 'prodi.strata', 'prodi.singkatan as prodi',
			'ppl.*'
			);
		}
		
		public function lokasi()
		{
			return $this -> hasMany(PplLokasi::class);		
		}
		
		public function tapel()
		{
			return $this -> belongsTo(Tapel::class);		
		}	
		public function matkul()
		{
			return $this -> belongsTo(Matkul::class);		
		}		
	}
