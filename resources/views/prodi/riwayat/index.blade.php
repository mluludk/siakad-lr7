@extends('app')

@section('title')
Riwayat Prodi {{ $prodi_data -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Prodi {{ $prodi_data -> nama }}
		<small>Riwayat</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/prodi') }}"> Daftar Prodi</a></li>
		<li class="active">Riwayat Prodi {{ $prodi_data -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Riwayat Prodi {{ $prodi_data -> nama }}</h3>
		<div class="box-tools">
			<a href="{{ route('prodi.riwayat.create', $prodi_data -> id) }}" class="btn btn-primary btn-xs btn-flat" title="Tambah Data"><i class="fa fa-plus"></i> Tambah Riwayat</a>
		</div>
	</div>
	<div class="box-body">
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
				<?php $c=1; ?>
				@if(!$riwayat -> count())
				<tr>
					<td colspan="13" align="center">Belum ada data</td>
				</tr>
				@else
				@foreach($riwayat as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> kode_dikti }}</td>
					<td>{{ $g -> nama }}</td>
					<td>{{ $g -> singkatan }}</td>
					<td>{{ $g -> strata }}</td>
					<td>{{ $g -> kaprodi }}</td>
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
						<a href="{{ route('prodi.riwayat.edit', [$g -> prodi_id, $g->id]) }}" class="btn btn-warning btn-xs btn-flat" title="Edit data"><i class="fa fa-pencil-square-o"></i> Edit</a>
						<a href="{{ route('prodi.riwayat.delete', [$g -> prodi_id, $g->id]) }}" class="btn btn-danger btn-xs has-confirmation btn-flat" title="Hapus data"><i class="fa fa-trash"></i> Hapus</a>
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