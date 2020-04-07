@extends('app')

@section('title')
Daftar Ruang Kuliah
@endsection

@section('header')
<section class="content-header">
	<h1>
		Ruang Kuliah
		<small>Daftar Ruang Kuliah</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Ruang Kuliah</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Ruang Kuliah</h3>
		<div class="box-tools">
			<a href="{{ route('ruangan.create') }}" class="btn btn-primary btn-xs btn-flat" title="Pendaftaran ruang kuliah baru"><i class="fa fa-plus"></i> Tambah Ruangan</a>
		</div>
	</div>
	<div class="box-body">
		@if(!$ruangan->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No</th>
					<th>Nama</th>
					<th>Ruang</th>
					<th>Lokasi</th>
					<th>Kapasitas</th>
					<th>Fasilitas</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($ruangan as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> kampus }}</td>
					<td>{{ $g -> nama }}</td>
					<td>{{ $g -> gedung }}</td>
					<td>@if(intval($g -> kapasitas)){{ $g -> kapasitas }} <span>orang</span>@endif</td>
					<td>{{ $g -> fasilitas }}</td>
					<td>
						<a href="{{ route('ruangan.edit', $g->id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data ruangan"><i class="fa fa-pencil-square-o"></i> Edit</a>
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