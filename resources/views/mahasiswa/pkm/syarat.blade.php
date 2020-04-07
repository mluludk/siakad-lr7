@extends('app')

@section('title')
Syarat Pendaftaran PKM
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Pendaftaran PKM</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Syarat Pendaftaran PKM</li>
	</ol>
</section>
@endsection

@section('content')
<style>
	th{
	vertical-align: middle !important;
	}
</style>
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Syarat Pendaftaran PKM</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th rowspan="2">NO</th>
					<th rowspan="2">Persyaratan yang harus dipenuhi</th>
					<th colspan="2">Status</th>
				</tr>
				<tr>
					<th>Belum tervalidasi</th>
					<th>Tervalidasi</th>
				</tr>
			</thead>
			<tbody>
				<?php $n = 1; ?>
				@foreach($tanggungan as $k => $v)
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $v }}</td>
					@if(isset($invalid[$k]))
					<td>
						<i class="fa fa-times text-danger"></i>
						@if($k == 'sks' and $invalid['sks'] == 1)
						<a href="{{ route('mahasiswa.kemajuan', $data -> id) }}" class="btn btn-danger btn-xs btn-flat">Cek Tanggungan SKS</a>
						@endif
					</td>
					<td></td>
					@else
					<td></td>
					<td><i class="fa fa-check text-success"></i></td>
					@endif
				</tr>
				<?php $n++; ?>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection																																																																																														