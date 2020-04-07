<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Kurikulum extends Model
	{
   		protected $guarded = [];
		protected $table = 'kurikulum';
		public $timestamps = false;
		
		public function scopeMatkulKurikulum($query, $prodi_id=0, $tapel_mulai=20161)
		{
			/* 				
				select m.kode, m.nama 
				from kurikulum k
				inner join kurikulum_matkul km on km.kurikulum_id = k.id
				inner join matkul m on km.matkul_id = m.id
				where k.prodi_id = 4
				and k.tapel_mulai in(select id from tapel where nama2 > 20161)
				group by m.kode
				order by m.kode
				
			*/	
			$query
			-> join('kurikulum_matkul', 'kurikulum_matkul.kurikulum_id', '=', 'kurikulum.id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id');
			
			if($prodi_id > 0) $query -> where('kurikulum.prodi_id', $prodi_id);
			
			$query
			-> whereIn('kurikulum.tapel_mulai', function($q) use ($tapel_mulai){
				$q -> from('tapel')
				-> select('id')
				-> where('nama2', '>', $tapel_mulai);
			})
			-> groupBy('matkul.kode')
			-> orderBy('matkul.kode')
			-> select('kurikulum.prodi_id', 'matkul.id',  'matkul.kode', 'matkul.nama');
		}
		public function prodi()
		{
			return $this-> belongsTo('Siakad\Prodi');	
		}
		
		public function tapel()
		{
			return $this-> belongsTo('Siakad\Tapel', 'tapel_mulai');	
		}
		
		public function matkul()
		{
			return $this -> belongsToMany('Siakad\Matkul');	
		}
		
	}
