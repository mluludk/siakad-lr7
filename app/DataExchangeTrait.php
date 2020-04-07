<?php
/* 	
	namespace Siakad;
	
	trait DataExchangeTrait{
		function toXlsx($filename, $title, $tpl, $rdata)
		{
			\Excel::create($filename, function($excel) use($title, $tpl, $rdata) {
				$excel
				-> setTitle($title)
				-> setCreator('schz')
				-> setLastModifiedBy('Schz')
				-> setManager('Schz')
				-> setCompany('Schz. Co')
				-> setKeywords('Al-Hikam, STAIMA, STAIMA Al-Hikam Malang, '. $title)
				-> setDescription($title);
				$excel -> sheet('title', function($sheet) use($tpl, $rdata, $title){
					$sheet
					-> setOrientation('landscape')
					-> setFontSize(12)
					-> loadView($tpl) -> with(compact('rdata', 'title'));
				});
				
			}) -> download('xlsx');
			return;
		}
	}  */
