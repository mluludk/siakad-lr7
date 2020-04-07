@extends('app')

@section('title')
Skala Nilai
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Skala Nilai</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Skala Nilai</li>
	</ol>
</section>
@endsection

@push('styles')
<style>
	th{
	text-align: center;
	vertical-align: middle !important;
	}
</style>	
@endpush

@push('scripts')
<script>
	$('.filter').change(function(){
		$('form#filter-form').submit();
	});
</script>
@endpush

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/skala') }}" class="form-inline" id="filter-form">
			{!! csrf_field() !!}
			<div class="form-group">
				<label class="sr-only" for="prodi">PRODI</label>
				{!! Form::select('prodi', $prodi, Request::get('prodi'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
			</div>
		</form>
	</div>
</div>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Skala Nilai</h3>
		<div class="box-tools">
			<a href="{{ route('skala.create') }}" class="btn btn-primary btn-xs btn-flat" title="Pendaftaran skala baru"><i class="fa fa-plus"></i> Tambah Skala Nilai</a>
		</div>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,3 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th rowspan="2">No</th>
					<th rowspan="2">PRODI</th>
					<th rowspan="2">NILAI HURUF</th>
					<th rowspan="2">NILAI INDEXS</th>
					<th colspan="2">ANGKA</th>
					<th rowspan="2">KELULUSAN</th>
					<th rowspan="2">PREDIKAT</th>
					<th rowspan="2"></th>
				</tr>
				<tr>
					<th style="background-color: #bdeaef;">Interval 0 - 4</th>
					<th style="background-color: #eef5a4;">Interval 0 - 100</th>
				</tr>
			</thead>
			<tbody>
				@if(!$skala->count())
				<tr>
					<td colspan="6" align="center">Belum ada data</td>
				</tr>
				@else
				<?php $c=1; ?>
				@foreach($skala as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> prodi -> strata  ?? '-' }} {{ $g -> prodi -> nama  ?? '-' }}</td>
					<td>{{ $g -> huruf }}</td>
					<td>{{ $g -> angka }}</td>
					<td>{{ $g -> bobot_min }} - {{ $g -> bobot_max }}</td>
					<td>{{ $g -> bobot_min_100 }} - {{ $g -> bobot_max_100 }}</td>
					<td>@if($g -> lulus == 'y') Lulus @else Tidak Lulus @endif</td>
					<td>{{ $g -> predikat }}</td>
					<td>
						<a href="{{ route('skala.edit', $g->id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit Skala"><i class="fa fa-pencil-square-o"></i> Edit</a>
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