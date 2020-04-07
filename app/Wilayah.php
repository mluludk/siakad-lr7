<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Wilayah extends Model
	{
		protected $table = 'wilayah';
		protected $guarded = [];
		public $timestamps = false;
		
		public function scopeKecamatan($query)
		{
			$query
			-> join('wilayah AS w', 'wilayah.id_wil', '=', 'w.id_induk_wilayah')
			-> join('wilayah AS wc', 'w.id_wil', '=', 'wc.id_induk_wilayah')
			-> where('wilayah.id_level_wil', 1)
			-> select('wc.id_wil', 'wilayah.nm_wil as prov', 'w.nm_wil as kab', 'wc.nm_wil as kec');
		}
		
		public function scopeDataKecamatan($query, $id)
		{
			$query
			-> join('wilayah AS kab', 'kab.id_wil', '=', 'wilayah.id_induk_wilayah')
			-> join('wilayah AS prov', 'prov.id_wil', '=', 'kab.id_induk_wilayah')
			-> where('wilayah.id_wil', $id)
			-> select('wilayah.nm_wil AS kec', 'kab.nm_wil AS kab', 'prov.nm_wil AS prov');
		}
	}
