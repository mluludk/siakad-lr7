@extends('app')

@section('title')
Bimbingan Skripsi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Bimbingan Skripsi</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Bimbingan Skripsi</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Data Mahasiswa Bimbingan Skripsi</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,3 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th rowspan="2">No.</th>
					<th rowspan="2">NIM</th>
					<th rowspan="2">Nama</th>
					<th rowspan="2">No. HP</th>
					<th rowspan="2">L/P</th>
					<th rowspan="2">PRODI</th>
					<th rowspan="2">Program</th>
					<th rowspan="2">Judul Skripsi</th>
					<th rowspan="2">Catatan Bimbingan</th>
					<th colspan="2">Validasi UJIAN</th>
				</tr>
				<tr>
					<th class="ctr" style="background-color: #ddd;"> Proposal</th>
					<th class="ctr" style="background-color: #f1f60b;"> Skripsi</th>
				</tr>
			</thead>
			<tbody>
				@if(!$bimbingan -> count())
				<tr><td colspan="11" align="center">Data Mahasiswa tidak ditemukan</td></tr>
				@else
				<?php 
					$c = 1;
				?>
				@foreach($bimbingan as $bim)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $bim -> NIM }}</td>
					<td>{{ $bim -> nama }}</td>
					<td>{{ $bim -> hp ?? $bim -> telp }}</td>
					<td>{{ $bim -> jenisKelamin }}</td>
					<td>{{ $bim -> strata }} {{ $bim -> singkatan }}</td>
					<td>{{ $bim -> program}}</td>
					<td>
						{{ $bim -> judul }}
					</td>
					<td> 
						<a href="{{ route('mahasiswa.skripsi.bimbingan.create', $bim -> skripsi_id) }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i> Bimbingan</a>
					</td>
					<td>
						@if($bim -> validasi_proposal == 'y')
						<button class="btn btn-success btn-xs btn-flat" disabled><i class="fa fa-check"></i> SIAP UJIAN</button>
						@else
						<a href="{{ route('skripsi.validasi', [$bim -> skripsi_id, 'proposal']) }}" class="btn btn-warning btn-xs btn-flat has-confirmation" data-message="Validasi Mahasiswa {{ $bim -> nama }} untuk Ujian Proposal?"><i class="fa fa-check"></i> Validasi</a>
						@endif
					</td>
					<td>
						@if($bim -> validasi_kompre == 'y')
						<button class="btn btn-success btn-xs btn-flat" disabled><i class="fa fa-check"></i> SIAP UJIAN</button>
						@else
						<a href="{{ route('skripsi.validasi', [$bim -> skripsi_id, 'komprehensif']) }}" class="btn btn-danger btn-xs btn-flat has-confirmation" data-message="Validasi Mahasiswa {{ $bim -> nama }} untuk Ujian Komprehensif?"><i class="fa fa-check"></i> Validasi</a>
						@endif
					</td>
				</tr>
				<?php 
					$c++;
				?>
				@endforeach				
				@endif
			</tbody>
		</table>
	</div>
@endsection																														