@extends('app')

@section('title')
Peserta Wisuda {{ $wisuda -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Wisuda
		<small>Peserta Wisuda</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="/wisuda"> Jadwal Wisuda</a></li>
		<li class="active">Peserta Wisuda</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Peserta Wisuda {{ $wisuda -> nama }} ({{ formatTanggal(date('Y-m-d', strtotime($wisuda -> tanggal))) }})</h3>
		<div class="box-tools">
			<a href="{{ route('mahasiswa.wisuda.peserta.export', $wisuda -> id) }}" class="btn btn-success btn-xs btn-flat" title="Export Data Peserta Wisuda"><i class="fa fa-file-excel-o"></i> Export Data Peserta Wisuda</a>
		</div>
	</div>
	<div class="box-body">
		@if(!$peserta -> count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c = ($peserta -> currentPage() - 1) * $peserta -> perPage(); ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-color: #dce3e2;">
					<th>No</th>
					<th>NIM</th>
					<th>Nama</th>
					<th>TB(cm)</th>
					<th>PRODI</th>
					<th>Program</th>
					<th>Judul Skripsi</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($peserta as $g)
				<tr>
				<?php $c++; ?>
					<td>{{ $c }}</td>
					<td>{{ $g -> NIM }}</td>
					<td>{{ $g -> nama }}</td>
					<td>{{ $g -> tinggi_badan }}</td>
					<td>{{ $g -> prodi -> strata }} {{ $g -> prodi -> nama }}</td>
					<td>{{ $g -> kelas -> nama }}</td>
					<td>{{ $g -> skripsi -> judul ?? '' }}</td>
					<td>
						<a href="{{ route('mahasiswa.wisuda.peserta.show', [$wisuda -> id, $g -> id]) }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-search"></i> Detail</a>						
						<a href="{{ route('mahasiswa.wisuda.peserta.delete', [$wisuda -> id, $g -> id]) }}" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i> Hapus</a>						
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{!! $peserta -> render() !!}
		@endif
	</div>
</div>
@endsection												