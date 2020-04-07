@extends('app')

@section('title')
Daftar Kuesioner
@endsection

@section('header')
<section class="content-header">
	<h1>
		Kuesioner
		<small>Daftar Kuesioner</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Kuesioner</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Kuesioner</h3>
		<div class="box-tools">
			<a href="{{ route('kuesioner.create') }}" class="btn btn-info btn-xs btn-flat" title="Input Pertanyaan"><i class="fa fa-plus"></i></a>
		</div>
	</div>
	<div class="box-body">		
		@if(!$kuesioners->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c = 1; ?>
		<table class="table table-bordered">
			<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
				<th>No.</th>
				<th>Kompetensi</th>
				<th>Pertanyaan</th>
				<th>Status</th>
				<th></th>
			</tr>
			@foreach($kuesioners as $kuesioner)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ config('custom.kuesioner.kompetensi')[$kuesioner -> kompetensi] }}</td>
				<td>{{ $kuesioner -> pertanyaan }}</td>
				<td>@if($kuesioner -> tampil == 'y')<span class="fa fa-eye"></span>@else<span class="fa fa-eye-slash"></span>@endif</td>
				<td>
					<a href="{{ route('kuesioner.edit', $kuesioner -> id) }}" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-pencil-square-o"></i> Edit</a>
				</td>
			</tr>
			<?php $c++; ?>
			@endforeach
		</table>
		@endif
	</div>
</div>
@endsection															