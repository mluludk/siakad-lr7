<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class JenisNilai extends Model {
		
		protected $guarded = [];
		protected $table = 'jenis_nilai';
		public $timestamps = false;
		
		public function prodi()
		{
			return $this -> belongsTo('Siakad\Prodi');
		}
		
		public function nilai()
		{
			return $this -> hasMany('Siakad\Nilai');
		}
	}
