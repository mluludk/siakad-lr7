@extends('app')

@section('title')
Daftar Penelitian Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Penelitian Mahasiswa
		<small>Daftar</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Penelitian Mahasiswa</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Penelitian Mahasiswa</h3>
		<div class="box-tools">
			<a href="{{ route('mahasiswa.penelitian.export') }}" class="btn btn-success btn-xs btn-flat" title="Export Data Penelitian Mahasiswa"><i class="fa fa-file-excel-o"></i> Export Penelitian Mahasiswa</a>
		</div>		
	</div>
	<div class="box-body">
		<?php $c=1; ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th rowspan="2">No.</th>
					<th rowspan="2">Nim</th>
					<th rowspan="2">Nama Mahasiswa</th>
					<th rowspan="2">Tahun</th>
					<th rowspan="2">Judul Penelitian</th>
					<th rowspan="2">Jenis Penelitian</th>
					<th rowspan="2">Ketua Penelitian</th>
					<th colspan="4">Nilai Sumber Dana (Rp)</th>
					<th  width="8%" rowspan="2"></th>
				</tr>
				<tr>
					<th>Mandiri</th>
					<th>Lembaga</th>
					<th>Hibah Nasional</th>
					<th>Hibah Internasional</th>
				</tr>
			</tr>
		</thead>
		<tbody>
			@if(!$penelitian->count())
			<tr>
				<td colspan="11">Belum ada data</td>
			</tr>
			@else
			@foreach($penelitian as $b)
			<tr>
				<td>{{ $c }}</td>
					<td>{{ $b -> NIM }}</td>
					<td><a href="{{url('/mahasiswa/' . $b -> mahasiswa_id) }}">{{ $b -> mahasiswa }}</td>
					<td>{{ $b -> tahun }}</td>
					<td>{{ $b -> judul }}</td>
					<td>@if($b -> jenis == 1) Pribadi @else Kelompok @endif</td>
					<td>{{ $b -> ketua_penelitian }}</td>
					<td>{{ number_format($b -> dana_pribadi, 2, ',', '.') }}</td>
					<td>{{ number_format($b -> dana_lembaga, 2, ',', '.') }}</td>
					<td>{{ number_format($b -> dana_hibah_nasional, 2, ',', '.') }}</td>
					<td>{{ number_format($b -> dana_hibah_internasional, 2, ',', '.') }}</td>
				<td>
					<a href="{{ route('mahasiswa.penelitian.edit', $b -> penelitian_id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data penelitian"><i class="fa fa-pencil-square-o"></i> Edit</a>
					<a href="{{ route('mahasiswa.penelitian.delete', $b -> penelitian_id) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Hapus data penelitian"><i class="fa fa-trash"></i> Hapus</a>
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