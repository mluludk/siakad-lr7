@extends('app')

@section('title')
Daftar Unsur & Bobot Penilaian
@endsection

@section('header')
<section class="content-header">
	<h1>
		Nilai
		<small>Daftar Unsur & Bobot Penilaian</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Unsur & Bobot Penilaian</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Komponen Penilaian</h3>
		<div class="box-tools">
			<a href="{{ route('jenisnilai.create') }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i>Tambah</a>
		</div>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No.</th>
					<th>Program Studi</th>
					<th>Nama Unsur Penilaian</th>
					<th>Bobot Penilaian</th>
					<th>Status</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(count($types) < 1)
				<tr>
					<td colspan="5" align="center">Belum ada data</td>
				</tr>
				@else
				<?php $n = 1; ?>
				@foreach($types as $type)
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $type -> prodi -> strata ?? '-' }} - {{ $type -> prodi -> nama ?? '-' }}</td>
					<td>{{ $type -> nama }}</td>
					<td>{{ $type -> bobot }} %</td>
					<td>@if($type -> aktif == 'y') <span class="label label-success">Aktif</span> @else <span class="label label-danger">Tidak Aktif</span> @endif</td>
					<td>
						@if($type -> id > 5)
						<a href="{{ route('jenisnilai.edit', $type -> id) }}" class="btn btn-warning btn-xs btn-flat"><i class= "fa fa-edit"></i> Edit</a>
						<a href="{{ route('jenisnilai.delete', $type -> id) }}" class="btn btn-danger btn-xs btn-flat"><i class= "fa fa-trash"></i> Hapus</a>
						@endif
					</td>
				</tr>
				<?php $n++; ?>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
@endsection
