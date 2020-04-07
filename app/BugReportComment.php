<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class BugReportComment extends Model
	{
   		protected $guarded = [];
		protected $table = 'bug_report_comment';
		public $timestamps = false;
		
		public function user()
		{
			return $this -> belongsTo(User::class);	
		}
	}
