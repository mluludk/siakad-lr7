@extends('app')

@section('title')
Daftar Fungsional Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Riwayat Fungsional</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen') }}">Dosen</a></li>
		<li class="active">Riwayat Fungsional</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Riwayat Fungsional Dosen</h3>
	</div>
	<div class="box-body">
		@if(!$fungsional->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php 
			$c=1; 
		?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No</th>
					<th>Nama</th>
					<th>Jabatan</th>
					<th>SK Jabatan</th>
					<th>TMT Jabatan</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($fungsional as $b)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $b -> dosen }}</td>
					<td>{{ config('custom.pilihan.jabatan_fungsional')[$b -> jabatan] }}</td>
					<td>{{ $b -> sk }}</td>
					<td>{{ $b -> tmt }}</td>
					<td>
						<a href="{{ route('dosen.fungsional.edit', [$b -> dosen_id, $b -> id]) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data fungsional"><i class="fa fa-pencil-square-o"></i> Edit</a>
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