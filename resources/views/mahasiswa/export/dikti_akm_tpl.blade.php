<!doctype HTML>
<html>
	<head>
		<title>Data AKM</title>
		<style>
			table{
			border-collapse:collapse;
			}
			td{
			border: 1px solid black;
			}
		</style>
	</head>
	<body>
		<table>
		<?php $c = 1; ?>
			<thead>
				<tr>
					<td>No</td>
					<td>NIM</td>
					<td>Nama</td>
					<td>Semester</td>
					<td>SKS</td>
					<td>IP Semester</td>
					<td>SKS Kumulatif</td>
					<td>IP Kumulatif</td>
					<td>Status</td>
					<td>Kode Prodi</td>
				</tr>
			</thead>
			<tbody>
				@foreach($rdata as $m)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $m['NIM'] }}</td>
					<td>{{ $m['nama'] }}</td>
					<td>{{ $m['semester'] }}</td>
					<td>{{ $m['sks'] }}</td>
					<td>{{ $m['ip'] }}</td>
					<td>{{ $m['sksk'] }}</td>
					<td>{{ $m['ipk'] }}</td>
					<td>{{ $m['status'] }}</td>
					<td>{{ $m['kode_dikti'] }}</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
	</body>
</html>											