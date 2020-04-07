<!doctype HTML>
<html>
	<head>
		<title>Data Nilai Perkuliahan</title>
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
					<td>Nama Mahasiswa</td>
					<td>Kode MK</td>
					<td>Mata Kuliah</td>
					<td>Semester</td>
					<td>Kelas</td>
					<td>Nilai Huruf</td>
					<td>Nilai Indeks</td>
					<td>Nilai Angka</td>
					<td>Kode Prodi</td>
				</tr>
			</thead>
			<tbody>
				@foreach($rdata as $m)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $m -> NIM }}</td>
					<td>{{ $m -> nama_mahasiswa }}</td>
					<td>{{ $m -> kode }}</td>
					<td>{{ $m -> nama_matkul }}</td>
					<td>{{ $m -> tapel }}</td>
					<td>{{ $m -> semester }}{{ $m -> kelas }}</td>
					<td>{{ $m -> nilai }}</td>
					<td>@if($m -> nilai != '' and $m -> nilai != '-'){{ config('custom.konversi_nilai.base_4')[$m -> nilai] }}@endif</td>
					<td>@if($m -> nilai != '' and $m -> nilai != '-'){{ config('custom.konversi_nilai.base_100')[$m -> nilai] }}@endif</td>
					<td>{{ $m -> kode_dikti }}</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
	</body>
</html>											