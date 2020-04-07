<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Informasi extends Model
	{
		protected $table = 'informasi';
		protected $guarded = [];
		
		public function poster()
		{
			return $this -> belongsTo('\Siakad\User', 'user_id');	
		}
	}
