@extends('app')

@section('title')
Daftar Kepangkatan Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Riwayat Kepangkatan</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen') }}">Dosen</a></li>
		<li class="active">Riwayat Kepangkatan</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Riwayat Kepangkatan Dosen</h3>
	</div>
	<div class="box-body">
		@if(!$kepangkatan->count())
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
					<th>Pangkat</th>
					<th>SK Pangkat</th>
					<th>Tgl SK Pangkat</th>
					<th>TMT Pangkat</th>
					<th>Masa Kerja</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($kepangkatan as $b)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $b -> dosen }}</td>
					<td>{{ config('custom.pilihan.golongan')[$b -> pangkat] }} {{ config('custom.pilihan.pangkat')[$b -> pangkat] }}</td>
					<td>{{ $b -> sk }}</td>
					<td>{{ $b -> tgl }}</td>
					<td>{{ $b -> tmt }}</td>
					<td>
						@if(intval($b -> masa_kerja_tahun)) {{ $b -> masa_kerja_tahun }} @else 0 @endif tahun, 
						@if(intval($b -> masa_kerja_bulan)) {{ $b -> masa_kerja_bulan }} @else 0 @endif bulan
					</td>
					<td>
						<a href="{{ route('dosen.kepangkatan.edit', [$b -> dosen_id, $b -> id]) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data kepangkatan"><i class="fa fa-pencil-square-o"></i> Edit</a>
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