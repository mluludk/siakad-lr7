<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class JadwalUjianSkripsiGelombang extends Model
	{
   		protected $guarded = [];
		protected $table = 'jadwal_ujian_skripsi_gelombang';
		public $timestamps = false;
		
		
		public function scopeSelectGelombangBuka($query, $jenis, $prodi_id)
		{
			$query
			-> join('jadwal_ujian_skripsi', 'jadwal_ujian_skripsi.id', '=', 'jadwal_ujian_skripsi_gelombang.jadwal_ujian_skripsi_id')
			-> where('jadwal_ujian_skripsi.jenis', $jenis)
			-> whereRaw('
			jadwal_ujian_skripsi.prodi_id = '. $prodi_id .' AND
			"'. date('Y-m-d') .'" BETWEEN str_to_date(tgl_mulai, "%d-%m-%Y") AND str_to_date(tgl_selesai, "%d-%m-%Y") 			
			')
			-> select('jadwal_ujian_skripsi_gelombang.id');
		}
		public function scopeCekGelombang($query, $jenis, $jadwal_id, $tgl_mulai, $tgl_selesai, $gelombang_id = null)
		{
			$query
			-> join('jadwal_ujian_skripsi', 'jadwal_ujian_skripsi.id', '=', 'jadwal_ujian_skripsi_gelombang.jadwal_ujian_skripsi_id')
			-> where('jadwal_ujian_skripsi.jenis', $jenis);
			
			if($gelombang_id != null) $query -> where('jadwal_ujian_skripsi_gelombang.id', '<>', $gelombang_id);
			
			$query
			-> whereRaw('
			jadwal_ujian_skripsi.prodi_id = (SELECT prodi_id FROM jadwal_ujian_skripsi WHERE jadwal_ujian_skripsi.id = '. $jadwal_id .') AND
			(str_to_date(tgl_mulai, "%d-%m-%Y") BETWEEN str_to_date("' . $tgl_mulai . '", "%d-%m-%Y") AND str_to_date("' . $tgl_selesai . '", "%d-%m-%Y") 
			OR str_to_date(tgl_selesai, "%d-%m-%Y") BETWEEN str_to_date("' . $tgl_mulai . '", "%d-%m-%Y") AND str_to_date("' . $tgl_selesai .'", "%d-%m-%Y"))
			')
			-> select('jadwal_ujian_skripsi.nama');
		}
		
		public function ujian(){
			return $this -> belongsTo(JadwalUjianSkripsi::class, 'jadwal_ujian_skripsi_id');
		}
		
		public function peserta()
		{
			return $this -> belongsToMany(Mahasiswa::class, 'mahasiswa_jusg', 'jusg_id', 'mahasiswa_id');
		}
	}
	
