<html>
	<head>
		<title>Pegawai Non Dosen</title>
	</head>
	<body>
		@if(count($rdata) < 1) 
		Data tidak ditemukan
		@else
		<table>
			<thead>
				<tr>
					<tr>
						<td>NIP</td>
						<td>NIDN</td>
						<td>NIK</td>
						<td>Nama Lengkap Dosen</td>
						<td>Nama Prodi</td>
						<td>Prodi Homebase</td>
						<td>Jenjang Prodi</td>
						<td>Rombel</td>
						<td>Nama Mata Kuliah</td>
						<td>Jumlah SKS</td>
						<td>Durasi Tatap Muka per SKS (menit)</td>
						<td>Hari</td>
						<td>Jam Mulai</td>
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
						<td>{{ $d[9] }}</td>
						<td>{{ $d[10] }}</td>
						<td>{{ $d[11] }}</td>
						<td>{{ $d[12] }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@endif
		</body>
	</html>																						