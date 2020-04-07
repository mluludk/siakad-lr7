<!doctype HTML>
<html>
	<head>
		<title>{{ $title }}</title>
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
					<td>Semester</td><td>NIDN</td><td>Nama Dosen</td><td>Kode Matakuliah</td><td>Nama Kelas</td>
					<td>Rencana Tatap Muka</td><td>Realisasi Tatap Muka</td><td>Kode Prodi</td>
				</tr>
			</thead>
			<tbody>
				@foreach($rdata as $m)
				<tr>
					<td>{{ $m -> tapel }}</td><td>{{ $m -> NIDN }}</td><td>{{ $m -> nama_dosen }}</td><td>{{ $m -> kode }}</td><td>{{ $m -> semester }}{{ $m -> kelas }}</td>
					<td></td><td></td><td>{{ $m -> kode_dikti }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</body>
</html>											