<!DOCTYPE html>
<html>
	<head>
		<title>Kurikulum Matkul</title>
		<style>
			/* 1cm == 37.8px */
			body{
			padding: 20px;
			width: 21cm;
			height: 35.56cm;
			font-family: tahoma;
			}
			hr{
			margin: 1px;
			}
			h1, h2, h3, h4, h5{
			margin: 0px;
			}
			header{
			text-align: center;
			}
			header div{
			font-size: 12px;
			}
			.sub-header{
			margin: 30px;
			text-align:center;
			}
			img{
			max-width: 100%;
			}
			table{
			width: 100%;
			border-collapse: collapse;
			margin: 10px 0;
			}
			td{
			padding: 3px 5px;
			}
		</style>
	</head>
	<body>
		<img src="{{ asset('/images/header.png') }}" />
		<div class="sub-header">
			<h4><u>Kurikulum Matkul</u></h4>
		</div>
		<table style="margin-bottom: 10px">
			<tr>
				<td width="12%">Nama</td><td width="3%">:</td><td width="30%">{{ $mahasiswa -> nama }}</td>
				<td width="17%">Prodi</td><td width="3%">:</td><td width="35%">{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }}</td>
			</tr>			
			<tr>
				<td>NIM</td><td>:</td><td>{{ $mahasiswa -> NIM }}</td>
				<td>Program</td><td>:</td><td>{{ $mahasiswa -> kelas -> nama }}</td>
			</tr>			
			<tr>
				<td>NIRM</td><td>:</td><td>{{ $mahasiswa -> NIRM }}</td>
				<td>Semester</td><td>:</td><td>{{ $mahasiswa -> semesterMhs }}</td>
			</tr>			
		</table>
		@if(count($matkul) < 1)
		<p class="text-muted">Belum ada data</p>
		@else
		<table border="1">
			<thead>
				<tr>
					<th rowspan="2">No.</th>
					<th rowspan="2">Kode MK</th>
					<th rowspan="2">Nama MK</th>
					<th rowspan="2">SKS</th>
					<th rowspan="2">Semester</th>
					<th colspan="2">Status</th>
				</tr>
				<tr>
					<th>Belum Ditempuh</th>
					<th>Sudah Ditempuh</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$c = $tsks = $tbd = $tsd = 0;
				?>
				@foreach($matkul as $k => $g)
				<?php
					$c++; 
					$sudah = false;
					if(isset($ditempuh[$k])) 
					{
						$sudah = true;
						$tsd += $g['sks'];
					}
					else
					{
						$tbd += $g['sks'];						
					}
					$tsks += $g['sks'];
				?>
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g['kode'] }}</td>
					<td>{{ $g['nama'] }}</td>
					<td>{{ $g['sks'] }}</td>
					<td>{{ $g['semester'] }}</td>
					@if($sudah)
					<td></td><td>&#10004;</td>
					@else
					<td>x</td><td></td>
					@endif
				</tr>
				@endforeach
				<tr>
					<th colspan="6" style="text-align: right !important">Total SKS wajib ditempuh</th>
					<th>{{ $tsks }}</th>
				</tr>
				<tr>
					<th colspan="6" style="text-align: right !important">Total SKS belum ditempuh</th>
					<th>{{ $tbd }}</th>
				</tr>
				<tr>
					<th colspan="6" style="text-align: right !important">Total SKS sudah ditempuh</th>
					<th>{{ $tsd }}</th>
				</tr>
			</tbody>
		</table>
		<script>
			window.print();
		</script>
		@endif
	</body>
</html>																			