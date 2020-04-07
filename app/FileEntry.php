<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class FileEntry extends Model
	{
		public $guarded = [];
		public $table = 'file';
	}
