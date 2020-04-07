@extends('app')

@section('title')
Daftar Prestasi Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Prestasi Mahasiswa
		<small>Daftar</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li class="active">Prestasi</li>
	</ol>
</section>
@endsection

@section('content')
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">Daftar Prestasi Mahasiswa</h3>	
			<div class="box-tools">
				<a href="{{ route('mahasiswa.prestasi.export') }}" class="btn btn-success btn-xs btn-flat" title="Export"><i class="fa fa-file-excel-o"></i> Export</a>
			</div>
		</div>
		<div class="box-body">
			<table class="table table-bordered table-striped">
				<thead>
					<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
						<th>No.</th>
						<th>NIM</th>
						<th>Nama</th>
						<th>Bidang Prestasi</th>
						<th>Tingkat Prestasi</th>
						<th>Nama Prestasi</th>
						<th>Tahun</th>
						<th>Penyelenggara</th>
						<th>Peringkat</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@if(!$prestasi->count())
					<tr>
						<td colspan="10" align="center">Data tidak ditemukan</td>
					</tr>
					@else
					<?php 
						$c=1; 
						$jenis = config('custom.pilihan.dikti.jenis_prestasi');
						$tingkat = config('custom.pilihan.dikti.tingkat_prestasi');
					?>
					@foreach($prestasi as $b)
					<tr>
						<td>{{ $c }}</td>
						<td>{{ $b -> NIM }}</td>
						<td>
							<a href="{{url('/mahasiswa/' . $b -> mahasiswa_id) }}">{{ $b -> mahasiswa }}</a>
						</td>
						<td>{{ $jenis[$b -> jenis] }}</td>
						<td>{{ $tingkat[$b -> tingkat] }}</td>
						<td>{{ $b -> nama }}</td>
						<td>{{ $b -> tahun }}</td>
						<td>{{ $b -> penyelenggara }}</td>
						<td>{{ $b -> peringkat }}</td>
						<td>
							<a href="{{ route('mahasiswa.prestasi.edit', [$b -> mahasiswa_id, $b -> id]) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data prestasi"><i class="fa fa-pencil-square-o"></i> Edit</a>
							<a href="{{ route('mahasiswa.prestasi.delete', [$b -> mahasiswa_id, $b -> id]) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Hapus data prestasi"><i class="fa fa-trash"></i> Hapus</a>
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