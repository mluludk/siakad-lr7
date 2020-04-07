<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class SettingTapel extends Model
	{
		protected $guarded = [];
		protected $table = 'setting_tapel';
		public $timestamps = false;
		
		public function tapel()
		{
			return $this -> belongsTo('Siakad\Tapel');
		}
		public function prodi()
		{
			return $this -> belongsTo('Siakad\Prodi');
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
