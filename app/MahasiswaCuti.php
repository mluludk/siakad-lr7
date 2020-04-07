<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class MahasiswaCuti extends Model {
		protected $guarded = [];
		protected $table = 'cuti_mahasiswa';
		
		public function scopeGetData($query, $prodi_id=null, $mahasiswa_id=null)
		{
			$query
			-> leftJoin('mahasiswa', 'mahasiswa.id', '=', 'cuti_mahasiswa.mahasiswa_id')
			-> leftJoin('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
			-> leftJoin('tapel', 'cuti_mahasiswa.tapel_id', '=', 'tapel.id');
			
			if($prodi_id != null) $query -> where('prodi_id', $prodi_id);
			if($mahasiswa_id != null) $query -> where('mahasiswa_id', $mahasiswa_id);
			
			$query
			-> orderBy('cuti_mahasiswa.created_at', 'DESC')
			-> select(
			'cuti_mahasiswa.id',
			'mahasiswa.NIM', 'mahasiswa.nama',
			'prodi.nama AS prodi',
			'tapel.nama AS ta',
			'status', 'tgl_mulai', 'keterangan'
			);
		}
		public function mahasiswa()
		{
			return $this -> belongsTo('Siakad\Mahasiswa');	
		}
	}
