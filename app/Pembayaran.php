<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Pembayaran extends Model
	{
   		protected $guarded = [];
		protected $table = 'pembayaran';
		
		public function petugas()
		{
			return $this -> belongsTo('\Siakad\User', 'user_id', 'id');
		}
		
		public function scopeRiwayat($query, $mahasiswa_id = null, $str = null, $tapel=null, $tgla=null, $tglb=null, $metode=null)
		{
			$query
			-> join('users', 'users.id', '=', 'user_id')
			-> join('admin', 'admin.id', '=', 'users.authable_id')
			-> join('tagihan', 'tagihan.id', '=', 'pembayaran.tagihan_id')
			-> join('bank', 'bank.id', '=', 'tagihan.bank_id')
			-> leftJoin('tapel', 'tagihan.tapel', '=', 'tapel.nama2')
			-> join('mahasiswa', 'mahasiswa.id', '=', 'tagihan.mahasiswa_id')
			-> join('setup_biaya', 'setup_biaya.id', '=', 'setup_biaya_id')
			-> leftJoin('jenis_biaya', 'setup_biaya.jenis_biaya_id', '=', 'jenis_biaya.id');
			
			if($mahasiswa_id != null) $query -> where('mahasiswa_id', $mahasiswa_id);
			if($tapel != null and $tapel != '-') $query -> where('tagihan.tapel', $tapel);
			if($str != null) $query -> where(function($q) use($str){
				$q 
				-> where('mahasiswa.NIM', 'LIKE' , '%' . $str . '%')
				-> orWhere('mahasiswa.nama', 'LIKE' , '%' . $str . '%')
				-> orWhere('jenis_biaya.nama', 'LIKE' , '%' . $str . '%');
			});
			
			if($tgla != null) $query -> where('pembayaran.created_at', '>=', $tgla .' 00:00:00');
			if($tglb != null) $query -> where('pembayaran.created_at', '<=', $tglb .' 23:59:59');
			if($metode != null) $query -> where('bank.id', $metode);
			
			$query
			-> orderBy('pembayaran.created_at', 'desc')
			-> orderBy('tagihan.nama')
			-> select(
			'pembayaran.id', 'pembayaran.jumlah', 'pembayaran.created_at', 'user_id',
			'tapel.nama AS tapel', 'bulan', 'tahun', 
			'jenis_biaya.nama', 'jenis_biaya_id',
			'admin.nama as admin',
			'NIM', 'mahasiswa.nama AS mahasiswa',
			'tagihan.nama as nama_tagihan', 'tagihan.bank_id',
			'bank.nama as metode'
			);
		}
		
		public function scopeKwitansi($query, $pembayaran_id)
		{
			$query
			-> join('tagihan', 'tagihan.id', '=', 'pembayaran.tagihan_id')
			-> join('mahasiswa', 'mahasiswa.id', '=', 'tagihan.mahasiswa_id')
			-> join('setup_biaya', 'setup_biaya.id', '=', 'setup_biaya_id')
			-> join('jenis_biaya', 'setup_biaya.jenis_biaya_id', '=', 'jenis_biaya.id')
			-> where('pembayaran.id', $pembayaran_id)
			-> select(
			'pembayaran.id as p_id', 'pembayaran.jumlah', 'pembayaran.created_at',
			'mahasiswa.id', 'mahasiswa.NIM', 'mahasiswa.nama', 
			'tagihan.tapel',
			'jenis_biaya.id as j_id', 'jenis_biaya.nama as jenis'
			);
		}
	}
