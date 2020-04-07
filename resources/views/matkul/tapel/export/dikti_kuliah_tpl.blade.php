<!doctype HTML>
<html>
	<head>
		<title>Data Kelas</title>
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
		<?php $c = 1; ?>
		<table>
			<thead>
				<tr>
					<td>No</td><td>Semester</td><td>Kode Matakuliah</td><td>Nama Matakuliah</td><td>Kelas</td><td>Kode Prodi</td>
				</tr>
			</thead>
			<tbody>
				@foreach($rdata as $m)
				<tr>
					<td>{{ $c }}</td><td>{{ $m -> tapel }}</td><td>{{ $m -> kode }}</td><td>{{ $m -> nama }}</td><td>{{ $m -> semester }}{{ $m -> kelas }}</td><td>{{ $m -> kode_dikti }}</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
	</body>
</html>											