@extends('app')

@section('title')
Daftar Sertifikasi Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Riwayat Sertifikasi</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen') }}">Dosen</a></li>
		<li class="active">Riwayat Sertifikasi</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Riwayat Sertifikasi Dosen</h3>
	</div>
	<div class="box-body">
		@if(!$sertifikasi->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php 
			$c=1; 
		?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No</th>
					<th>Nama</th>
					<th>No. Peserta</th>
					<th>Bidang Studi</th>
					<th>Jenis Sertifikasi</th>
					<th>Tahun Sertifikasi</th>
					<th>No SK Sertifikasi</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($sertifikasi as $b)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $b -> dosen }}</td>
					<td>{{ $b -> NIDN }}</td>
					<td>{{ $b -> bidang_studi }}</td>
					<td>{{ config('custom.pilihan.sertifikasi')[$b -> sertifikasi_id] }}</td>
					<td>{{ $b -> tahun }}</td>
					<td>{{ $b -> no_serdik }}</td>
					<td>
						<a href="{{ route('dosen.sertifikasi.edit', [$b -> dosen_id, $b -> id]) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data sertifikasi"><i class="fa fa-pencil-square-o"></i> Edit</a>
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>
@endsection															