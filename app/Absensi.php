<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Absensi extends Model
	{
		protected $table = 'absensi';
		protected $guarded = [];
		public $timestamps = false;
		
		public function scopeDataAbsensi($query, $matkul_taple_id)
		{
			$query 
			-> join('jurnal', 'jurnal.id', '=', 'jurnal_id')
			-> join('matkul_tapel', 'jurnal.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> select('absensi.*');
		}
		
		public function scopeStatus($query, $matkul_tapel_id)
		{
			$query
			-> Join('mahasiswa', 'mahasiswa_id', '=', 'mahasiswa.id')
			-> join('jurnal', 'jurnal.id', '=', 'jurnal_id')
			-> where('matkul_tapel_id', '=', $matkul_tapel_id)
			-> orderBy('NIM')
			-> select('jurnal.id AS jurnal_id', 'NIM', 'mahasiswa.nama', 'mahasiswa.id AS mhs_id', 'absensi.status', 'absensi.new');
		}
	}
