<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class DosenSkripsi extends Model {
		
		protected $guarded = [];
		protected $table = 'dosen_skripsi';
		public $timestamps = false;
		
		public function scopePembagianPembimbing($query)
		{
			$query 
			-> join('dosen', 'dosen_skripsi.dosen_id', '=', 'dosen.id')
			-> join('skripsi', 'dosen_skripsi.skripsi_id', '=', 'skripsi.id')
			-> join('tapel', 'dosen_skripsi.tapel_id', '=', 'tapel.id')
			-> join('mahasiswa', 'mahasiswa.skripsi_id', '=', 'skripsi.id')
			-> join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
			-> orderBy('sudah')
			-> orderBy('dosen.id')
			-> orderBy('NIM', 'desc')
			-> select(\DB::raw(
			'`tapel`.`nama2`, `tapel`.`nama` as `semester`, `dosen`.`id` as `id_dosen`, `dosen`.`gelar_depan`, 
			`dosen`.`nama` as `nama_dosen`, `dosen`.`gelar_belakang`, `mahasiswa`.`NIM`, `mahasiswa`.`nama` as `nama_mhs`, 
			`prodi`.`singkatan`, `skripsi`.`judul`, IF(`validasi_proposal` = "y" AND `validasi_kompre` = "y", "y", "n") AS sudah'
			));
		}
		
		public function scopeBimbingan($query, $dosen_id, $status='bimbingan')
		{
			$query
			-> join('skripsi', 'dosen_skripsi.skripsi_id', '=', 'skripsi.id')
			-> join('mahasiswa', 'mahasiswa.skripsi_id', '=', 'skripsi.id')
			
			-> join('pengajuan_skripsi', 'pengajuan_skripsi.mahasiswa_id', '=', 'mahasiswa.id')
			
			-> join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
			-> join('kelas', 'mahasiswa.kelasMhs', '=', 'kelas.id')
			-> where('pengajuan_skripsi.diterima', 'y')
			-> where('dosen_skripsi.dosen_id', $dosen_id);
			
			if($status == 'kandidat')
			{
				$query
				-> where(function($w){
					$w
					-> where('diterima_dosen', 'p')
					-> orWhere('diterima_dosen', 'r');
				});
			}
			elseif($status == 'bimbingan')
			{
				$query 
				-> where('diterima_dosen', 'y')
				-> where(function($w){
					$w
					-> where('validasi_proposal', 'n') 
					-> orWhere('validasi_kompre', 'n');
				});
				
				
			}
			elseif($status == 'selesai')
			{
				$query 
				-> where('validasi_proposal', 'y') 
				-> where('validasi_kompre', 'y');
			}
			
			$query
			-> orderBy('NIM', 'desc')
			-> select(
			'NIM', 'mahasiswa.nama', 'mahasiswa.jenisKelamin', 'hp','telp', 'angkatan',
			'strata', 'singkatan',
			'kelas.nama AS program',
			'skripsi.judul', 'skripsi.id AS skripsi_id', 'validasi_proposal', 'validasi_kompre',
			'pengajuan_skripsi.judul as judul_pengajuan', 'pengajuan_skripsi.judul_revisi', 'diterima_dosen'
			);
		}
		public function skripsi()
		{
			return $this -> belongsTo(Skripsi::class, 'skripsi_id');	
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
				