<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class MahasiswaPrestasi extends Model {
		protected $guarded = [];
		protected $table = 'mahasiswa_prestasi';
		protected $casts = ['peringkat' => 'integer'];
		
		public function mahasiswa()
		{
			return $this -> belongsTo('Siakad\Mahasiswa');
		}
		
		public function scopeRiwayatPrestasi($query, $mahasiswa_id=null, $prestasi_id=null)
		{
			$query 
			-> leftJoin('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_id')
			-> leftJoin('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
			-> orderBy('mahasiswa_prestasi.tahun', 'desc');
			if($mahasiswa_id !== null) $query -> where('mahasiswa_id', $mahasiswa_id);
			if($prestasi_id !== null) $query -> where('mahasiswa_prestasi.id', $prestasi_id);
			$query
			-> select('mahasiswa_prestasi.*', 'mahasiswa.nama as mahasiswa', 'NIM', 'kode_dikti');
		}
	}
