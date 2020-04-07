<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Pmb extends Model
	{
		protected $table = 'pmb';
		protected $guarded = [];
		
		public function peserta()
		{
			return $this -> hasMany('Siakad\PmbPeserta') -> orderBy('created_at', 'desc');
		}
	}
