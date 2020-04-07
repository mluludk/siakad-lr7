<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class JadwalPengajuanSkripsiGelombang extends Model
	{
   		protected $guarded = [];
		protected $table = 'jadwal_pengajuan_skripsi_gelombang';
		public $timestamps = false;
		
		public function scopeMahasiswa($query, $gelombang_id, $singk=null)
		{
			$query
			-> join('pengajuan_skripsi', 'pengajuan_skripsi.jadwal_pengajuan_skripsi_gelombang_id', '=', 'jadwal_pengajuan_skripsi_gelombang.id')
			-> join('jadwal_pengajuan_skripsi', 'jadwal_pengajuan_skripsi.id', '=', 'jadwal_pengajuan_skripsi_gelombang.jadwal_pengajuan_skripsi_id')
			-> join('mahasiswa', 'mahasiswa.id', '=', 'pengajuan_skripsi.mahasiswa_id')
			-> join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
			-> leftJoin('dosen', 'dosen.id', '=', 'pengajuan_skripsi.dosen_id');
			
			if($gelombang_id != 'semua')
			$query -> where('jadwal_pengajuan_skripsi_gelombang.id', $gelombang_id);
			
			if($singk != null)
			$query -> where('prodi.singkatan', $singk);
			
			$query
			-> orderBy('mahasiswa.id')
			-> orderBy('pengajuan_skripsi.created_at', 'desc')
			-> select(
			'pengajuan_skripsi.id', 'pengajuan_skripsi.created_at', 'pengajuan_skripsi.judul', 'pengajuan_skripsi.judul_revisi', 'pengajuan_skripsi.validator', 'similarity', 'similarity2', 'diterima', 'diterima_dosen', 'pengajuan_skripsi.keterangan',
			'NIM', 'mahasiswa.nama', 'mahasiswa.hp', 
			'strata', 'singkatan',
			'dosen.gelar_depan as p_gd','dosen.nama as p_nm','dosen.gelar_belakang as p_gb',
			'max_similarity'
			);
		}
		public function jadwal()
		{
			return $this -> belongsTo(JadwalPengajuanSkripsi::class, 'jadwal_pengajuan_skripsi_id');
		}
		
		public function peserta()
		{
			return $this -> hasMany(PengajuanSkripsi::class);
		}
		public function diproses()
		{
			return $this -> hasMany(PengajuanSkripsi::class) -> where('diterima', '<>', 'p');
		}
	}
