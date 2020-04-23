<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Kegiatan extends Model
	{
		protected $table = 'kegiatan_pembelajaran';
		protected $guarded = [];
		
		protected $casts = [
		'isi' => 'array'
		];
		
		// public function komentar()
		// {
		// return $this -> hasMany(Komentar::class, 'kegiatan_pembelajaran_id');	
		// }
		
		public function komentar()
		{
			return $this -> morphMany(Komentar::class, 'commentable') -> orderBy('waktu');
		}
	}
