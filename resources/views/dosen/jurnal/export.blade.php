<html>
	<head>
		<title>Jurnal Ilmiah Dosen</title>
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
					<td>NIK/No.KTP</td>
					<td>Nama Lengkap Dosen</td>
					<td>Judul Artikel</td>
					<td>Nama Jurnal</td>
					<td>Alamat Website Jurnal</td>
					<td>Level Jurnal</td>
					<td>Penerbit</td>
					<td>No. ISSN</td>
					<td>Akreditasi</td>
					<td>Tahun Terbit</td>
				</tr>
			</thead>
			<tbody>
				@foreach($rdata as $d)
				<tr>
					<td>{{ $d -> NIP }}</td>
					<td>{{ $d -> NIDN }}</td>
					<td>{{ $d -> NIK }}</td>
					<td>{{ str_replace("'", '`', $d -> nama) }}</td>
					<td>{{ str_replace("'", '`', $d -> judul_artikel) }}</td>
					<td>{{ str_replace("'", '`', $d -> nama_jurnal) }}</td>
					<td>{{ $d -> website_jurnal }}</td>
					<td>{{ $d -> level_jurnal }}</td>
					<td>{{ str_replace("'", '`', $d -> penerbit) }}</td>
					<td>{{ $d -> issn }}</td>
					<td>{{ $d -> akreditasi }}</td>
					<td>{{ $d -> tahun_terbit }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</body>
</html>											