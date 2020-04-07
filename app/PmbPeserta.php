<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class PmbPeserta extends Model {
		
		protected $guarded = [];
		protected $table = 'pmb_peserta';
		
		public function prodi()
		{
		return $this -> belongsTo('Siakad\Prodi', 'jurusan');	
		}
	}
