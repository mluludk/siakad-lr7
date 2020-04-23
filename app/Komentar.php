<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Komentar extends Model
	{
		protected $table = 'komentar';
		protected $guarded = [];
		public $timestamps = false;
		
		public function commentable()
		{
			return $this -> morphTo();	
		}
		
		public function author()
		{
			return $this -> belongsTo(User::class, 'user_id');	
		}
	}
