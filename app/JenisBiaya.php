<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class JenisBiaya extends Model
	{
		protected $guarded = [];
		protected $table = 'jenis_biaya';
		public $timestamps = false;
		
		/* public function scopeStatusPembayaran($query, $mahasiswa)
		{
			$query 
			-> leftJoin('setup_biaya', 'setup_biaya.jenis_biaya_id', '=', 'jenis_biaya.id')
			-> leftJoin('biaya', function($join) use ($mahasiswa)
			{
				$join -> on('biaya.jenis_biaya_id', '=', 'jenis_biaya.id');
				$join -> on('biaya.mahasiswa_id', '=', \DB::raw($mahasiswa -> id));
			})
			// -> where('jenis_biaya.id', '<>', 1)
			-> groupBy('biaya.jenis_biaya_id')
			-> groupBy('jenis_biaya.id')
			-> orderBy('jenis_biaya.id')
			-> where('setup_biaya.angkatan', substr($mahasiswa -> NIM, 0, 4))
			-> select(\DB::raw('
			`jenis_biaya`.`id`, 
			`nama`,
			`setup_biaya`.`jumlah` as `tanggungan`,
			IFNULL(`biaya`.`jumlah`, 0) as `dibayar`,
			(IFNULL(`setup_biaya`.`jumlah`, 0) - IFNULL(`biaya`.`jumlah`, 0)) AS sisa
			'));
			
		} */
		public function scopeBiayaKuliah($query, $data)
		{
			$query 
			-> leftJoin('setup_biaya', 'setup_biaya.jenis_biaya_id', '=', 'jenis_biaya.id')
			-> where('angkatan', $data['angkatan'])
			-> where('prodi_id', $data['prodi_id'])
			-> where('kelas_id', $data['program_id'])
			-> where('jenisPembayaran', $data['jenisPembayaran'])
			-> orderBy('jenis_biaya.nama')
			-> select('jumlah', 'nama', 'id');
		}
		
		public function biaya_kuliah()
		{
			return $this -> hasMany('Siakad\BiayaKuliah');
		}
		
		public function biaya()
		{
			return $this -> hasMany('Siakad\Biaya');
		}
		}
				