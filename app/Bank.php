<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Bank extends Model
	{
		public $guarded = [];
		public $table = 'bank';
		public $timestamps = false;
	}
