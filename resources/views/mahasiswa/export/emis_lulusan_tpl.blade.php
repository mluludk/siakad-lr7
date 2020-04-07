<html>
	<head>
		<title>Form Dosen PTKI</title>
	</head>
	<body>
		@if(count($rdata) < 1) 
		Data tidak ditemukan
		@else
		<table>
			<thead>
				<tr>
				<tr>
					<td>Nama Lengkap Lulusan</td>
					<td>Nomor Induk Mahasiswa (NIM)</td>
					<td>Tempat Lahir</td>
					<td>Tgl</td>
					<td>Bln</td>
					<td>Thn</td>
					<td>Jenis Kelamin</td>
					<td>Asal Negara</td>
					<td>Asal Pendidikan Sebelumnya</td>
					<td>Jenjang Pendidikan</td>
					<td>Nama Fakultas/Jurusan</td>
					<td>Program Studi</td>
					<td>Jumlah SKS Yang Diselesaikan</td>
					<td>Jumlah Semester Yang Diselesaikan</td>
					<td>Indeks Prestasi Kumulatif (IPK) Akhir</td>
					<td>Nomor Ijazah</td>
					<td>Tgl</td>
					<td>Bln</td>
					<td>Thn</td>
					<td>Rata-Rata Penghasilan Orangtua/Wali per Bulan</td>
					<td>Ayah/Wali</td>
					<td>Ibu/Wali</td>
					<td>Ayah/Wali</td>
					<td>Ibu/Wali</td>
					<td>Status Setelah Lulus</td>
					<td>Jenis Pekerjaan Setelah Lulus (Jika Bekerja)</td>
					<td>Status Domisili Setelah Lulus</td>
					<td>SKPI (Surat Keterangan Pendamping Ijazah)</td>
					<td>Sertifikat Keahlian Bahasa Inggris</td>
					<td>Sertifikat Keahlian Bahasa Arab</td>
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
					<td>{{ $d[13] }}</td>
					<td>{{ $d[14] }}</td>
					<td>{{ $d[15] }}</td>
					<td>{{ $d[16] }}</td>
					<td>{{ $d[17] }}</td>
					<td>{{ $d[18] }}</td>
					<td>{{ $d[19] }}</td>
					<td>{{ $d[20] }}</td>
					<td>{{ $d[21] }}</td>
					<td>{{ $d[22] }}</td>
					<td>{{ $d[23] }}</td>
					<td>{{ $d[24] }}</td>
					<td>{{ $d[25] }}</td>
					<td>{{ $d[26] }}</td>
					<td>{{ $d[27] }}</td>
					<td>{{ $d[28] }}</td>
					<td>{{ $d[29] }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</body>
</html>											