@extends('app')

@section('title')
Daftar Jurnal Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Jurnal Dosen
		<small>Daftar</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Jurnal Dosen</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Jurnal Dosen</h3>
		<div class="box-tools">
			<a href="{{ route('dosen.jurnal.export') }}" class="btn btn-success btn-xs btn-flat" title="Export Data Jurnal Dosen"><i class="fa fa-file-excel-o"></i> Export Jurnal Dosen</a>
			<a href="{{ route('dosen.jurnal.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data Jurnal Dosen"><i class="fa fa-plus"></i> Tambah Jurnal Dosen</a>
		</div>		
	</div>
	<div class="box-body">
		@if(!$jurnal->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No.</th>
					<!--th>NIP</th>
					<th>NIDN</th>
					<th>NIK</th-->
					<th>Nama</th>
					<th>Judul</th>
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
				@foreach($jurnal as $b)
				<tr>
					<td>{{ $c }}</td>
					<!--td>{{ $b -> NIP }}</td>
					<td>{{ $b -> NIDN }}</td>
					<td>{{ $b -> noIndentitas }}</td-->
					<td>{{ $b -> dosen }}</td>
					<td>{{ $b -> judul_artikel }}</td>
					<td>{{ $b -> nama_jurnal }}</td>
					<td>{{ $b -> website_jurnal }}</td>
					<td>@if($b -> level_jurnal == 1) Nasional @else Internasional @endif</td>
					<td>{{ $b -> penerbit }}</td>
					<td>{{ $b -> issn }}</td>
					<td>@if($b -> akreditasi == 1) Sudah @else Belum @endif</td>
					<td>{{ $b -> tahun_terbit }}</td>
					<td>
						<a href="{{ route('dosen.jurnal.edit', $b -> jurnal_id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data jurnal"><i class="fa fa-pencil-square-o"></i> Edit</a>
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