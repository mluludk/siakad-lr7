@extends('app')

@section('title')
Daftar Prodi
@endsection

@section('header')
<section class="content-header">
	<h1>
		PRODI
		<small>Daftar PRODI</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar PRODI</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar PRODI</h3>
		<div class="box-tools">
			<a href="{{ route('prodi.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data"><i class="fa fa-plus"></i> Tambah PRODI</a>
		</div>
	</div>
	<div class="box-body">
		@if(!$prodi->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No.</th>
					<th>Kode</th>
					<th colspan="2">Nama</th>
					<th>Strata</th>
					<th>Kaprodi</th>
					<th>Wilayah</th>
					<th colspan="2">No.SK</th>
					<th>Peringkat</th>
					<th>Tanggal Daluarsa</th>
					<th>Status Daluarsa</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($prodi as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> kode_dikti }}</td>
					<td>{{ $g -> nama }}</td>
					<td>{{ $g -> singkatan }}</td>
					<td>{{ $g -> strata }}</td>
					<td>{{ $g -> kepala -> gelar_depan ?? '' }} {{ $g -> kepala -> nama ?? '' }} {{ $g -> kepala -> gelar_belakang ?? '' }}</td>
					<td>{{ $g -> wilayah }}</td>
					<td>{{ $g -> no_sk }}</td>
					<td>{{ substr($g -> tgl_sk, 6, 4) }}</td>
					<td>{{ $g -> peringkat }}</td>
					<td>{{ $g -> tgl_daluarsa }}</td>
					<td>
						@if(strtotime($g -> tgl_daluarsa) < time())
						<button class="btn btn-danger btn-xs btn-flat">Kadaluarsa</button>
						@else
						<button class="btn btn-success btn-xs btn-flat">Berlaku</button>
						@endif
					</td>
					<td>
						<a href="{{ route('prodi.riwayat.index', $g -> id) }}" class="btn btn-info btn-xs btn-flat" title="Riwayat data prodi"><i class="fa fa-clock-o"></i> Histori</a>
						<a href="{{ route('prodi.edit', $g->id) }}" class="btn btn-warning btn-xs btn-flat" title="Edit data prodi"><i class="fa fa-pencil-square-o"></i> Edit</a>
						<a href="{{ route('prodi.delete', $g->id) }}" class="btn btn-danger btn-xs has-confirmation btn-flat" title="Hapus data prodi"><i class="fa fa-trash"></i> Hapus</a>
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