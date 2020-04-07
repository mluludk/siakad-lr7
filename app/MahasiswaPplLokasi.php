<?php namespace Siakad;

	use Illuminate\Database\Eloquent\Model;

	class MahasiswaPplLokasi extends Model {
		protected $guarded = [];
		protected $table = 'mahasiswa_ppl_lokasi';
		public $timestamps = false;

		public function scopeStatus($query, $mahasiswa_id)
		{
			$query
			-> join('ppl_lokasi', 'ppl_lokasi.id', '=', 'ppl_lokasi_id')
			-> join('ppl', 'ppl.id', '=', 'ppl_lokasi.ppl_id')
			-> join('tapel', 'ppl.tapel_id', '=', 'tapel.id')
			-> where('mahasiswa_id', $mahasiswa_id)
			-> select(
			'ppl_lokasi.nama AS lokasi_ppl',
			'tapel.nama AS nama_tapel',
			'ppl.*'
			);
		}
		
		public function scopePeserta($query, $ppl_id)
		{
			$query
			-> join('ppl_lokasi', 'ppl_lokasi.id', '=', 'ppl_lokasi_id')
			-> join('ppl', 'ppl.id', '=', 'ppl_lokasi.ppl_id')
			-> join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_ppl_lokasi.mahasiswa_id')
			-> join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
			-> join('kelas', 'kelas.id', '=', 'mahasiswa.kelasMhs')
			-> where('ppl.id', $ppl_id)
			-> orderBy('NIM', 'desc')
			-> select(
			'mahasiswa_id',
			'mahasiswa.NIM', 'mahasiswa.nama AS mahasiswa', 'mahasiswa.angkatan',
			'prodi.strata', 'prodi.singkatan', 'prodi.nama AS prodi',
			'kelas.nama AS program',
			'ppl_lokasi.nama AS lokasi', 'ppl_lokasi_id',
			'nilai', 'nilai_angka'
			);
		}

		public function lokasi()
		{
			return $this -> belongsTo(PplLokasi::class);
		}
	}
