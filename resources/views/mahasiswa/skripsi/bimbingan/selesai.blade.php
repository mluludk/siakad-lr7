@extends('app')

@section('title')
Mahasiswa Selesai Bimbingan Skripsi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Mahasiswa Selesai Bimbingan Skripsi</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Mahasiswa Selesai Bimbingan Skripsi</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Data Mahasiswa Selesai Bimbingan Skripsi</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,3 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No.</th>
					<th>NIM</th>
					<th>Nama</th>
					<th>No. HP</th>
					<th>L/P</th>
					<th>PRODI</th>
					<th>Program</th>
					<th>Judul Skripsi</th>
				</tr>
			</thead>
			<tbody>
				@if(!$selesai -> count())
				<tr><td colspan="9" align="center">Data Mahasiswa tidak ditemukan</td></tr>
				@else
				<?php 
					$c = 1;
				?>
				@foreach($selesai as $k)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $k -> NIM }}</td>
					<td>{{ $k -> nama }}</td>
					<td>{{ $k -> hp ?? $k -> telp }}</td>
					<td>{{ $k -> jenisKelamin }}</td>
					<td>{{ $k -> strata }} {{ $k -> singkatan }}</td>
					<td>{{ $k -> program}}</td>
					<td>
						{{ $k -> judul_pengajuan }}
						@if($k -> judul_revisi != '')
						<br/>
						<strong>Revisi:</strong>
						<br/>
						<span class="text-danger">{{ $k -> judul_revisi }}</span>
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