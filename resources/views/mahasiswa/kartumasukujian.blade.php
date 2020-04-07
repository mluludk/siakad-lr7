<!DOCTYPE html>
<html>
	<head>
		<title>Kartu Masuk Ujian Semester {{ $semester }}</title>
		<style>
			/* 1cm == 37.8px */
			body{
			padding: 20px;
			width: 21cm;
			height: 35.56cm;
			font-family: "Times New Roman";
			position: relative;
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
			margin: 10px 30px;
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
			padding: 3px 5px;
			}
		</style>
	</head>
	<body>
		<img src="{{ asset('/images/header.png') }}" />
		<img style="position: absolute; right: 60px; top: 130px; width: 150px;" src="{{ asset('/images/belum_lunas.png') }}" />
		<?php $c=1; $jsks = 0;?>
		<div class="sub-header">
			<h2><u>KARTU MASUK UJIAN</u></h2>
			<h3>(Semester {{ $semester }})</h3>
		</div>
		<table style="margin-bottom: 10px">
			<tr>
				<td width="12%">NIM</td><td width="2%">:</td><td width="30%">{{ $mhs -> NIM }}</td>
				<td width="17%">Prodi</td><td width="2%">:</td><td width="35%">{{ $mhs -> prodi -> nama }} {{ $mhs -> kelas -> nama }}</td>
			</tr>			
			<tr>
				<td>Nama</td><td>:</td><td>{{ $mhs -> nama }}</td>
				<td>Semester</td><td>:</td><td>{{ $mhs -> semesterMhs }}</td>
			</tr>
		</table>
		
		<table border="1">	
			<thead>
				<tr>
					<th width="5%">No</th>
					<th>Kode</th>
					<th width="30%">Mata Kuliah</th>
					<th>Program</th>
					<th width="5%">SKS</th>
					<th>Dosen Pengajar</th>
					<th width="20%">Paraf {{ strtoupper($jenis) }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach($krs as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> kode }}</td>
					<td>{{ $g -> nama_matkul }}</td>
					<td>{{ $g -> program }}</td>
					<td>{{ $g -> sks }}</td>
					<td>{{ $g -> dosen }}</td>
					<td></td>
				</tr>
				<?php 
					$c++; 
					$jsks += $g -> sks;
				?>
				@endforeach
				<tr>
					<td colspan="4" align="right"><strong>Total</strong></td>
					<td colspan="3"><strong>{{ $jsks }}</strong></td>
				</tr>
			</tbody>
		</table>
		
		<table>
			<tr>
				<td width="25%"></td>
				<td></td>
				<td width="100px"></td>
				<td width="30%">Malang, ___________________</td>
			</tr>
			<tr>
				<td align="center">
					<strong>
						Mengetahui, 
						<br/>
						Dosen Wali
					</strong>
					<br/><br/><br/><br/><br/>
					<u>({{ $mhs -> dosenwali -> gelar_depan }} {{ $mhs -> dosenwali -> nama }} {{ $mhs -> dosenwali -> gelar_belakang }})</u>
					<br/>
					{{ $mhs -> dosenwali -> NIDN ?? '-' }}
				</td>
				<td align="center" >
					<br/>
					<strong>Mahasiswa</strong>
					<br/><br/><br/><br/><br/>
					<u>({{ $mhs -> nama }})</u><br/>{{ $mhs -> NIM }}
				</td>
				<td align="left">
					<img src="@if(isset($mhs -> foto) and $mhs -> foto != '') {{ url('getimage/' . $mhs -> foto) }} @else {{ url('images/untitled.png') }} @endif"></img>
				</td>
				<td align="center" >
					<br/>
					<strong>Staf Admin Akademik</strong>
					<br/><br/><br/><br/><br/>
					(____________________)
					<br/>&nbsp;
				</td>
			</tr>
		</table>
		
		<script>
			window.print();
		</script>
	</body>
</html>														