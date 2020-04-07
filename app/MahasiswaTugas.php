<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class MahasiswaTugas extends Model
	{
   		protected $guarded = [];
		protected $table = 'mahasiswa_tugas';
		public $timestamps = false;
		
		public function scopeHasil($query, $tugas_id)
		{
			$query
			-> join('tugas', 'mahasiswa_tugas.tugas_id', '=', 'tugas.id')
			-> join('mahasiswa', 'mahasiswa_tugas.mahasiswa_id', '=', 'mahasiswa.id')
			-> join('mahasiswa_tugas_detail', 'mahasiswa_tugas_detail.mahasiswa_id', '=', 'mahasiswa.id')
			-> join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
			-> join('kelas', 'mahasiswa.kelasMhs', '=', 'kelas.id')
			-> where('tugas_id', $tugas_id)
			-> groupBy('mahasiswa_id')
			-> select(
			'mahasiswa_tugas.status', 'mahasiswa_tugas.nilai','mahasiswa_tugas.id', 'mahasiswa_tugas_detail.mahasiswa_id',
			'mahasiswa.nama as mahasiswa', 'NIM', 'semesterMhs as semester',
			'kelas.nama AS program',
			'prodi.strata', 'prodi.singkatan'
			);
		}
		
		
		
		
		
		public static function insertIgnore($arrayOfArrays) {
			$static = new static();
			$table = with(new static)->getTable(); //https://github.com/laravel/framework/issues/1436#issuecomment-28985630
			$questionMarks = '';
			$values = [];
			foreach ($arrayOfArrays as $k => $array) {
			if ($static->timestamps) {
				$now = \Carbon\Carbon::now();
				$arrayOfArrays[$k]['created_at'] = $now;
				$arrayOfArrays[$k]['updated_at'] = $now;
			}
			if ($k > 0) {
				$questionMarks .= ',';
			}
			$questionMarks .= '(?' . str_repeat(',?', count($array) - 1) . ')';
			$values = array_merge($values, array_values($array));
		}
		$query = 'INSERT IGNORE INTO ' . $table . ' (' . implode(',', array_keys($array)) . ') VALUES ' . $questionMarks;
		return \DB::insert($query, $values);
		}
	}
	
