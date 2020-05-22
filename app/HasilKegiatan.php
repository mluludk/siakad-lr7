<?php

	namespace Siakad;

	use Illuminate\Database\Eloquent\Model;

	class HasilKegiatan extends Model
	{
		protected $table = 'hasil_kegiatan_pembelajaran';
		protected $guarded = [];

		protected $casts = [
		'jawaban' => 'array'
		];

        public function mahasiswa(){
            return $this -> belongsTo(Mahasiswa::class, 'mahasiswa_id');
        }
	}
