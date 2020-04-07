@extends('app')

@section('title')
Daftar Tulisan Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Tulisan Mahasiswa
		<small>Daftar</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Tulisan Mahasiswa</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Tulisan Mahasiswa</h3>
		<div class="box-tools">
			<a href="{{ route('mahasiswa.tulisan.export') }}" class="btn btn-success btn-xs btn-flat" title="Export Data Tulisan Mahasiswa"><i class="fa fa-file-excel-o"></i> Export Tulisan Mahasiswa</a>
		</div>		
	</div>
	<div class="box-body">
		<?php $c=1; ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No.</th>
					<th>Nim</th>
					<th>Nama Mahasiswa</th>
					<th>Judul Tulisan</th>
					<th>Link</th>
					<th>Tahun</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$tulisan->count())
				<tr>
					<td colspan="7">Belum ada data</td>
				</tr>
				@foreach($tulisan as $b)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $b -> NIM }}</td>
					<td><a href="{{url('/mahasiswa/' . $b -> mahasiswa_id) }}">{{ $b -> mahasiswa }}</a></td>
					<td>{{ $b -> judul }}</td>
					<td>{{ $b -> link }}</td>
					<td>{{ $b -> tahun }}</td>
					<td>
						<a href="{{ route('mahasiswa.tulisan.edit', $b -> tulisan_id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data tulisan"><i class="fa fa-pencil-square-o"></i> Edit</a>
						<a href="{{ route('mahasiswa.tulisan.delete', $b -> tulisan_id) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Hapus data tulisan"><i class="fa fa-trash"></i> Hapus</a>
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