<?php
	$tipe = [
	'uts' => 'KARTU UJIAN TENGAH SEMESTER (UTS)',
	'uas' => 'KARTU UJIAN AKHIR SEMESTER (UAS)',
	]
?>
<!DOCTYPE html>
<html>
	<head>
		<title>{{ $tipe[$kartu] }}</title>
		<style>
			body{
			margin: 10px;
			font-family: "Times New Roman";
			}			
			#content{
			background-image: url({{ asset('/images/ku_f.png') }});
			background-repeat:no-repeat;
			background-position:center;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			
			border: 1px solid black;
			width: 9cm;
			height: 6cm;
			position: fixed;
			left: 10px;
			right: 0;
			z-index: 0;
			}
			hr{
			margin: 1px;
			}
			h1, h2, h3, h4, h5{
			margin: 0px;
			}
			.header{
			text-align: center;
			}
			img{
			display: block;
			float: left;
			height: 50px;
			}
			small{
			font-size: 11px;
			}
		</style>
	</head>
	<body>
		<div id="content">
			<?php
				$conf = config('custom');
			?>
			<table style="font-size: 18px;">
				<tr>
					<td><img src="{{ asset('/images/logo64px.png') }}" /></td>
					<td>
						<div class="header">
							<h5>SEKOLAH TINGGI AGAMA ISLAM</h5>
							<h5>MA'HAD ALY ALHIKAM MALANG</h5>
							<small>{{ $conf['profil']['alamat']['jalan'] }} Tlp. {{ $conf['profil']['telepon'] }} {{ $conf['profil']['alamat']['kabupaten'] }} {{ $conf['profil']['alamat']['kodepos'] }}</small>
						</div>
					</td>
				</tr>
			</table>
			<hr/>
			<hr/>
			<br/>
			<table style="font-size: 16px; width: 100%;">
				<tr><th colspan="3" align="center">{{ $tipe[$kartu] }}</th></tr>
				<tr><td width="60px">Nama</td><td>:</td><td>{{ $data -> nama }}</td></tr>
				<tr><td>NIM</td><td>:</td><td>{{ $data -> NIM }}</td></tr>
				<tr><td>Prodi</td><td>:</td><td>{{ $data -> prodi -> nama }}</td></tr>
				<tr><td>Semester</td><td>:</td><td>{{ $data -> semesterMhs }}</td></tr>
			</table>
			<div style="text-align:right; font-size: 12px; margin-right: 10px; text-decoration:underline; font-family: arial;">{{ $conf['profil']['website'] }}</div>
		</div>
		<script>
			window.print();
		</script>
	</body>
</html>																			