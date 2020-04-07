@extends('app')

@section('title')
Daftar Bank
@endsection

@section('header')
<section class="content-header">
	<h1>
		Bank
		<small>Daftar Bank</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Bank</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Bank</h3>
		<div class="box-tools">
			<a href="{{ route('bank.create') }}" class="btn btn-primary btn-xs btn-flat" title="Pendaftaran Bank baru"><i class="fa fa-plus"></i> Tambah Bank</a>
		</div>
	</div>
	<div class="box-body">
		@if(!$bank->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No</th>
					<th>Nama</th>
					<th>Singkatan</th>
					<th>API Key</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($bank as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> nama }}</td>
					<td>{{ $g -> singkatan }}</td>
					<td>{{ $g -> api_key }}</td>
					<td>
						<a href="{{ route('bank.edit', $g->id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data bank"><i class="fa fa-pencil-square-o"></i> Edit</a>
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