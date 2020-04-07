<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Tagihan extends Model
	{
   		protected $guarded = [];
		protected $table = 'tagihan';
		public $timestamps = false;	
		
		public function mahasiswa()
		{
			return $this -> belongsTo('Siakad\Mahasiswa');	
		}
		public function setup()
		{
			return $this -> belongsTo('Siakad\BiayaKuliah', 'setup_biaya_id');	
		}
		public function pembayaran()
		{
			return $this -> hasMany('Siakad\Pembayaran');	
		}
		
		public function scopeDetail($query, $id=null, $token=null)
		{
			$query
			-> join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_id')
			-> join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
			-> join('kelas', 'mahasiswa.kelasMhs', '=', 'kelas.id')
			-> join('setup_biaya', 'setup_biaya.id', '=', 'setup_biaya_id')
			-> join('jenis_biaya', 'setup_biaya.jenis_biaya_id', '=', 'jenis_biaya.id');
			
			if($id != null) $query -> where('tagihan.id', $id);
			else $query -> where('tagihan.token', $token);
			
			$query
			-> select(
			'NIM', 'mahasiswa.nama as nama_mahasiswa', 'mahasiswa.jenisPembayaran', 'semesterMhs',
			'strata', 'prodi.nama as nama_prodi',
			'kelas.nama as nama_kelas',
			'jenis_biaya_id', 'jenis_biaya.nama AS jenis', 'golongan',
			'tagihan.id', 'tagihan.bayar', 'tagihan.jumlah', 'tagihan.tapel', 'mahasiswa_id', 'tagihan.nama as nama_tagihan',
			'privilege_krs', 
			'privilege_uts', 
			'privilege_uas', 
			'privilege'
			);
		}
		
		public function scopeDaftar($query, $q=null)
		{
			$query
			-> leftJoin('tapel', 'tapel.nama2', '=', 'tagihan.tapel')
			-> join('bank', 'tagihan.bank_id', '=', 'bank.id')
			-> join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_id')
			-> join('setup_biaya', 'setup_biaya.id', '=', 'setup_biaya_id')
			-> join('jenis_biaya', 'setup_biaya.jenis_biaya_id', '=', 'jenis_biaya.id');
			
			if($q !== null)
			{
				$query	
				-> where('mahasiswa.nama', 'LIKE', '%' . $q . '%')
				-> orWhere('mahasiswa.NIM', $q)
				-> orWhere('tagihan.nama', 'LIKE', '%' . $q . '%')
				-> orWhere('jenis_biaya.nama', $q);
			}
			
			$query
			-> orderBy('tapel', 'desc')
			-> orderBy('mahasiswa.id', 'desc')
			-> select(
			'mahasiswa_id', 'NIM', 'mahasiswa.nama', 
			/* 'tapel.nama AS tapel', */
			'jenis_biaya_id', 'jenis_biaya.nama AS nama_jenis', 
			'tagihan.id', 'tagihan.bayar', 'tagihan.jumlah', 'tapel', 'tagihan.nama AS nama_tagihan', 'tgl_cicilan_awal', 'tgl_cicilan_akhir', 'tagihan.bank_id', 'override',
			'bank.nama AS metode_pembayaran'
			);
		}
		
		public function scopePrivilege($query, $q=null, $golongan=null)
		{
			$query
			-> leftJoin('tapel', 'tapel.nama2', '=', 'tagihan.tapel')
			-> join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_id')
			-> join('setup_biaya', 'setup_biaya.id', '=', 'setup_biaya_id')
			-> join('jenis_biaya', 'setup_biaya.jenis_biaya_id', '=', 'jenis_biaya.id')
			-> whereRaw('tagihan.jumlah - tagihan.bayar > 0');
			
			
			if($golongan !== null)
			{
				$query -> where('jenis_biaya.golongan', $golongan);
			}
			else
			{
				$query -> where('tapel.aktif', 'y');
			}
			
			if($q !== null)
			{
				$query	
				-> where(function($query) use($q){
					$query -> where('mahasiswa.nama', 'LIKE', '%' . $q . '%')
					-> orWhere('mahasiswa.NIM', $q);
				});				
			}
			
			$query
			-> orderBy('mahasiswa.id', 'desc')
			-> select(
			'mahasiswa_id', 'NIM', 'mahasiswa.nama', 
			'jenis_biaya_id', 'jenis_biaya.nama AS jenis', 
			'tagihan.id', 'tagihan.bayar', 'tagihan.jumlah', 'tagihan.tapel', 
			'jenis_biaya.golongan',
			'privilege_krs', 'privilege_uts', 'privilege_uas', 'privilege_wis', 'privilege'
			);
		}
		
		public function scopeRincian($query, $mahasiswa=null, $jenis_biaya_id=null, $tapel=null)
		{
			$query
			-> join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_id')
			-> join('setup_biaya', 'setup_biaya.id', '=', 'setup_biaya_id')
			-> join('jenis_biaya', 'setup_biaya.jenis_biaya_id', '=', 'jenis_biaya.id');			
			
			if($mahasiswa !== null)
			{
				if(is_array($mahasiswa))
				$query	-> whereIn('mahasiswa_id', $mahasiswa);
				else
				$query	-> where('mahasiswa_id', $mahasiswa);
			}
			
			if($jenis_biaya_id !== null)
			{
				$query -> where('jenis_biaya_id', $jenis_biaya_id);
			}
			
			if($tapel !== null)
			{
				$query -> where('tagihan.tapel', $tapel);
			}
			
			$query
			-> select(
			'setup_biaya_id', 'tapel', 'bulan', 'tahun',
			'mahasiswa_id', 'mahasiswa.nama', 
			'jenis_biaya_id', 'jenis_biaya.nama AS jenis', 'jenis_biaya.periode',
			'tagihan.tapel', 'tagihan.jumlah', 'tagihan.bayar', 
			'privilege', 'privilege_krs', 'privilege_uts', 'privilege_uas'
			);
		}
		
		public function scopeTanggungan($query, $mahasiswa_id, $tapel=null, $bank_id=null)
		{
			$query
			-> leftJoin('setup_biaya', 'setup_biaya.id', '=', 'setup_biaya_id')
			-> join('jenis_biaya', 'setup_biaya.jenis_biaya_id', '=', 'jenis_biaya.id')
			-> where('mahasiswa_id', $mahasiswa_id)
			/* -> where('tagihan.bank_id', 0) */; // only Non-Bank
			
			if($tapel != null) $query -> where('tapel', $tapel);
			if($bank_id != null) $query -> where('tagihan.bank_id', $bank_id);
			
			$query
			-> orderBy('tagihan.tapel')
			-> orderBy('tagihan.nama')
			-> orderBy('jenis_biaya.id')
			-> select(
			'tagihan.id', 'setup_biaya_id', 'tagihan.nama as nama_tagihan', 'jenis_biaya_id',
			'tapel', 'bulan', 'tahun', 'tagihan.jumlah', 'bayar', 
			'tagihan.bank_id', 'tgl_cicilan_awal', 'tgl_cicilan_akhir', 'override',
			'jenis_biaya.nama', 'golongan', 'periode');
		}
		
		public static function insertIgnore($arrayOfArrays) {
			$static = new static();
			$table = with(new static)->getTable(); //https://github.com/laravel/framework/issues/1436#issuecomment-28985630
			$questionMarks = '';
			$values = [];
			foreach ($arrayOfArrays as $k => $array) {
				if ($static->timestamps) {
					$now = \Carbon\Carbon::now();
					$arrayOfArrays[$k]['created_at'] = $now;
					$arrayOfArrays[$k]['updated_at'] = $now;
				}
				if ($k > 0) {
					$questionMarks .= ',';
				}
				$questionMarks .= '(?' . str_repeat(',?', count($array) - 1) . ')';
				$values = array_merge($values, array_values($array));
			}
			$query = 'INSERT IGNORE INTO ' . $table . ' (' . implode(',', array_keys($array)) . ') VALUES ' . $questionMarks;
			return \DB::insert($query, $values);
		}
	}
