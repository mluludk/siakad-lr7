<?php
	$pmb = $rdata['pmb'];
	$data = $rdata['data'];
?>
<html>
	<head>
		<title>Peserta PMB {{ str_limit(str_slug($pmb -> nama), 15) }}</title>
	</head>
	<body>
		@if(count($data) < 1) 
		Data tidak ditemukan
		@else
		<?php
			$conf = config('custom');
			$tujuan = explode(',', $pmb -> tujuan) == null ? null : explode(',', $pmb -> tujuan);
			$kelas = explode(',', $pmb -> kelas) == null ? null : explode(',', $pmb -> kelas);
		?>
		<table>
			<thead>
				<tr>
					<td>noPendaftaran</td>
					<td>kode</td>
					<td>nama</td>
					<td>jenisKelamin</td>
					<td>tglLahir</td>
					<td>tmpLahir</td>
					<td>agama</td>
					<td>statusSipil</td>
					<td>alamatMhs</td>
					<td>rtrwMhs</td>
					<td>kodePosMhs</td>
					<td>telpMhs</td>
					<td>noKtp</td>
				<td>jurusan</td>
				<td>program</td>
				<td>kelas</td>
				<td>namaSekolahAsal</td>
				<td>sekolahAsal</td>
				<td>thLulus</td>
				<td>ijazah</td>
				<td>jurusanSekolahAsal</td>
				<td>alamatSekolahAsal</td>
				<td>rtrwSekolahAsal</td>
				<td>kodePosSekolahAsal</td>
				<td>namaAyah</td>
				<td>alamatAyah</td>
				<td>rtrwAyah</td>
				<td>kodePosAyah</td>
				<td>telpAyah</td>
				<td>pekerjaanAyah</td>
				<td>pendidikanAyah</td>
				<td>penghasilanAyah</td>
				<td>namaIbu</td>
				<td>alamatIbu</td>
				<td>rtrwIbu</td>
				<td>kodePosIbu</td>
				<td>telpIbu</td>
				<td>pekerjaanIbu</td>
				<td>pendidikanIbu</td>
				<td>penghasilanIbu</td>
				</tr>
				</thead>
				<tbody>
				@foreach($data as $d)
				<tr>
				<td>{{ $d['noPendaftaran'] }}/{{ $d -> prodi -> singkatan }}/online</td>
				<td>{{ $d['kode'] }}</td>
				<td>{{ $d['nama'] }}</td>
				<td>{{ $d['jenisKelamin'] }}</td>
				<td>{{ $d['tglLahir'] }}</td>
				<td>{{ $d['tmpLahir'] }}</td>
				<td>{{ $conf['pilihan']['agama'][$d['agama']] }}</td>
				<td>{{ $conf['pilihan']['statusSipil'][$d['statusSipil']] }}</td>
				<td>{{ $d['alamatMhs'] }}</td>
				<td>{{ $d['rtrwMhs'] }}</td>
				<td>{{ $d['kodePosMhs'] }}</td>
				<td>{{ $d['telpMhs'] }}</td>
				<td>{{ '`' . $d['noKtp'] }}</td>
				<td>{{ $d -> prodi -> singkatan }}</td>
				<td>{{ $tujuan[$d['tujuan']] }}</td>
				<td>@if(is_array($kelas) and $d['kelas'] != ''){{ $kelas[$d['kelas']] }}@endif</td>
				<td>{{ $d['namaSekolahAsal'] }}</td>
				<td>{{ $conf['pilihan']['sekolahAsal'][$d['sekolahAsal']] }}</td>
				<td>{{ $d['thLulus'] }}</td>
				<td>{{ $d['ijazah'] }}</td>
				<td>{{ $d['jurusanSekolahAsal'] }}</td>
				<td>{{ $d['alamatSekolahAsal'] }}</td>
				<td>{{ $d['rtrwSekolahAsal'] }}</td>
				<td>{{ $d['kodePosSekolahAsal'] }}</td>
				<td>{{ $d['namaAyah'] }}</td>
				<td>{{ $d['alamatAyah'] }}</td>
				<td>{{ $d['rtrwAyah'] }}</td>
				<td>{{ $d['kodePosAyah'] }}</td>
				<td>{{ $d['telpAyah'] }}</td>
				<td>{{ $d['pekerjaanAyah'] }}</td>
				<td>{{ $conf['pilihan']['pendidikanOrtu'][$d['pendidikanAyah']] }}</td>
				<td>{{ $conf['pilihan']['penghasilanOrtu'][$d['penghasilanAyah']] }}</td>
				<td>{{ $d['namaIbu'] }}</td>
				<td>{{ $d['alamatIbu'] }}</td>
				<td>{{ $d['rtrwIbu'] }}</td>
				<td>{{ $d['kodePosIbu'] }}</td>
				<td>{{ $d['telpIbu'] }}</td>
				<td>{{ $d['pekerjaanIbu'] }}</td>
				<td>{{ $conf['pilihan']['pendidikanOrtu'][$d['pendidikanIbu']] }}</td>
				<td>{{ $conf['pilihan']['penghasilanOrtu'][$d['penghasilanIbu']] }}</td>
				</tr>
				@endforeach
				</tbody>
				</table>
				@endif
				</body>
				</html>																			