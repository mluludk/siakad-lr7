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
	}
