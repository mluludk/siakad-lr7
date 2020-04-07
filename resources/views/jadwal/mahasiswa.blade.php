@extends('app')

@section('title')
Jadwal Perkuliahan
@endsection

@push('styles')
<style>
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
	.sidebar-menu-small h5{
	text-align: center;
	background-color: #023355;
	color: white;
	padding: 5px;
	}
	.sidebar-menu-small {
    list-style: none;
    margin: 0;
    padding: 0
	}
	.sidebar-menu-small > li {
    position: relative;
    margin: 0;
    padding: 0
	}
	.sidebar-menu-small > li > a {
    padding: 5px 2px 5px 12px;
    display: block
	}
	.sidebar-menu-small > li > a > .fa{
    width: 20px
	}
	
	.sidebar-menu-small > li > a {
    border-left: 3px solid transparent;
	color: #120101;
	border-bottom: 1px solid #bbb;
	}
	.sidebar-menu-small > li:hover > a,
	.sidebar-menu-small > li.active > a {
    color: #3c8dbc;
    background: #f5f9fc;
    border-left-color: #3c8dbc
	}
	
/* 	.table td, th{
	border-top-width: 0px !important;
	} */
</style>
@endpush
@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Jadwal Perkuliahan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Jadwal Perkuliahan</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Jadwal Perkuliahan</h3>
	</div>
	<div class="box-body">
		<?php 
			$c = 1; 
			$today = date('N');
		?>
		<table class="table table-bordered table-hover">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No.</th>
					<th>Jadwal</th>
					<th>Mata Kuliah</th>
					<th>Dosen</th>
					<th>Semester</th>
					<th>Program</th>
					<th>Kelas</th>
					<th>Ruang</th>
					<th class="hidden-print">RPP</th>
					<th class="hidden-print">Silabus</th>
				</tr>
			</thead>
			<tbody>
				@if(!$data -> count())
				<td colspan="9" align="center">Belum ada data</td>
				@else
				@foreach($data as $mk)
				<tr @if($mk -> hari == $today)class="info" @endif >
					<td>{{ $c }}</td>
					<td>@if(isset($mk -> hari)){{ config('custom.hari')[$mk -> hari] }}, {{ $mk -> jam_mulai }} - {{ $mk -> jam_selesai }}@else<span>-</span>@endif</td>
					<td>{{ $mk -> matkul }} ({{ $mk -> kd }})</td>
					<td>{{ formatTimDosen($mk -> matkul_tapel -> tim_dosen) }}</td>
					<td>{{ $mk -> semester }}</td>
					<td>{{ $mk -> program }}</td>
					<td>{{ $mk -> kelas }}</td>
					<td>{{ $mk -> ruang ?? '-' }}</td>
					<td class="hidden-print">@if(isset( $mk -> rpp))<a href="{{ url('/download/' . $mk -> rpp . '/' . csrf_token()) }}" class="btn btn-primary btn-xs" title="Download"><i class="fa fa-download"></i></a>@else<a href="" class="btn btn-default btn-xs" disabled="disabled" title="Download"><i class="fa fa-download"></i></a>@endif</td>
					<td class="hidden-print">@if(isset( $mk -> silabus))<a href="{{ url('/download/' . $mk -> silabus . '/' . csrf_token()) }}" class="btn btn-primary btn-xs" title="Download"><i class="fa fa-download"></i></a>@else<a href="" class="btn btn-default btn-xs" disabled="disabled" title="Download"><i class="fa fa-download"></i></a>@endif</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
@endsection																			