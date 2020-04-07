@extends('app')

@section('title')
Wisuda
@endsection

@section('header')
<section class="content-header">
	<h1>
		Wisuda
		<small>Jadwal Wisuda</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Jadwal Wisuda</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Jadwal Wisuda</h3>
		<div class="box-tools">
			<a href="{{ route('mahasiswa.wisuda.create') }}" class="btn btn-primary btn-xs btn-flat" title="Buat Jadwal Wisuda"><i class="fa fa-plus"></i> Tambah Wisuda</a>
		</div>
	</div>
	<div class="box-body">
		@if(!$wisuda -> count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,3 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th rowspan="2">No</th>
					<th rowspan="2">WISUDA KE</th>
					<th rowspan="2">TANGGAL WISUDA</th>
					<th rowspan="2">SK NOMOR</th>
					<th rowspan="2">TANGGAL SK</th>
					<th rowspan="2">SMT YUDISIUM</th>
					<th colspan="3" align="center" style="background-color: #779a06;">STATUS</th>
					<th rowspan="2"></th>
				</tr>
				
				<tr>
					<th class="ctr" style="background-color: #ddd;">JADWAL</th>
					<th class="ctr" style="background-color: #a2f5a6;">PESERTA</th>
					<th class="ctr" style="background-color: #f0e5e5;">KUOTA</th>
				</tr>
			</thead>
			<tbody>
				@foreach($wisuda as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> nama }}</td>
					<td>{{ formatTanggal(date('Y-m-d', strtotime($g -> tanggal))) }}</td>
					<td>{{ $g -> SKYudisium ?? '' }}</td>
					<td>{{ formatTanggal(date('Y-m-d', strtotime($g -> tglSKYudisium))) }}</td>
					<td>{{ $g -> smt_yudisium ?? '' }}</td>
					<td>@if($g -> daftar == 'y')<span class="label label-success">Buka</span>@else<span class="label label-danger">Tutup</span>@endif</td>
					<td>{{ $g -> peserta }}</td>
					<td>@if(intval($g -> kuota)){{ $g -> kuota }}@endif</td>
					<td>
						<a href="{{ route('mahasiswa.wisuda.peserta', $g -> id) }}" class="btn btn-info btn-flat btn-xs" title="Peserta Wisuda"><i class="fa fa-group"></i> Peserta</a>
						<a href="{{ route('mahasiswa.wisuda.edit', $g -> id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit Jadwal Wisuda"><i class="fa fa-pencil-square-o"></i> Edit</a>
						<a href="{{ route('mahasiswa.wisuda.delete', $g -> id) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Hapus Jadwal Wisuda"><i class="fa fa-trash"></i> Hapus</a>
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