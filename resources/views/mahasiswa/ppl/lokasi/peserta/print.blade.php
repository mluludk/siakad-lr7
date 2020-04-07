<!DOCTYPE html>
<html>
	<head>
		<title>Daftar Peserta PPL {{ $ppl -> strata }} {{ $ppl -> prodi }} {{ $ppl -> tapel }}</title>
		<style>
			body{
			margin: 10px;
			font-family: "Times New Roman";
			}
			table{
			border-collapse: collapse;
			}
			th, td{
			border: 1px solid black;
			}
		</style>
	</head>
	<body>
		<h3 class="box-title">Daftar Peserta PPL {{ $ppl -> strata }} {{ $ppl -> prodi }} {{ $ppl -> tapel }}</h3>
		<?php $c=1; ?>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th width="30px">NO</th>
					<th>NIM</th>
					<th>NAMA</th>
					<th>PRODI</th>
					<th>PROGRAM</th>
					<th>LOKASI</th>
					<th>DOSEN PENDAMPING</th>
					<th>NILAI</th>
				</tr>
			</thead>
			<tbody>
				@if(count($peserta) < 1)
				<tr>
					<td colspan="8" align="center">Belum ada data</td>
				</tr>
				@else
				@foreach($peserta as $lokasi)
				<?php
					$span = '';
					$l = false;
					$rs = count($lokasi);
					if($rs > 1) $span = ' rowspan="' . $rs . '"';
				?>
				@foreach($lokasi as $g)
				<tr>
					<td>{{ $c }}</td>					
					<td>{{ $g -> NIM }}</td>
					<td>{{ $g -> mahasiswa }}</td>
					<td>{{ $g -> strata }} {{ $g -> singkatan }}</td>
					<td>{{ $g -> program }}</td>
					@if(!$l)
					<td {!! $span !!}>{{ $g -> lokasi }}</td>
					<td {!! $span !!}>{!! formatPendamping($g -> ppl_lokasi_id, $pendamping) !!}</td>
					@endif
					<td>
						{{ $g -> nilai }}
					</td>
				</tr>				
				<?php 
					$c++; 
					if(!$l) $l = true;
				?>
				@endforeach
				@endforeach
				@endif
			</tbody>
		</table>
		<script>
		window.print();
		</script>
	</body>
</html>