@extends('app')

@section('title')
Aktivitas Perkuliahan
@endsection

@push('scripts')
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({no_results_text: "Tidak ditemukan hasil pencarian untuk: "});
	});
	$('.filter').change(function(){
		window.location.href = '{{ route('mahasiswa.aktivitas') }}/' + $(this).val();
	});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<style>
	.akt th, .ctr{
	text-align: center !important;
	vertical-align: middle !important;
	}
	.akt .rgt{
	text-align: right !important;
	}
	
	.chosen-container{
	font-size: inherit;
	min-width: 200px;
	}
	.chosen-single{
	padding: 6px 10px !important;
	box-shadow: none !important;
	border-color: #d2d6de !important;
	background: white !important;
	height: 34px !important;
	border-radius: 0px !important;
	}
	.chosen-drop{
	border-color: #d2d6de !important;	
	box-shadow: none;
	min-width: 200px;
	}
	
	#preview{
	width: 166px;
	height: 220px;
	padding: 5px;
	margin: 15px auto;
	border: 1px solid #999;
	position: relative;
	overflow: hidden;
	}
	
	#preview img {
	max-width: 100%;
	max-height: 100%;
	width: auto;
	height: auto;
	position: absolute;
	left: 50%;
	top: 50%;
	-webkit-transform: translate(-50%, -50%);
	-moz-transform: translate(-50%, -50%);
	-ms-transform: translate(-50%, -50%);
	transform: translate(-50%, -50%);
	}
	
	.status{
	width: 100%;
	text-align: center;
	margin-bottom: 10px;
	}
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Aktivitas Perkuliahan</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li><a href="{{ route('mahasiswa.show', $mahasiswa -> id) }}"> {{ ucwords(strtolower($mahasiswa -> nama)) }}</a></li>
		<li class="active">Aktivitas Perkuliahan</li>
	</ol>
</section>
@endsection

@section('content')
<div class="row">
	<div class="col-sm-3">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Nama : {{ $mahasiswa -> NIM }} / {{ ucwords(strtolower($mahasiswa -> nama)) }}</h3>
			</div>
			<div class="box-body" style="padding-left: 0px;">
				<div id="preview">
					<img src="@if(isset($mahasiswa->foto) and $mahasiswa->foto != '')/getimage/{{ $mahasiswa->foto }} @else/images/b.png @endif"></img>
				</div>
				<div class="status">
					@if($mahasiswa -> statusMhs == 1)
					<span class="label label-success">{{ config('custom.pilihan.statusMhs')[$mahasiswa -> statusMhs] }}</span>
					@else
					<span class="label label-default">{{ config('custom.pilihan.statusMhs')[$mahasiswa -> statusMhs] }}</span>
					@endif
				</div>
				
				<ul class="sidebar-menu-small">
					<li><h5>AKSI CEPAT</h5></li>
					@include('mahasiswa.partials._menu2', ['role_id' => \Auth::user() -> role_id, 'mahasiswa' => $mahasiswa])
				</ul>
			</div>
		</div>
	</div>
	
	<div class="col-sm-9">
		
		@include('mahasiswa.partials._data', ['mahasiswa' => $mahasiswa])		
		
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Aktivitas Perkuliahan Mahasiswa</h3>
			</div>
			<div class="box-body">
				<?php 
					$c = 0;
					$status = config('custom.pilihan.statusMhs');
				?>
				<table class="table table-bordered table-striped akt">
					<thead>
						<tr style="background-image: -webkit-gradient(linear,3 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
							<th rowspan="2" width="20px">No.</th>
							<th rowspan="2">Semester</th>
							<th rowspan="2">Status</th>
							<th rowspan="2">IPS</th>
							<th rowspan="2">IPK</th>
							<th colspan="2">Jumlah SKS</th>
						</tr>
						<tr style="background-color: #70bbb0;">
							<th class="ctr" style="background-color: #eef5a4;">Semester</th>
							<th class="ctr" style="background-color: #a2f5a6;">Total</th>
						</tr>
					</thead>
					<tbody>
						@if(count($aktivitas) < 1)
						<tr>
							<td colspan="7" align="center">Belum ada data</td>
						</tr>
						@else
						@foreach($aktivitas as $a)
						<?php
							$c++; 
						?>
						<tr>
							<td>{{ $c }}</td>
							<td class="ctr">{{ $a -> semester }}</td>
							<td class="ctr">{{ $status[$a -> status] }}</td>
							<td class="rgt">{{ $a -> ips }}</td>
							<td class="rgt">{{ $a -> ipk }}</td>
							<td class="rgt">{{ $a -> skss }}</td>
							<td class="rgt">{{ $a -> skst }}</td>
						</tr>
						@endforeach
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection							