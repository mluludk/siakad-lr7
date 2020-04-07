<?php
	$delay = 5;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="REFRESH" content="{{ $delay }};url={{ url('/') }}" />
		<title>Memuat ...</title>
	</head>
	<body>
		<p>
			Pengaturan berhasil diperbarui. Mengalihkan ke halaman utama dalam <span id="timer">{{ $delay }}</span> detik.
		</p>
		<a href="{{ url('/') }}">Klik disini</a> jika anda tidak dialihkan secara otomatis
		<script>
			var delay = {{ $delay }};
			setInterval(function(){
				if(--delay > 0) document.getElementById('timer').innerHTML = delay;
			}, 1000);
		</script>
	</body>
</html>