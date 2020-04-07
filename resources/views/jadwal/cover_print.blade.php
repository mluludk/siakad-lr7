<!DOCTYPE html>
<html>
	<head>
		<title>Cetak Sampul Jadwal</title>
		<style>
			body{
			padding: 20px;
			width: 21cm;
			height: 35.56cm;
			font-family: tahoma;
			}
			table.jadwal{
			border-collapse: collapse;
			width: 100%;
			}
			table.jadwal td{
			padding: 3px;
			}
			table.ket th{
			text-align: left !important;
			}
		</style>
	</head>
	<body>
		<img src="{{ url('/images/logo.png#' . config('custom.app.updated')) }}" style="width: 128px; display: block; margin: 20px auto 10px auto;">
		<p style="text-align: center; font-size: 17px; font-weight: bold;">
			PRESENSI DAN JURNAL<br/>
			{{ config('custom.profil.nama') }}<br/>
			SEMESTER {{ strtoupper($tapel[1]) }} {{ $tapel[0] }}<br/>
			{{ $prodi -> nama }} ({{ $prodi -> singkatan }})
		</p>
		@if(count($data) < 1) 
		Data tidak ditemukan
		@else
		<?php
			$s = false;
		?>
		<table class="jadwal" border="1">
			<thead>
				<tr>
					<th>SEMESTER</th>
					<th>PROGRAM</th>
					<th>KELAS</th>
					<th>HARI</th>
					<th width="110px">JAM</th>
					<th>RUANG</th>
					<th>MATA KULIAH</th>
					<th>DOSEN PENGAMPU</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $k => $hari)
				<?php
					$h = false;
				?>
				@foreach($hari as $jam => $m)
				<tr>
					@if(!$s) 
					<th rowspan="{{ $c_data }}" style="font-size: 70px;" valign="middle">{{ arabicToRoman($smt) }}</th>
					<th rowspan="{{ $c_data }}" style="font-size: 40px;" valign="middle">{{ $prg }}</th>
					<th rowspan="{{ $c_data }}" style="font-size: 40px;" valign="middle">{{ $smt }}{{ $kls }}</th>
					@endif
					@if(!$h) 
					<td rowspan="{{ count($hari) }}" align="center" valign="middle">{{ config('custom.hari')[$k] }}</td>
					@endif
					<td align="center">{{ $jam }}</td>
					<td>{{ $m['ruang'] }}</td>
					<td>{{ $m['matkul'] }}</td>
					<td>{{ $m['dosen'] }}</td>
				</tr>
				<?php
					$h = $s = true;
				?>
				@endforeach
				@endforeach
			</tbody>
		</table>
		@endif
		<br/>
		<table class="ket">
			<tr>
				<th>Ketua Prodi</th><td>:</td><td>{{ $prodi -> kepala -> gelar_depan }} {{ $prodi -> kepala -> nama }} {{ $prodi -> kepala -> gelar_belakang }}</td>
			</tr>
			<tr>
				<th>No. HP</th><td>:</td><td>{{ $prodi -> kepala -> telp }}</td>
			</tr>
			<tr>
				<th></th><td></td><td>&nbsp;</td>
			</tr>
			<tr>
				<th>Ketua Kelas</th><td>:</td><td></td>
			</tr>
			<tr>
				<th>No. HP</th><td>:</td><td></td>
			</tr>
		</table>
		<script>
		window.print();
		</script>
	</body>
</html>																						