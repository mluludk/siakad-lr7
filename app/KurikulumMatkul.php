<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class KurikulumMatkul extends Model
	{
   		protected $guarded = [];
		protected $table = 'kurikulum_matkul';
		public $timestamps = false;
		
		/*   		public function scopeKelasPerkuliahan($query, $kurikulum_id=null, $tapel_id=null, $semester=null)
			{
			$query
			-> join('kurikulum', 'kurikulum.id', '=', 'kurikulum_matkul.kurikulum_id')
			-> leftJoin('matkul', 'matkul.id', '=', 'kurikulum_matkul.matkul_id')
			-> leftJoin('matkul_tapel', 'matkul_tapel.kurikulum_matkul_id', '=', 'kurikulum_matkul.id')
			-> where('kurikulum_id', $kurikulum_id);
			
			if($tapel_id != null) $query -> where('tapel_id', $tapel_id);
			if($semester != null) $query -> where('semester', $semester);
			
			$query
			-> groupBy('matkul.id')
			-> orderBy('semester')
			-> select('matkul.nama', 'matkul.kode', 'matkul.sks_total', 'kurikulum_matkul.id', 'kurikulum_matkul.semester', 'matkul_tapel.id AS kelas_id');
		}  */
		
		
		public function scopeDetailKurikulum($query, $kurikulum_id, $semester=null)
		{			
			$query
			-> leftJoin('matkul', 'matkul.id', '=', 'kurikulum_matkul.matkul_id')
			-> where('kurikulum_id', $kurikulum_id);
			
			if($semester !== null) $query -> where('kurikulum_matkul.semester', $semester);
			
			$query
			-> orderBy('kurikulum_matkul.semester')
			-> select('kurikulum_matkul.*', 'kurikulum_matkul.id AS kmid', 'matkul.*');
		}
		public function kurikulum()
		{
			return $this -> belongsTo('Siakad\Kurikulum');	
		}
		
		public function matkul()
		{
			return $this -> belongsTo('Siakad\Matkul');	
		}
		
		public function kelas()
		{
			return $this -> hasMany('Siakad\MatkulTapel');
		}
		
		public function scopeJumlahSKS($query, $mahasiswa_id)
		{
			$query
			// -> join('kurikulum', 'kurikulum.id', '=', 'kurikulum_matkul.kurikulum_id')
			-> join('matkul', 'matkul.id', '=', 'kurikulum_matkul.matkul_id')
			-> join('matkul_tapel', 'matkul_tapel.kurikulum_matkul_id', '=', 'kurikulum_matkul.id')
			-> join('nilai', 'nilai.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> where('nilai.mahasiswa_id', $mahasiswa_id)
			-> where('nilai.jenis_nilai_id', 0)
			-> where(function($query){
				$query
				-> whereNotIn('nilai.nilai', ['C-', 'D', 'E', '-'])
				-> orWhereNull('nilai.nilai');
			})
			-> select(\DB::raw('sum(matkul.sks_total) AS jumlah'));
		}
		
		public function scopeSKSWajib($query, $prodi_id, $angkatan, $semester)
		{
			$query
			-> join('kurikulum', 'kurikulum.id', '=', 'kurikulum_matkul.kurikulum_id')
			-> join('matkul', 'matkul.id', '=', 'kurikulum_matkul.matkul_id')
			-> where('kurikulum.prodi_id', $prodi_id)
			-> where('kurikulum.angkatan', $angkatan)
			-> where('kurikulum_matkul.semester', '<=', $semester)
			-> select(\DB::raw('sum(matkul.sks_total) AS jumlah'));
		}		
	}
