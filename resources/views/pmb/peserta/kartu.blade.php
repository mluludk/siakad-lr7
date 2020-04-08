<!DOCTYPE html>
<html>
	<head>
		<title>KARTU UJIAN MASUK</title>
		<style>
			/* 1cm == 37.8px */
			body{
			padding: 20px;
			width: 21cm;
			height: 35.56cm;
			font-family: "Times New Roman";
			}
			hr{
			margin: 1px;
			}
			h1, h2, h3, h4, h5{
			margin: 0px;
			}
			.header{
			@if($prodi[$data -> jurusan] -> singkatan == 'PAI')
			background-color: red;
			@elseif($prodi[$data -> jurusan] -> singkatan == 'PGMI')
			background-color: yellow;
			@else
			background-color: blue;
			@endif
			color: #fff;
			width: 300px;
			padding: 10px;
			margin: 0 -100px 0 -80px;
			font-family: tahoma; text-align:center; -ms-transform: rotate(-90deg); -webkit-transform: rotate(-90deg); transform: rotate(-90deg);
			}
			.foto{
			float: left;
			align-items: center;
			justify-content: center;
			text-align:center;
			border: 1px solid black;
			width: 3cm;
			height: 4cm;
			}
			img{
			max-width: 100%;
			}
			.num{
			width: 100px;
			height: 100px;
			border: 1px solid brown;
			text-align: center;
			color: #ddd;
			}
		</style>
	</head>
	<body>
		<table width="100%" height="250px" valign="top">
			<tr>
				<td>
					<div class="header">
						<h3>KARTU UJIAN MASUK</h3>
						<h5>SEKOLAH TINGGI AGAMA ISLAM</h5>
						<h5>MA'HAD ALY ALHIKAM</h5>
						<h5>MALANG</h5>
						<h5>{{ date('Y') }}</h5>
					</div>
				</td>
				<td valign="top" style="width: 65%; background: url(data:image/jpg;base64,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) no-repeat 30% 20%; background-size:40%;">
					<table style="font-size: 18px;">
						<tr><td width="100px">No. Daftar</td><td width="10px">:</td><td>{{ $data -> noPendaftaran }}/{{ $data -> prodi -> singkatan }}/online</td></tr>
						<tr><td>Nama</td><td>:</td><td>{{ $data -> nama }}</td></tr>
						<tr><td>TTL</td><td>:</td><td>{{ $data -> tmpLahir }}, {{ $data -> tglLahir }}</td></tr>
						<tr><td valign="top">Alamat</td><td valign="top">:</td><td>{{ $data -> alamatMhs }} @if($data -> rtrwMhs != '')RT/RW: {{ $data -> rtrwMhs }}@endif @if($data -> kodePosMhs != '')Kode Pos: {{ $data -> kodePosMhs }}@endif</td></tr>
						<tr><td>Prodi</td><td>:</td><td>{{ $prodi[$data -> jurusan] -> nama }}</td></tr>
						<tr><td>Program</td><td>:</td><td>{{ explode(',', $pmb -> tujuan)[$data -> tujuan] }}</td></tr>
						<tr><td>Jalur</td><td>:</td><td>{{ explode(',', $pmb -> jalur)[$data -> jalur] ?? 'Tidak terdaftar' }} / {{ explode(',', $pmb -> kelas)[$data -> kelas] ?? 'Tidak terdaftar' }}</td></tr>
						<tr><td></td><td></td><td>
							<div class="foto">
								@if(isset($data -> foto) and $data -> foto != '') <img src="{{ url('/getimage/' . $data -> foto) }}" />
								@else
								<br/>
								Foto<br/>
								3 x 4
								@endif
							</div>
							<div style="float: left; margin-left: 15px;">
								Malang, {{ formatTanggal(date('Y-m-d', strtotime($data->created_at))) }}
								<br/>
								Ketua PMB {{ date('Y') }}
								<br/>
								<br/>
								<br/>
								<br/>
								<br/>
								{{ $pmb -> ketua }}
							</div>
						</td></tr>
					</table>
				</td>
				<td width="20%" valign="top">
					<div class="num">
						<br/>
						<br/>
						Nomor
					</div>
				</td>
			</tr>
		</table>
		<script>
			window.print();
		</script>
	</body>
</html>																			