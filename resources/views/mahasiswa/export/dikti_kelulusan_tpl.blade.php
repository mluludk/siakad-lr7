<!doctype HTML>
<html>
	<head>
		<title>Data Kelulusan</title>
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
					<td>No</td><td>NIM</td><td>Nama</td>
					<td>Jenis Keluar</td><td>Tanggal Keluar</td><td>SK Yudisium</td>
					<td>Tanggal SK Yudisium</td><td>IPK</td><td>No Seri Ijazah</td>
					<td>Judul Skripsi</td><td>Bulan Awal Bimbingan</td><td>Bulan Akhir Bimbingan</td>
					<td>Jalur Skripsi</td><td>Pembimbing I</td><td>Pembimbing II</td>
					<td>Kode Prodi</td>
				</tr>
			</thead>
			<tbody>
				@foreach($rdata as $m)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $m['NIM'] }}</td>
					<td>{{ $m['nama'] }}</td>
					<td>{{ $m['jenisKeluar'] }}</td><td>{{ $m['tglKeluar'] }}</td><td>{{ $m['SKYudisium'] }}</td>
					<td>{{ $m['tglSKYudisium'] }}</td><td>{{ $m['ipk'] }}</td><td>{{ $m['noIjazah'] }}</td>
					<td>{{ $m['judulSkripsi'] }}</td><td>{{ $m['awalBimbingan'] }}</td><td>{{ $m['akhirBimbingan'] }}</td>
					<td>{{ $m['jalurSkripsi'] }}</td><td>{{ $m['pembimbing1'] }}</td><td>{{ $m['pembimbing2'] }}</td>
					<td>{{ $m['kode_dikti'] }}</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
	</body>
</html>											