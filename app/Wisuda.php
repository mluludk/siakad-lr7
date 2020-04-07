<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Wisuda extends Model {
		protected $guarded = [];
		protected $table = 'wisuda';

		public function scopeDataWisuda($query, $wisuda_id=null)
		{
			$query 
			-> leftJoin('tapel', 'wisuda.smt_yudisium', '=', 'tapel.nama2')
			-> leftJoin('mahasiswa', 'mahasiswa.wisuda_id', '=', 'wisuda.id');
			
			if($wisuda_id != null) $query -> where('wisuda.id', $wisuda_id);
			
			$query
			-> groupBy('wisuda.id')
			-> orderBy('tanggal', 'desc')
			-> select(\DB::raw('wisuda.*, count(NIM) as peserta, tapel.nama as smt_yudisium'));
		}
		public function peserta()
		{
			return $this -> hasMany('Siakad\Mahasiswa');		
		}
	}
