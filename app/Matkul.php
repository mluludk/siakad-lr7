<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Matkul extends Model
	{
		// use \Sofa\Eloquence\Eloquence;
		// protected $searchableColumns = [
		// 'nama' => 10,
		// 'kode' => 8
		// ];
		protected $guarded = [];
		protected $table = 'matkul';
		public $timestamps = false;
		
		public function scopeGetMatkulKurikulum($query, $prodi_id, $angkatan=null)
		{
			$query 
			-> join('kurikulum_matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('kurikulum', 'kurikulum_matkul.kurikulum_id', '=', 'kurikulum.id')
			-> where('kurikulum.prodi_id', $prodi_id);
			if(intval($angkatan) > 0) $query -> where('kurikulum.angkatan', $angkatan);
			$query
			-> orderBy('matkul.nama')
			-> orderBy('kurikulum.angkatan')
			-> select('kurikulum_matkul.id', 'matkul.nama AS matkul', 'matkul.kode', 'matkul.sks_total', 'kurikulum.nama AS kurikulum', 'kurikulum.angkatan');
		}
		
		public function per_semester()
		{
			return $this -> belongsToMany('Siakad\Semester');
		}
		
		public function prodi()
		{
			return $this-> belongsTo('Siakad\Prodi');	
		}
		
		public function kurikulum()
		{
			return $this-> belongsToMany('Siakad\Kurikulum');	
		}
		
		public function dosen()
		{
			return $this-> belongsToMany('Siakad\Dosen');	
		}
		
		/* public function scopeMatkulPerSemester($query, $semester_id, $sort, $order)
			{			
			$query
			-> join('dosen', 'matkul_semester.dosen_id', '=', 'dosen.id')
			-> join('semester', 'matkul_semester.semester_id', '=', 'semester.id')
			-> join('matkul', 'matkul_semester.matkul_id', '=', 'matkul.id')
			->where(function($query) use($semester_id){
			if($semester_id > 0)
			{
			$query -> where('matkul_semester.semester_id', '=', $semester_id);
			}
			})
			-> orderBy('matkul_semester.' . $sort, $order)
		-> select('matkul_semester.*', 'dosen.nama AS nama_dosen', 'semester.nama AS nama_semester', 'matkul.nama AS nama_matkul');
		} */
		}
				