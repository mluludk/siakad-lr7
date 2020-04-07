<!DOCTYPE html>
<html>
	<head>
		<title>Kartu Rencana Studi</title>
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
			header{
			text-align: center;
			}
			header div{
			font-size: 12px;
			}
			.sub-header{
			margin: 30px;
			text-align:center;
			}
			img{
			max-width: 100%;
			}
			.nilai{
			// width: 50%;
			// float: left;
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
		<?php $c=1; $jsks = 0;?>
		<div class="sub-header">
			<h2><u>KARTU RENCANA STUDI (KRS)</u></h2>
		</div>
		<table style="margin-bottom: 10px">
			<tr>
				<td width="17%">Prodi</td><td width="2%">:</td><td width="35%">{{ $mhs -> prodi -> nama }}</td>
				<td width="12%">NIM</td><td width="2%">:</td><td width="30%">{{ $mhs -> NIM }}</td>
			</tr>			
			<tr>
				<td>Tahun Akademik</td><td>:</td><td>{{ $tapel -> nama }}</td>
				<td>Nama</td><td>:</td><td>{{ $mhs -> nama }}</td>
			</tr>			
			<tr>
				<td>Semester</td><td>:</td><td>{{ hitungSemester($mhs -> tapelMasuk, $tapel -> nama2) }}</td>
				<td>Kelas Asal</td><td>:</td><td>{{ $mhs -> kelas -> nama }}</td>
				<td></td><td></td><td></td>
			</tr>				
		</table>
		
		<table border="1">	
			<thead>
				<tr>
					<th width="5%">No</th>
					<th width="15%">Kode</th>
					<th width="30%">Mata Kuliah</th>
					<th>Program</th>
					<th>KELAS</th>
					<th width="5%">SKS</th>
					<th>Dosen</th>
					<th>Ruang</th>
					<th>Jadwal</th>
				</tr>
			</thead>
			<tbody>
				@foreach($krs as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> kode }}</td>
					<td>{{ $g -> nama_matkul }}</td>
					<td>{{ $g -> program }}</td>
					<td>{{ $mhs -> semesterMhs }} {{ $g -> kelas2 }}</td>
					<td>{{ $g -> sks }}</td>
					<td>{{ $g -> gelar_depan }} {{ $g -> dosen }} {{ $g -> gelar_belakang }}</td>
					<td>{{ $g -> ruangan }}</td>
					<td>
						@if(isset(config('custom.hari')[$g -> hari])) {{ config('custom.hari')[$g -> hari] }}
						@else
						-
						@endif
						@if($g -> jam_mulai != '')
						,
						@endif
						{{ $g -> jam_mulai ?? '' }}
						@if($g -> jam_mulai != '')
						- 
						@endif
						{{ $g -> jam_selesai ?? '' }} 
					</td>
				</tr>
				<?php 
					$c++; 
					$jsks += $g -> sks;
				?>
				@endforeach
				<tr>
					<td colspan="5" align="right"><strong>Total</strong></td>
					<td colspan="4"><strong>{{ $jsks }}</strong></td>
				</tr>
			</tbody>
		</table>
		
		<table>
			<tr>
				<td width="30%"></td>
				<td width="40%"></td>
			<td width="30%">Malang, {{ date('d') }} {{ config('custom.bulan')[date('m')] }} {{ date('Y') }}</td></tr>
			<tr>
				<td align="center">
					Pembimbing Akademik
					@if($mhs -> dosenwali -> ttd != '')
					<img src="{{ url('/getimage/' . $mhs -> dosenwali -> ttd) }}" style="display: block;max-width: 200px;"/>
					@else
					<br/><br/><br/><br/><br/>
					@endif
					<strong>{{ $mhs -> dosenwali -> gelar_depan }} {{ $mhs -> dosenwali -> nama }} {{ $mhs -> dosenwali -> gelar_belakang }}</strong>
				</td>
				<td></td>
				<td>
					Mahasiswa
					<br/><br/><br/><br/><br/><br/>
					<strong>{{ $mhs -> nama }}</strong>
				</td>
			</tr>
		</table>
		
		<script>
			window.print();
		</script>
	</body>
</html>														