@extends('app')

@section('title')
Daftar Pimpinan
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pimpinan
		<small>Daftar Pimpinan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Pimpinan</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Pimpinan</h3>
		<div class="box-tools">
			<a href="{{ route('pimpinan.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data"><i class="fa fa-plus"></i> Tambah Pimpinan</a>
		</div>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No.</th>
					<th>NIDN</th>
					<th>Nama</th>
					<th>Jabatan</th>
					<th>No.SK Penugasan</th>
					<th>Tanggal Mulai Penugasan</th>
					<th>Tanggal Selesai Penugasan</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$pimpinan->count())
				<tr>
					<td colspan="8" align="center">Belum ada data</td>
				</tr>
				@else
				<?php $c=1; ?>
				@foreach($pimpinan as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> dosen -> NIDN }}</td>
					<td>{{ $g -> dosen -> gelar_depan }} {{ $g -> dosen -> nama }} {{ $g -> dosen -> gelar_belakang }}</td>
					<td>{{ $jabatan[$g -> jabatan] }}</td>
					<td>{{ $g -> no_sk }}</td>
					<td>{{ $g -> tanggal_mulai }}</td>
					<td>{{ $g -> tanggal_selesai }}</td>
					<td>
					<a href="{{ route('pimpinan.edit', $g->id) }}" class="btn btn-warning btn-xs btn-flat" title="Edit data pimpinan"><i class="fa fa-pencil-square-o"></i></a>
					<a href="{{ route('pimpinan.delete', $g->id) }}" class="btn btn-danger btn-xs has-confirmation btn-flat" title="Hapus data pimpinan"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
@endsection													