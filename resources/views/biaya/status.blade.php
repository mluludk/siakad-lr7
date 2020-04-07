<!doctype HTML>
<html>
	<head>
		<title>Status Pembayaran</title>
		<style>
			body{
			padding: 20px;
			width: 21cm;
			height: 35.56cm;
			font-family: Tahoma;
			}
			.table{
			width: 100%;
			border-collapse: collapse;
			}
			.table th{
			padding: 7px;
			}
			.table td{
			padding: 0px 4px;
			}
			.table td, th{
			border: 1px solid black;
			}
			.rp1{
			border-right: none !important;
			}
			.rp2{
			border-left: none !important;
			text-align: right;
			}
		</style>
	</head>
	<body>
		<h2 style="text-align: center;">Status Pembayaran</h2>
		<table>
			<tr>
				<td>Nama</td><td width="20">:</td><td width="65%">{{ $mahasiswa -> nama }}</td>
				<td>NIM</td><td>:</td><td>{{ $mahasiswa -> NIM }}</td>
			</tr>
			<tr>
				<td>PRODI</td><td>:</td><td>{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }}</td>
				<td>Program</td><td>:</td><td>{{ $mahasiswa -> kelas -> nama }}</td>
			</tr>
			<tr>
				<td>Semester</td><td>:</td><td>{{ $mahasiswa -> semesterMhs }}</td>
				<td></td><td></td><td></td>
			</tr>
		</table>
		<hr/>
		<?php $c = 1; $tanggungan = $dibayar = $t_sisa = 0;?>
		<table class="table">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No.</th>
					<th>Jenis Pembayaran</th>
					<th colspan="2">Jumlah Bayar</th>
					<th colspan="2">Sudah Bayar</th>
					<th colspan="2">Sisa</th>
				</tr>
			</thead>
			<tbody>				
				@foreach($status as $s)
		<?php $sisa = $s -> jumlah - $s -> bayar;?>
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $s -> nama }} {{ $s -> tapel }}</td>
					<td class="rp1">Rp </td><td class="rp2">{{ number_format($s -> jumlah, 0, ',', '.') }}</td>
					<td class="rp1">Rp </td><td class="rp2">{{ number_format($s -> bayar, 0, ',', '.') }}</td>
					<td class="rp1">Rp </td><td class="rp2">{{ number_format($sisa, 0, ',', '.') }}</td>
				</tr>
				<?php 
					$c++; 
					$tanggungan += $s -> jumlah;
					$dibayar += $s -> bayar;
					$t_sisa += $sisa;
				?>
				@endforeach
				<tr>
					<th colspan="2" style="text-align:right">Total</th>
					<td class="rp1">Rp </td><td class="rp2">{{ number_format($tanggungan, 0, ',', '.') }}</td>
					<td class="rp1">Rp </td><td class="rp2">{{ number_format($dibayar, 0, ',', '.') }}</td>
					<td class="rp1">Rp </td><td class="rp2">{{ number_format($t_sisa, 0, ',', '.') }}</td>
				</tr>
			</tbody>
		</table>
		<p>
			{{ config('custom.profil.alamat.kabupaten') }}, {{ formatTanggal(date('Y-m-d')) }}<br/>
			{!! config('custom.ttd.biaya.status.kiri') !!}
		</p>
		<script>
			window.print();
		</script>
	</body>
</html>									