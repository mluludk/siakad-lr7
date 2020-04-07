<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class TugasDetail extends Model
	{
   		protected $guarded = [];
		protected $table = 'tugas_detail';
		public $timestamps = false;
		
		public function scopeIndex($query, $tugas_id, $mahasiswa_id)
		{
			$query
			-> leftjoin('mahasiswa_tugas_detail', 'mahasiswa_tugas_detail.tugas_detail_id', '=', 'tugas_detail.id')
			-> where('mahasiswa_id', $mahasiswa_id)
			-> where('tugas_id', $tugas_id)
			-> select(
			'tugas_detail.*',
			'mahasiswa_tugas_detail.jawaban'
			);
		} 
		
		public function jawaban()
		{
			return $this -> hasMany('Siakad\MahasiswaTugasDetail');
		}
		
		public function tugas()
		{
			return $this -> belongsTo('Siakad\Tugas');
		}
	}
	
