@extends('app')

@section('title')
Daftar Semester
@endsection

@section('header')
<section class="content-header">
	<h1>
		Tahun Akademik
		<small>Daftar Tahun Akademik</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Tahun Akademik</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Tahun Akademik </h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<tr>
				<th>No.</th>
				<th>Tahun Akademik</th>
				<th>Waktu</th>
				<th>KRS</th>
				<th>Status</th>
			</tr>
			@if(!$tapel->count())
			<tr>
				<td colspan="5">Belum ada data</td>
			</tr>
			@else
			<?php $c = 1; ?>
			@foreach($tapel as $smt)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ $smt -> nama }}</td>
				<td>{{ formatTanggal($smt -> mulai) }} - {{ formatTanggal($smt -> selesai) }}</td>
				<td>{{ formatTanggal($smt -> mulaiKrs) }} - {{ formatTanggal($smt -> selesaiKrs) }}</td>
				<td>@if($smt -> aktif == 'y')<span style="color: #02bc63">Aktif</span>@else<span style="color: #999;">Tidak aktif</span>@endif</td>
			</tr>
			<?php $c++; ?>
			@endforeach
			@endif
		</table>
	</div>
	</div>
	@endsection																			