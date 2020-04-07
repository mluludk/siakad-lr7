@extends('app')

@section('title')
Daftar Jurnal Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Jurnal Mahasiswa
		<small>Daftar</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Jurnal Mahasiswa</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Jurnal Mahasiswa</h3>
		<div class="box-tools">
			<a href="{{ route('mahasiswa.jurnal.export') }}" class="btn btn-success btn-xs btn-flat" title="Export Data Jurnal Mahasiswa"><i class="fa fa-file-excel-o"></i> Export Jurnal Mahasiswa</a>
		</div>		
	</div>
	<div class="box-body">
		<?php $c=1; ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No.</th>
					<th>Nama Mahasiswa</th>
					<th>Judul Jurnal</th>
					<th>Nama Jurnal</th>
					<th>Website</th>
					<th>Level</th>
					<th>Penerbit</th>
					<th>ISSN</th>
					<th>Akreditasi</th>
					<th>Tahun</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$jurnal->count())
				<tr>
					<td colspan="11">Belum ada data</td>
				</tr>
				@else
				@foreach($jurnal as $b)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $b -> mahasiswa }} ({{ $b -> NIM }})</td>
					<td>{{ $b -> judul_artikel }}</td>
					<td>{{ $b -> nama_jurnal }}</td>
					<td>{{ $b -> website_jurnal }}</td>
					<td>{{ $level[$b-> level_jurnal] }}</td>
					<td>{{ $b -> penerbit }}</td>
					<td>{{ $b -> issn }}</td>
					<td>@if($b -> akreditasi == 1) Sudah @else Belum @endif</td>
					<td>{{ $b -> tahun_terbit }}</td>
					<td>
						<a href="{{ route('mahasiswa.jurnal.edit', $b -> jurnal_id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data jurnal"><i class="fa fa-pencil-square-o"></i> Edit</a>
						<a href="{{ route('mahasiswa.jurnal.delete', $b -> jurnal_id) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Hapus data jurnal"><i class="fa fa-trash"></i> Hapus</a>
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