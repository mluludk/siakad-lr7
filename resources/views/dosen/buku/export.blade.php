<html>
	<head>
		<title>Daftar Buku Dosen</title>
	</head>
	<body>
		@if(count($rdata) < 1) 
		Data tidak ditemukan
		@else
		<table>
			<thead>
				<tr>
					<td>NIP</td>
					<td>NIDN</td>
					<td>NIK</td>
					<td>Nama Lengkap Dosen</td>
					<td>Judul Buku</td>
					<td>Klasifikasi</td>
					<td>Penerbit</td>
					<td>No. ISBN</td>
					<td>Tahun Terbit</td>
				</tr>
			</thead>
			<tbody>
				@foreach($rdata as $d)
				<tr>
					<td>{{ $d[0] }}</td>
					<td>{{ $d[1] }}</td>
					<td>{{ $d[2] }}</td>
					<td>{{ $d[3] }}</td>
					<td>{{ $d[4] }}</td>
					<td>{{ $d[5] }}</td>
					<td>{{ $d[6] }}</td>
					<td>{{ $d[7] }}</td>
					<td>{{ $d[8] }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</body>
</html>																							