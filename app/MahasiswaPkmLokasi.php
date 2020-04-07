<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class MahasiswaPkmLokasi extends Model {
		protected $guarded = [];
		protected $table = 'mahasiswa_pkm_lokasi';
		public $timestamps = false;

		public function scopeStatus($query, $mahasiswa_id)
		{
			$query
			-> join('pkm_lokasi', 'pkm_lokasi.id', '=', 'pkm_lokasi_id')
			-> join('pkm', 'pkm.id', '=', 'pkm_lokasi.pkm_id')
			-> join('tapel', 'pkm.tapel_id', '=', 'tapel.id')
			-> where('mahasiswa_id', $mahasiswa_id)
			-> select(
			'pkm_lokasi.nama AS lokasi_pkm',
			'tapel.nama AS nama_tapel',
			'pkm.*'
			);
		}
		
		public function scopePeserta($query, $pkm_id)
		{
			$query
			-> join('pkm_lokasi', 'pkm_lokasi.id', '=', 'mahasiswa_pkm_lokasi.pkm_lokasi_id')
			-> join('pkm', 'pkm.id', '=', 'pkm_lokasi.pkm_id')
			-> join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_pkm_lokasi.mahasiswa_id')
			
			-> join('pkm_lokasi_matkul', function($join){
				$join 
				-> on('pkm_lokasi.id', '=', 'pkm_lokasi_matkul.pkm_lokasi_id')
				-> on('mahasiswa.prodi_id', '=', 'pkm_lokasi_matkul.prodi_id');
			})
			
			-> join('matkul', 'matkul.id', '=', 'pkm_lokasi_matkul.matkul_id')
			-> join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
			-> join('kelas', 'kelas.id', '=', 'mahasiswa.kelasMhs')
			-> where('pkm.id', $pkm_id)
			-> orderBy('NIM', 'desc')
			-> groupBy('NIM')
			-> select(
			'mahasiswa_id',
			'mahasiswa.NIM', 'mahasiswa.nama AS mahasiswa', 'mahasiswa.angkatan',
			'prodi.strata', 'prodi.singkatan', 'prodi.nama AS prodi',
			'kelas.nama AS program',
			'pkm_lokasi.nama AS lokasi', 'mahasiswa_pkm_lokasi.pkm_lokasi_id',
			'nilai', 'nilai_angka',
			'matkul_id', 'mahasiswa.prodi_id',
			'matkul.nama as nama_matkul', 'matkul.kode as kode_matkul'
			);
		}
		
		public function lokasi()
		{
			return $this -> belongsTo(PkmLokasi::class);
		}
	}
