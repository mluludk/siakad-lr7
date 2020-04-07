<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class BiayaKuliah extends Model
	{
		protected $guarded = [];
		protected $table = 'setup_biaya';
		public $timestamps = false;
		// protected $casts = [
		// 'cicilan' => 'array',
		// ];
		
		public function scopeJenisPembayaran($query, $data)
		{
		 	$query
			-> join('jenis_biaya', 'jenis_biaya.id', '=', 'jenis_biaya_id')
			-> where('angkatan', $data['angkatan'])
			-> where('prodi_id', $data['prodi_id'])
			-> where('kelas_id', $data['program_id'])
			-> where('jenisPembayaran', $data['jenisPembayaran'])
			-> orderBy('jenis_biaya.nama')
			-> select(
			'jenis_biaya.id', 'jenis_biaya.nama', 'jenis_biaya.periode', 'jenis_biaya_id', 'jenis_biaya.golongan',
			'setup_biaya.id as setup_biaya_id', 'jumlah AS tanggungan', 'cicilan', 'bank_id'
			);
		}
		
		public function prodi()
		{
			return $this -> belongsTo('Siakad\Prodi');	
		}
		public function program()
		{
			return $this -> belongsTo('Siakad\Kelas', 'kelas_id');	
		}
		public function jenis()
		{
			return $this -> belongsTo('Siakad\JenisBiaya', 'jenis_biaya_id');	
		}
		public function tagihan()
		{
			return $this -> hasMany('Siakad\Tagihan', 'setup_biaya_id');	
		}
	}
