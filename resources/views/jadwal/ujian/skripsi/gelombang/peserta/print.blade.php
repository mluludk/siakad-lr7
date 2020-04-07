<!DOCTYPE html>
<html>
	<head>
		<title>Peserta Ujian {{ ucfirst($j) }} Skripsi {{ ucfirst($gelombang -> nama) }}</title>
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
			margin: 30px;
			text-align:center;
			}
			img{
			max-width: 100%;
			}
			.table{
			font-family: "Times New Roman";
			}
			table{
			width: 100%;
			border-collapse: collapse;
			margin: 10px 0;
			font-size: 12px;
			}
			.table td, th{
			padding: 1px 3px;
			border: 1px solid black;
			}
		</style>
	</head>
	<body>
		<img src="{{ asset('/images/header.png') }}" />
		<div class="sub-header">
			<h4><u>Peserta Ujian {{ ucfirst($j) }} Skripsi {{ ucfirst($gelombang -> nama) }}</u></h4>
			<h4>Program Studi {{ $gelombang -> ujian -> prodi -> nama }} ({{ $gelombang -> ujian -> prodi -> singkatan }})</h4>
		</div>
		@if(!$peserta -> count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c = 1; ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>NO</th>
					<th>RUANG</th>
					<th width="85px">TANGGAL</th>
					<th width="100px">WAKTU</th>
					<th>NAMA MAHASISWA</th>
					<th>NIM/NIRM</th>
					<th>JUDUL @if($j == 'proposal') PROPOSAL @endif SKRIPSI</th>
					<th>PENGUJI UTAMA</th>
					<th>KETUA PENGUJI</th>
					<th>SEKRETARIS</th>
				</tr>
			</thead>
			<tbody>
				@foreach($peserta as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> ruang ?? '-' }}</td>
					<td>{{ $g -> tanggal ?? '-' }}</td>
					<td>{{ $g -> jam_mulai ?? '' }} - {{ $g -> jam_selesai ?? '' }}</td>
					<td>{{ $g -> nama }}</td>
					<td>{{ $g -> NIM }}</td>
					<td>{{ strtoupper($g -> judul) }}</td>
					<td>{{ $g -> p_gd ?? '' }} {{ $g -> p_nama ?? '' }} {{ $g -> p_gb ?? '' }}</td>
					<td>{{ $g -> k_gd }} {{ $g -> k_nama }} {{ $g -> k_gb }}</td>
					<td>{{ $g -> s_gd }} {{ $g -> s_nama }} {{ $g -> s_gb }}</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
		
		<table>
			<tr><td width="70%"></td><td width="30%">
				{{ config('custom.profil.alamat.kabupaten') }}, {{ formatTanggal(date('Y-m-d')) }}
			</td></tr>
			<tr><td></td>
				<td>
					@if($gelombang -> ujian -> prodi -> kepala -> ttd != '')
					<img src="{{ url('/getimage/' . $gelombang -> ujian -> prodi -> kepala -> ttd) }}" style="display: block;max-width: 200px;"/>
					@else
					<br/>
					<br/>
					<br/>
					<br/>	
					@endif
					{{$gelombang -> ujian -> prodi -> kepala -> gelar_depan }} {{$gelombang -> ujian -> prodi -> kepala -> nama }} {{$gelombang -> ujian -> prodi -> kepala -> gelar_belakang }}
					
				</td>
			</tr>
		</table>
		<script>
			window.print();
		</script>
		@endif
	</body>
</html>																			