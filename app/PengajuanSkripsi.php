<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class PengajuanSkripsi extends Model 
	{
		protected $casts = [
		'rumusan_masalah' => 'array',
		'similarity_array' => 'array',
		'similarity2_array' => 'array'
		];
		protected $guarded = [];
		protected $table = 'pengajuan_skripsi';
		
		public function scopeIndex($query, $auth=null)
		{			
			$query
			-> join('mahasiswa', 'mahasiswa.id', '=', 'pengajuan_skripsi.mahasiswa_id')
			-> join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
			-> leftJoin('dosen', 'dosen.id', '=', 'pengajuan_skripsi.dosen_id');
			// -> leftJoin('dosen_skripsi', 'dosen.id', '=', 'pengajuan_skripsi.dosen_id');
			
			if($auth !== null)
			{
				if($auth -> role -> name == 'Mahasiswa') $query -> where('mahasiswa_id', $auth -> authable_id);
				if($auth -> role -> name == 'Prodi') $query -> where('prodi.singkatan', $auth -> role -> sub);
				if($auth -> role -> name == 'Dosen') $query -> where('dosen.id', $auth -> authable_id) -> where('diterima', 'y');
			}
			
			$query 
			-> orderBy('pengajuan_skripsi.diterima_dosen', 'desc')
			-> orderBy('pengajuan_skripsi.diterima', 'desc')
			-> select(
			'mahasiswa.nama', 'mahasiswa.NIM', 'mahasiswa.hp', 'skripsi_id',
			'pengajuan_skripsi.*', 
			'prodi.strata', 'prodi.singkatan',
			'dosen.gelar_depan as p_gd','dosen.nama as p_nm','dosen.gelar_belakang as p_gb'
			);
		}
		
		public function gelombang()
		{
			return $this -> belongsTo(JadwalPengajuanSkripsiGelombang::class, 'jadwal_pengajuan_skripsi_gelombang_id');	
		}
		
		public function pembimbing()
		{
			return $this -> belongsTo(\Siakad\Dosen::class);	
		}
		public function mahasiswa()
		{
			return $this -> belongsTo(\Siakad\Mahasiswa::class);	
		}
		public function similar()
		{
			return $this -> belongsTo(\Siakad\Skripsi::class, 'similar_id');	
		}
	}
