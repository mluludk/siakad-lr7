@extends('app')

@section('title')
Daftar Kurikulum
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Daftar Kurikulum</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Kurikulum</li>
	</ol>
</section>
@endsection

@section('content')
<style>
	th{
	vertical-align: middle !important;
	text-align: center;
	}
</style>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Kurikulum</h3>
		<div class="box-tools">
			<a href="{{ route('prodi.kurikulum.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data"><i class="fa fa-plus"></i> Tambah Kurikulum</a>
		</div>
	</div>
	<?php
		$c = 0;
	?>
	<div class="box-body">
			<table class="table table-bordered">
				<tr>
					<th width="50px">No.</th>
					<th>Nama</th>
					<th>Program Studi</th>
				</tr>
				@if(!count($kurikulum))
				<tr>
					<td colspan="3" align="center">Data tidak ditemukan</td>
				</tr>
				@else
				@foreach($kurikulum as $k)
				<?php 
					$c++;
				?>
				<tr>
					<td>{{ $c }}</td>
					<td><a href="{{ route('prodi.kurikulum.index') }}?nama={{ $k -> nama }}">{{ $k -> nama }}</a></td>
					<td>{{ $k -> strata }} {{ $k -> prodi }}</td>
				</tr>
				@endforeach
				@endif
			</table>
	</div>
</div>
@endsection																										