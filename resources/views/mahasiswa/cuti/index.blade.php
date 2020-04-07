@extends('app')

@section('title')
Daftar Cuti Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Cuti Mahasiswa
		<small>Daftar</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li class="active">Daftar Cuti</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Cuti Mahasiswa</h3>
		<div class="box-tools">
			<a href="{{ route('mahasiswa.cuti.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data"><i class="fa fa-plus"></i> Pengajuan Cuti</a>
		</div>		
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No.</th>
					<th>NIM</th>
					<th>Nama</th>
					<th>Prodi</th>
				<th>Status</th>
				<th>Tahun Akademik</th>
				<th>Tanggal Mulai</th>
				<th>Keterangan</th>
				<th></th>
				</tr>
				</thead>
				<tbody>
				@if(!$data -> count())
				<tr>
				<td colspan="8" align="center">Belum ada data</td>
				</tr>
				@else
				<?php 
				$per_page = $data -> perPage();
				$total = $data -> total();
				$c = ($data -> currentPage() - 1) * $per_page;
				$last = $c + $per_page > $total ? $total : $c + $per_page;
				
				$status = [0 => 'Aktif Kuliah', 11 => 'Cuti Resmi', 12 => 'Cuti Tanpa Keterangan'];
				?>
				@foreach($data as $b)
				<?php $c++; ?>
				<tr>
				<td>{{ $c }}</td>
				<td>{{ $b -> NIM }}</td>
				<td>{{ $b -> nama }}</td>
				<td>{{ $b -> prodi}}</td>
				<td>{{ $status[$b -> status] }}</td>
				<td>{{ $b -> ta }}</td>
				<td>{{ $b -> tgl_mulai }}</td>
				<td>{{ $b -> keterangan }}</td>
				<td>
				@if($b -> status > 10)
				<a href="{{ route('mahasiswa.cuti.edit', $b -> id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data"><i class="fa fa-pencil-square-o"></i> Edit</a>
				<a href="{{ route('mahasiswa.cuti.reactivate', $b -> id) }}" class="btn btn-success btn-flat btn-xs" title="Aktifkan Mahasiswa"><i class="fa fa-check"></i> Aktifkan</a>
				@else
				<button class="btn btn-warning btn-flat btn-xs" title="Edit data" disabled><i class="fa fa-pencil-square-o"></i> Edit</button>
				<button class="btn btn-success btn-flat btn-xs" title="Aktifkan Mahasiswa" disabled><i class="fa fa-check"></i> Aktifkan</button>						
				@endif
				<a href="{{ route('mahasiswa.cuti.delete', $b -> id) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Delete"><i class="fa fa-trash"></i> Delete</a>
				</td>
				</tr>
				@endforeach
				@endif
				</tbody>
				</table>
				</div>
				</div>
				@endsection																			