@extends('app')

@section('title')
Pembagian Dosen Pembimbing
@endsection

@section('header')
<section class="content-header">
	<h1>
		Skripsi
		<small>Pembagian Dosen Pembimbing</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/skripsi') }}"> Skripsi</a></li>
		<li class="active">Pembagian Dosen Pembimbing</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Pembagian Dosen Pembimbing</h3>
	</div>
	<div class="box-body">
		@if(!count($pembimbing))
		<p class="text-muted">Data Bimbingan tidak ditemukan</p>
		@else
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No</th>
					<th>Dosen Pembimbing</th>
					<th>No</th>
					<th>NIM</th>
					<th>Mahasiswa</th>
					<th>PRODI</th>
					<th>Judul Skripsi</th>
					<th>Keterangan</th>
				</tr>
			</thead>
			<tbody>
				@foreach($pembimbing as $semester => $id)
				<tr><th colspan="8" style="background-color: #36e6d4;">Tahun Akademik {{ $semester }}</th></tr>
				<?php $c= 0; ?>
				@foreach($id as $m)
				<?php 
					$c++; 
					$e = 0;
				?>
				@foreach($m as $b)
				<?php 
					$e++; 
					$rs = count($m) + 1;
					$cls = $b['sudah'] == 'y' ? ' class="td_sdh_'.$b['id_dosen'].' hidden"' :  ''; 
				?>
				<tr>
					@if($e == 1)
					<td rowspan="{{ $rs }}" id="no_{{ $b['id_dosen'] }}">{{ $c }}</td>
					<td rowspan="{{ $rs }}" id="nm_{{ $b['id_dosen'] }}">{{ $b['dosen'] }}</td>		
					@endif
					<td {!! $cls !!}>{{ $e }}</td>
					<td {!! $cls !!}>{{ $b['nim'] }}</td>
					<td {!! $cls !!}>{{ $b['nama'] }}</td>
					<td {!! $cls !!}>{{ $b['prodi'] }}</td>
					<td {!! $cls !!}>{{ $b['judul'] }}</td>
					<td {!! $cls !!}>
						@if($b['sudah'] == 'y')
						<span class="label label-success">Selesai</span>
						@else
						<span class="label label-warning">Belum</span>
						@endif
					</td>
				</tr>
				@endforeach
				<tr>
					<td colspan="6"><button class="btn btn-warning btn-flat btn-xs btn-tgl-h" data-target="{{ $b['id_dosen'] }}">TOGGLE</button></td>
				</tr>
				@endforeach
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>
@endsection		

@push('scripts')
<script>
	$(document).on('click', '.btn-tgl-v', function(){
		$(this).addClass('btn-tgl-h').removeClass('btn-tgl-v');
		$(this).addClass('btn-warning').removeClass('btn-success');
		var id = $(this).attr('data-target');
		$('.td_sdh_' + id).addClass('hidden');
	});
	
	$(document).on('click', '.btn-tgl-h', function(){
		$(this).addClass('btn-tgl-v').removeClass('btn-tgl-h');
		$(this).addClass('btn-success').removeClass('btn-warning');
		var id = $(this).attr('data-target');
		$('.td_sdh_' + id).removeClass('hidden');
	});
</script>
@endpush

@push('styles')
<style>
	th{text-align: center !important;vertical-align: middle !important}
</style>
@endpush																																						