<?php
	$conf = config('custom');
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title" id="petunjuk-title"><strong>Tentang {{ $conf['app']['abbr'] }} {{ $conf['app']['version'] }}</strong></h4>
</div>

<div class="modal-body">
	
	<h4>Dibuat oleh:</h4>
	<dl>
		<dt>a.muadib[at]gmail.com</dt>
	</dl>
	<h4>Software dan komponen yang digunakan:</h4>
	<div class="row">
		<div class="col-sm-6">		
			<dl>
				<dt>Apache 2.4.41</dt>
				<dt>PHP 7.3.8</dt>
				<dt>MariaDB 10.3.9</dt>
				<dt>Notepad++ 7.8.4</dt>
				<dt>Adminer 4.7.6</dt>
			</dl>
		</div>
		<div class="col-sm-6">	
			<dl>
				<dt>Laravel Framework 7.3.0</dt>
				<dt>AdminLTE 2.3.5</dt>
				<dt>Bootstrap 3.3.1</dt>
				<dt>jQuery 2.2.3</dt>
				<dt>Font Awesome 4.6.3</dt>
				<dt>ChartJS 2.3.0</dt>
				<dt>Morris.Js 0.5.0</dt>
				<dt>jQuery Form Plugin 3.51.0</dt>
				<dt>...</dt>
			</dl>
		</div>
	</div>
</div>

<div class="modal-footer">
	Untuk {{ $conf['profil']['nama'] }}
</div>