<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Transaksi extends Model
	{
		protected $guarded = [];
		protected $table = 'transaksi';
		
		public function petugas()
		{
			return $this -> belongsTo('\Siakad\User', 'user_id', 'id');
		}
	}
