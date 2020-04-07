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
					<td>NIP</td>
					<td>NIDN</td>
					<td>Nama Lengkap Dosen</td>
					<td>Gelar Depan</td>
					<td>Gelar Belakang</td>
					<td>Jenis Kelamin</td>
					<td>Tempat Lahir</td>
					<td>Tgl</td>
					<td>Bln</td>
					<td>Thn</td>
					<td>NIK</td>
					<td>Nama Ibu Kandung</td>
					<td>Status Kepegawaian</td>
					<td>Golongan</td>
					<td>Jabatan Fungsional Dosen</td>
					<td>Nomor SK Awal</td>
					<td>Tgl</td>
					<td>Bln</td>
					<td>Thn</td>
					<td>Nomor SK Terbaru</td>
					<td>Tgl</td>
					<td>Bln</td>
					<td>Thn</td>
					<td>Instansi yang mengangkat</td>
					<td>Status Tugas</td>
					<td>Status Keaktifan Dosen</td>
					<td>Jenjang  Prodi Tempat Tugas</td>
					<td>Nama Program Studi Tempat Tugas</td>
					<td>Mata Kuliah Utama</td>
					<td>Jumlah SKS</td>
					<td>Mata Kuliah Lain</td>
					<td>Jumlah SKS</td>
					<td>Jabatan Tambahan di PTKI</td>
					<td>Jenjang  Pendidikan Terakhir</td>
					<td>Nama Program Studi Pendidikan Terakhir</td>
					<td>Tgl</td>
					<td>Bln</td>
					<td>Thn</td>
					<td>Status Kelulusan Serdos</td>
					<td>Tahun Lulus Serdos</td>
					<td>Status Penerima Tunjangan Profesi Sem. 1 Thn 2016</td>
					<td>Besarnya Tunjangan Profesi per Bulan (Rp)</td>
					<td>Jumlah Judul Buku Yang Pernah Ditulis</td>
					<td>Jumlah Penelitian Ilmiah Yang Pernah Dilakukan</td>
					<td>Jumlah Artikel Ilmiah Yang Pernah Dimuat di Jurnal Internasional</td>
					<td>Alamat</td>
					<td>Kab./Kota</td>
					<td>Kode Provinsi</td>
					<td>Nama Provinsi</td>
				</tr>
			</thead>
			<tbody>
				@foreach($rdata as $d)
				<tr>
					<td>{{ $d -> NIP }}</td>
					<td>{{ $d -> NIDN }}</td>

					<td>{{ str_replace("'", '`', $d -> nama_dosen) }}</td>
					<td>{{ $d -> gelar_depan }}</td>
					<td>{{ $d -> gelar_belakang }}</td>

					<td>@if($d -> jenisKelamin == 'L') 1 @else 0 @endif</td>
					<td>{{ $d -> tmpLahir }}</td>
					<?php $tgl = isset($d -> tglLahir) ? explode('-', $d -> tglLahir) : false; ?>
					<td>@if(isset($tgl[0]))@if(intval($tgl[0]) > 0) {{ intval($tgl[0]) }} @endif @endif</td>
					<td>@if(isset($tgl[1]))@if(intval($tgl[0]) > 1)  {{ intval($tgl[1]) }} @endif @endif</td>
					<td>@if(isset($tgl[2])) {{ $tgl[2] }} @endif</td>
					
					<td>{{ $d -> NIK }}</td>
					<td>{{ $d -> nama_ibu }}</td>

					<td>{{ $d -> pns }}</td>
					<td>@if($d -> pns == 1){{ $d -> pangkat }}@endif</td>
					<td>{{ $d -> jabatan }}</td>

					<td>{{ $d -> no_sk_awal }}</td>
					<?php $tgl = isset($d -> tmt_sk_awal) ? explode('-', $d -> tmt_sk_awal) : false; ?>
					<td>@if(isset($tgl[0]))@if(intval($tgl[0]) > 0) {{ intval($tgl[0]) }} @endif @endif</td>
					<td>@if(isset($tgl[1]))@if(intval($tgl[0]) > 1)  {{ intval($tgl[1]) }} @endif @endif</td>
					<td>@if(isset($tgl[2])) {{ $tgl[2] }} @endif</td>

					<td>{{ $d -> no_sk_terbaru }}</td>
					<?php $tgl = isset($d -> tmt_sk_terbaru) ? explode('-', $d -> tmt_sk_terbaru) : false; ?>
					<td>@if(isset($tgl[0]))@if(intval($tgl[0]) > 0) {{ intval($tgl[0]) }} @endif @endif</td>
					<td>@if(isset($tgl[1]))@if(intval($tgl[0]) > 1)  {{ intval($tgl[1]) }} @endif @endif</td>
					<td>@if(isset($tgl[2])) {{ $tgl[2] }} @endif</td>

					<td>{{ $d -> instansi }}</td>
					<td>{{ $d -> statusDosen }}</td>
					<td>{{ $d -> status_keaktifan }}</td>

					<?php
					$jenjang = array_flip(config('custom.pilihan.emis.jenjang'));
					?>
					<td>@if(isset($jenjang[$d -> strata])){{ $jenjang[$d -> strata] }}@endif</td>
					<td>{{ $d -> nama_prodi }}</td>
					<td>{{ $d -> nama_matkul }}</td>
					<td>{{ $d -> sks_total }}</td>
					<td></td>
					<td></td>

					<td>{{ $d -> jabatan_tambahan }}</td>

					<td>{{ $d -> jenjang }}</td>
					<td>{{ $d -> bidangStudi }}</td>
					<?php $tgl = isset($d -> tgl_ijasah) ? explode('-', $d -> tgl_ijasah) : false; ?>
					<td>@if(isset($tgl[0]))@if(intval($tgl[0]) > 0) {{ intval($tgl[0]) }} @endif @endif</td>
					<td>@if(isset($tgl[1]))@if(intval($tgl[0]) > 1)  {{ intval($tgl[1]) }} @endif @endif</td>
					<td>@if(isset($tgl[2])) {{ $tgl[2] }} @endif</td>

					<td>
					@if(isset($d -> tahun_sertifikasi)) 
					1 
					@else 
					3 
					@endif
					</td>
					<td>{{ $d -> tahun_sertifikasi }}</td>
					
					<td>{{ $d -> tunjangan_profesi }}</td>
					<td>{{ $d -> besar_tunjangan_profesi }}</td>

					<td>{{ $d -> jbuku }}</td>
					<td>{{ $d -> jpenelitian }}</td>
					<td>{{ $d -> jjurnal }}</td>
					
					<td>{{ $d -> alamat }}</td>
					<td>{{ $d -> kab }}</td>
					<td>{{ $d -> provinsi }}</td>
					<td>{{ config('custom.pilihan.emis.provinsi')[$d -> provinsi] }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</body>
</html>											