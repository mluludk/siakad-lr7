@extends('app')

@section('title')
Daftar Program
@endsection

@section('header')
<section class="content-header">
	<h1>
		Program
		<small>Daftar Program</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Program</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Program</h3>
		<div class="box-tools">
			<a href="{{ route('kelas.create') }}" class="btn btn-info btn-xs btn-flat" title="Pendaftaran kelas baru"><i class="fa fa-plus"></i></a>
		</div>
	</div>
	<div class="box-body">
		@if(!$kelas->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No</th>
					<th>Nama</th>
					<th>Keterangan</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($kelas as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> nama }}</td>
					<td>{{ $g -> keterangan }}</td>
					<td>
						<a href="{{ route('kelas.edit', $g->id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data Program"><i class="fa fa-pencil-square-o"></i> Edit</a>
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif
@endsection												