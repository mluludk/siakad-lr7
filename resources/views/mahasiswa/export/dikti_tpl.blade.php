<!doctype HTML>
<html>
	<head>
		<title>Data Mahasiswa</title>
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
					<td>NIM</td><td>Nama</td><td>Tempat Lahir</td><td>Tanggal Lahir</td><td>Jenis Kelamin</td><td>NIK</td><td>Agama</td>
					<td>NISN</td><td>Id Jalur Masuk</td><td>NPWP</td><td>Kewarganegaraan</td><td>Jenis Pendaftaran</td><td>Tgl Masuk Kuliah</td><td>Mulai semester</td>
					<td>Jalan</td><td>RT</td><td>RW</td><td>Dusun / Lingkungan</td><td>Desa / Kelurahan</td><td>Kecamatan</td><td>Kode Pos</td><td>Jenis Tinggal</td>
					<td>Alat Transportasi</td><td>Telp Rumah</td><td>No HP</td><td>Email</td><td>Terima KPS</td><td>No KPS</td><td>NIK Ayah</td><td>Nama Ayah</td>
					<td>Tgl Lahir Ayah</td><td>Pendidikan Ayah</td><td>Pekerjaan Ayah</td><td>Penghasilan Ayah</td><td>NIK Ibu</td><td>Nama Ibu</td><td>Tanggal Lahir Ibu</td>
					<td>Pendidikan Ibu</td><td>Pekerjaan Ibu</td><td>Penghasilan Ibu</td><td>Nama Wali</td><td>Tanggal Lahir wali</td><td>Pendidikan Wali</td><td>Pekerjaan Wali</td>
					<td>Penghasilan Wali</td>
					<td>Kode Prodi</td>
				</tr>
			</thead>
			<tbody>
				@foreach($rdata as $m)
				@if(isset($m -> NIM))
				<tr>
					
					<td>{{ $m -> NIM }}</td><td>{{ $m -> nama }}</td><td>{{ $m -> tmpLahir }}</td>
					
					<td>{{ toYmd($m -> tglLahir) }}</td>
					
					<td>{{ $m -> jenisKelamin }}</td>
					<td>="{{ $m -> NIK }}"</td><td>{{ $m -> agama }}</td>
					<td>="{{ $m -> NISN }}"</td><td>{{ $m -> jalurMasuk }}</td><td>="{{ $m -> NPWP }}"</td><td>{{ $m -> wargaNegara }}</td><td>{{ $m -> jenisPendaftaran }}</td>
					
					<td>{{ toYmd($m -> tglMasuk) }}</td>
					
					<td>{{ $m -> tapelMasuk }}</td>
					<td>{{ $m -> jalan }}</td><td>{{ $m -> rt }}</td><td>{{ $m -> rw }}</td><td>{{ $m -> dusun }}</td><td>{{ $m -> kelurahan }}</td><td>{{ $m -> id_wil }}</td>
					<td>{{ $m -> kodePos }}</td><td>{{ $m -> mukim }}</td>
					<td>{{ $m -> transportasi }}</td><td>="{{ $m -> telp }}"</td><td>="{{ $m -> hp }}"</td><td>{{ $m -> email }}</td><td>{{ $m -> kps }}</td><td>{{ $m -> noKps }}</td>
					<td>="{{ $m -> NIKAyah }}"</td><td>{{ $m -> namaAyah }}</td>
					<td>{{ $m -> tglLahirAyah }}</td><td>{{ $m -> pendidikanAyah }}</td><td>{{ $m -> pekerjaanAyah }}</td><td>{{ $m -> penghasilanAyah }}</td><td>="{{ $m -> NIKIbu }}"</td>
					<td>{{ $m -> namaIbu }}</td><td>{{ $m -> tglLahirIbu }}</td>
					<td>{{ $m -> pendidikanIbu }}</td><td>{{ $m -> pekerjaanIbu }}</td><td>{{ $m -> penghasilanIbu }}</td><td>{{ $m -> namaWali }}</td><td>{{ $m -> tglLahirwali }}</td>
					<td>{{ $m -> pendidikanWali }}</td><td>{{ $m -> pekerjaanWali }}</td>
					<td>{{ $m -> penghasilanWali }}</td>
					<td>{{ $m -> prodi -> kode_dikti }}</td>
				</tr>
				<?php $c++; ?>
				@endif
				@endforeach
			</tbody>
		</table>
		<script>
			// window.print();
		</script>
		</body>
		</html>											