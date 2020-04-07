<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Mail extends Model
	{
		protected $guarded = [];
		protected $table = 'mail';
		public $timestamps = false;
		
		public function from()
		{
			return $this -> belongsTo(User::class, 'sender');
		}
		
		public function to()
		{
			return $this -> belongsTo(User::class, 'recipient');
		}
	}
