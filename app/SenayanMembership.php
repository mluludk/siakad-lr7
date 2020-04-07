<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class SenayanMembership extends Model
	{
		protected $connection = 'senayan';
   		protected $guarded = [];
		protected $table = 'member';
		public $timestamps = false;
	}
