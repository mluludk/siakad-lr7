<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class BugReport extends Model
	{
   		protected $guarded = [];
		protected $table = 'bug_report';
		// protected $casts = [
		// 'attachment' => 'array',
		// ];
		
		public function user()
		{
			return $this -> belongsTo(User::class, 'reporter');	
		}
		
		public function comment()
		{
			return $this -> hasMany(BugReportComment::class);	
		}
	}
