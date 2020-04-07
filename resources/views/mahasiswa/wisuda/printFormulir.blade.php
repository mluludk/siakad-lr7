<!DOCTYPE html>
<html>
	<head>
		<title>Cetak Formulir</title>
		<style>
			/* 1cm == 37.8px */
			body{
			padding: 20px;
			width: 21cm;
			height: 35.56cm;
			font-family: tahoma;
			}
			hr{
			margin: 1px;
			}
			h1, h2, h3, h4, h5{
			margin: 0px;
			}
			header{
			text-align: center;
			}
			header div{
			font-size: 12px;
			}
			.sub-header{
			margin: 10px auto 30px auto;
			text-align:center;
			}
			img{
			max-width: 100%;
			}
			table{
			width: 100%;
			border-collapse: collapse;
			margin: 10px 0;
			}
			td{
			padding: 7px 5px;
			}
			#preview{
			display:block;
			width: 150px;
			margin-bottom: 15px;
			border: 1px solid #999;
			}
		</style>
	</head>
	<body>
		<img src="{{ asset('/images/header.png') }}" />
		<div class="sub-header">
			<h4><u>FORMULIR PENDAFTARAN WISUDA</u></h4>
		</div>
		<table style="margin-bottom: 10px">
			<tr>
				<td width="12%">NIM</td><td width="1%">:</td><td width="30%">{{ $mahasiswa -> NIM }}</td>
			</tr>			
			<tr>
				<td>NIRM</td><td>:</td><td>{{ $mahasiswa -> NIRM }}</td>
			</tr>		
			<tr>
				<td>PRODI</td><td>:</td><td>{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }}</td>
			</tr>			
			<tr>
				<td>Nama Mahasiswa</td><td>:</td><td>{{ $mahasiswa -> nama }}</td>
			</tr>							
			<tr>
				<td>Nama Ayah Kandung</td><td>:</td><td>{{ $mahasiswa -> namaAyah }}</td>
			</tr>							
			<tr>
				<td>Tempat / Tanggal Lahir</td><td>:</td><td>{{ $mahasiswa -> tmpLahir }}, {{ $mahasiswa -> tglLahir }}</td>
			</tr>															
			<tr>
				<td>Telepon / Handphone</td><td>:</td><td>{{ $mahasiswa -> hp }}</td>
			</tr>						
			<tr>
				<td>Alamat Lengkap</td><td>:</td><td>{{ $alamat }}</td>
			</tr>											
			<tr>
				<td valign="top">Judul Skripsi</td><td valign="top">:</td><td valign="top">{{ $mahasiswa -> skripsi -> judul }}</td>
			</tr>					
		</table>
		<br/>
		<br/>
		<br/>
		<table style="margin-bottom: 10px">
			<tr>
				<td>
					Malang, {{ formatTanggal(date('Y-m-d')) }}<br/>
					Calon Wisudawan
					<br/><br/><br/><br/><br/>
					({{ $mahasiswa -> nama }})<br/>
				</td>
			</tr>	
		</table>
		<img id="preview" src="{{ url('/getimage/' . $mahasiswa -> foto) }}"></img>
		<script>
			window.print();
		</script>
	</body>
</html>																		