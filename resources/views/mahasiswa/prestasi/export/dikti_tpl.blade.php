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
			<thead>
				<tr>
					<th>NIM</th>
					<th>Nama</th>
					<th>Bidang Prestasi</th>
					<th>Tingkat Prestasi</th>
					<th>Nama Prestasi</th>
					<th>Tahun</th>
					<th>Penyelenggara</th>
					<th>Peringkat</th>
					<th>Kode Prodi</th>
				</tr>
			</thead>
			<tbody>
				@foreach($rdata as $m)
				<tr>
					<td>{{ $m[0] }}</td>
					<td>{{ $m[1] }}</td>
					<td>{{ $m[2] }}</td>
					<td>{{ $m[3] }}</td>
					<td>{{ $m[4] }}</td>
					<td>{{ $m[5] }}</td>
					<td>{{ $m[6] }}</td>
					<td>{{ $m[7] }}</td>
					<td>{{ $m[8] }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</body>
</html>														