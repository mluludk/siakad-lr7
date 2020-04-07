<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Skripsi extends Model 
	{
	/* 	use \Sofa\Eloquence\Eloquence;
		protected $searchableColumns = [
		'pengarang.nama' => 8,
		'pengarang.NIM' => 8,
		'judul' => 8,
		'abstrak' => 7
		]; */
		protected $guarded = [];
		protected $table = 'skripsi';
		public $timestamps = false;
		
		public function scopeGetList($query, $prodi_singk=null, $search_query=null)
		{
			$query
			-> join('mahasiswa', 'mahasiswa.skripsi_id', '=', 'skripsi.id')
			-> join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id');
			if($prodi_singk != null) 
			{
				$query -> where('prodi.singkatan', $prodi_singk);
			}
			
			if($search_query != null) 
			{
				$query 
				-> where('mahasiswa.nama', 'like', '%' . $search_query . '%')
				-> orWhere('mahasiswa.NIM', 'like', '%' . $search_query . '%')
				-> orWhere('skripsi.judul', 'like', '%' . $search_query . '%')
				-> orWhere('skripsi.abstrak', 'like', '%' . $search_query . '%');
			}
			
			$query 
			-> orderBy('skripsi.id', 'desc')
			-> select('skripsi.*', 'NIM', 'mahasiswa.nama', 'mahasiswa.id as mahasiswa_id', 'skripsi_id');
		}
		
		public function tapel()
		{
			return $this -> belongsTo('Siakad\Tapel', 'tapel_validasi_kompre');		
		}
		
		public function pengarang()
		{
			return $this -> hasOne('Siakad\Mahasiswa');		
		}
		
		public function pembimbing()
		{
			return $this -> belongsToMany('Siakad\Dosen', 'dosen_skripsi');
		}
		
		public function bimbingan()
		{
			return $this -> hasMany('Siakad\BimbinganSkripsi') -> orderBy('tglBimbingan', 'asc');
		}
	}
