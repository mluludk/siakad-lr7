<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class PkmLokasiMatkul extends Model {
		protected $guarded = [];
		protected $table = 'pkm_lokasi_matkul';
		public $timestamps = false;
		
		public function scopePesertaMatkul($query, $pkm_lokasi_id, $matkul_id)
		{
			$query
			-> join('pkm_lokasi', 'pkm_lokasi.id', '=', 'pkm_lokasi_matkul.pkm_lokasi_id')
			-> join('mahasiswa_pkm_lokasi', 'pkm_lokasi.id', '=', 'mahasiswa_pkm_lokasi.pkm_lokasi_id')
			
			-> join('mahasiswa', function($join){
				$join 
				-> on('mahasiswa.id', '=', 'mahasiswa_pkm_lokasi.mahasiswa_id')
				-> on('mahasiswa.prodi_id', '=', 'pkm_lokasi_matkul.prodi_id');
			})
			-> where('pkm_lokasi.id', $pkm_lokasi_id)
			-> where('pkm_lokasi_matkul.matkul_id', $matkul_id)
			-> orderBy('NIM', 'desc')
			-> groupBy('NIM')
			-> select(
			'mahasiswa_id',
			'mahasiswa.NIM', 'mahasiswa.nama AS mahasiswa', 
			'nilai', 'nilai_angka'
			);
		}
		
		public function lokasi()
		{	
			return $this -> belongsTo(PkmLokasi::class, 'pkm_lokasi_id');	
		}
		public function prodi()
		{	
			return $this -> belongsTo(Prodi::class);	
		}
		public function mk()
		{	
			return $this -> belongsTo(Matkul::class, 'matkul_id');	
		}
	}
