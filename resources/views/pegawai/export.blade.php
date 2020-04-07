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
						<td>Nama Lengkap Tenaga Kependidikan</td><td>Gelar Depan</td><td>Gelar Belakang</td>
						<td>Jenis Kelamin</td>
						<td>Tempat Lahir</td><td>Tgl</td><td>Bln</td><td>Thn</td>
						<td>NIK/No. KTP</td><td>Nama Ibu Kandung</td>
						<td>Status Kepegawaian</td><td>Golongan</td>
						<td>Nomor SK Awal</td><td>Tgl</td><td>Bln</td><td>Thn</td>
						<td>Nomor SK Terbaru</td><td>Tgl</td><td>Bln</td><td>Thn</td>
						<td>Instansi yang mengangkat</td><td>Status Tugas</td><td>Status Keaktifan</td>
						<td>Unit Tempat Tugas</td><td>Nama Unit Tempat Tugas (Nama Bagian/Fakultas/Program Studi/Lembaga/Pusat Strategis)</td>
						<td>Tugas Pokok</td><td>Tugas Tambahan</td>
						<td>Jenjang Pendidikan Terakhir</td><td>Nama Program Studi Pendidikan Terakhir</td>
						<td>Tgl</td><td>Bln</td><td>Thn</td>
						<td>Alamat</td>
						<td>Kab./Kota</td>
						<td>Kode Prov</td>
						<td>Nama Provinsi</td>
					</tr>
				</thead>
				<tbody>
					@foreach($rdata as $d)
					<tr>
						<td>{{ $d -> nip }}</td>
						<td>{{ str_replace("'", '`', $d -> nama) }}</td><td>{{ $d -> gelar_depan }}</td><td>{{ $d -> gelar_belakang }}</td>
						<td>@if($d -> jenis_kelamin == 'L') 1 @else 0 @endif</td>
						<td>{{ $d -> tmp_lahir }}</td>
						<?php $tgl = isset($d -> tgl_lahir) ? explode('-', $d -> tgl_lahir) : false; ?>
						<td>@if(isset($tgl[0]))@if(intval($tgl[0]) > 0) {{ intval($tgl[0]) }} @endif @endif</td>
						<td>@if(isset($tgl[1]))@if(intval($tgl[0]) > 1)  {{ intval($tgl[1]) }} @endif @endif</td>
						<td>@if(isset($tgl[2])) {{ $tgl[2] }} @endif</td>
						<td>{{ $d -> nik }}</td>
						<td>{{ $d -> nama_ibu }}</td>
						
						<td>{{ $d -> pns }}</td>
						<td>@if($d -> pns == 1){{ $d -> golongan }}@endif</td>
						
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
					<td>{{ $d -> status_tugas }}</td>
					<td>{{ $d -> status_keaktifan }}</td>
					
					<td>{{ $d -> unit_tugas }}</td>
					<td>{{ $d -> nama_unit_tugas }}</td>
					
					<td>{{ $d -> tugas_pokok }}</td>
					<td>{{ $d -> tugas_tambahan }}</td>
					
					<td>{{ $d -> pendidikan_terakhir }}</td>
					<td>{{ $d -> program_studi }}</td>
					
					<?php $tgl = isset($d -> tgl_ijasah) ? explode('-', $d -> tgl_ijasah) : false; ?>
					<td>@if(isset($tgl[0]))@if(intval($tgl[0]) > 0) {{ intval($tgl[0]) }} @endif @endif</td>
					<td>@if(isset($tgl[1]))@if(intval($tgl[0]) > 1)  {{ intval($tgl[1]) }} @endif @endif</td>
					<td>@if(isset($tgl[2])) {{ $tgl[2] }} @endif</td>
					
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