<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class JenisGaji extends Model
	{
		protected $guarded = [];
		protected $table = 'jenis_gaji';
		public $timestamps = false;
	}
