<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Aktivitas extends Model
	{
   		protected $guarded = [];
		protected $table = 'aktivitas_perkuliahan';
		public $timestamps = false;
		
		public function scopeAkm($query, $tapel_id, $prodi_id)
		{
			$query
			-> join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_id')	
			-> join('tapel', 'tapel.id', '=', 'tapel_id')	
			-> where('tapel_id', $tapel_id)
			-> where('prodi_id', $prodi_id)
			-> orderBy('mahasiswa.NIM')
			-> orderBy('tapel.nama2')
			-> select('NIM', 'mahasiswa.id', 'mahasiswa.nama', 'angkatan', 'status', 'ips', 'ipk', 'jsks AS skss', 'total_sks AS skst');
		}
		
		public function scopeAktivitasMahasiswa($query, $mahasiswa_id=null)
		{
			$query
			-> join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_id')	
			-> join('tapel', 'tapel.id', '=', 'tapel_id')	
			-> where('mahasiswa_id', $mahasiswa_id)
			-> orderBy('tapel.nama2')
			-> select('tapel.nama AS semester', 'status', 'ips', 'ipk', 'jsks AS skss', 'total_sks AS skst');
		}
		
		public function mahasiswa()
		{
			return $this -> belongsTo(\Siakad\Mahasiswa::class);
		}
	}
