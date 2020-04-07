<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Ruang extends Model
	{
   		protected $guarded = [];
		protected $table = 'ruang';
		public $timestamps = false;
	}
