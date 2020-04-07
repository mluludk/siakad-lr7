<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Pegawai extends Model {
		protected $guarded = [];
		protected $table = 'pegawai';
		public $timestamps = false;
	}
