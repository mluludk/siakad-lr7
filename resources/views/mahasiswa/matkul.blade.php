@extends('app')

@section('title')
Kurikulum Matkul
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
</style>
@endpush

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
				
				@if($auth -> role_id != 512)
				<ul class="sidebar-menu-small">
					<li><h5>AKSI CEPAT</h5></li>
					@include('mahasiswa.partials._menu2', ['role_id' => \Auth::user() -> role_id, 'mahasiswa' => $mahasiswa])
				</ul>
				@endif
			</div>
		</div>
	</div>
	
	@section('header')
	<section class="content-header">
		<h1>
			Perkuliahan
			<small>Kurikulum Matkul</small>
		</h1>		
		<ol class="breadcrumb">
			<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
			<li><a href="{{ route('mahasiswa.show', $mahasiswa -> id) }}"> {{ ucwords(strtolower($mahasiswa -> nama)) }}</a></li>
			<li class="active">Kurikulum Matkul</li>
		</ol>
	</section>
	@endsection
	
	<div class="col-sm-9">
		
		@include('mahasiswa.partials._data', ['mahasiswa' => $mahasiswa])		
		
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Kurikulum Matkul</h3>
			</div>
			<div class="box-body">
				<?php 
					$sksw = $sksd = 0;
				?>
				@if(count($matkul) < 1)
				<p class="text-muted">Belum ada data</p>
				@else
				<?php 
					$c = 0;
				?>
				<style>
					th{
					vertical-align: middle !important;
					}
				</style>
				<table class="table table-bordered table-striped">
					<thead>
						<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
							<th rowspan="2">No.</th>
							<th rowspan="2">KODE MK</th>
							<th rowspan="2">MATAKULIAH</th>
							<th rowspan="2">SKS</th>
						<th rowspan="2">SEMESTER</th>
						<th colspan="2" class="ctr">STATUS</th>
						</tr>
						<tr>
						<th class="ctr" style="background-color: #a2f5a6;">BELUM DITEMPUH</th>
						<th class="ctr" style="background-color: #eef5a4;">SUDAH DITEMPUH</th>
						</tr>
						</thead>
						<tbody>
						@foreach($matkul as $k => $g)
						<?php
						$c++; 
						$sudah = false;
						if(isset($ditempuh[$k])) 
						{
						$sudah = true;
						$sksd += $g['sks'];
						}
						$sksw += $g['sks'];
						?>
						<tr>
						<td>{{ $c }}</td>
						<td>{{ $g['kode'] }}</td>
						<td>{{ $g['nama'] }}</td>
						<td>{{ $g['sks'] }}</td>
						<td>{{ $g['semester'] }}</td>
						
						@if($sudah)
						<td></td>
						
						@if(in_array($ditempuh[$k]['nilai'], ['C-', 'D', 'E', '-']))
						<td class="warning">
						<strong>{{ $ditempuh[$k]['nilai'] }}</strong>
						</td>
						@else
						<td>
						<i class="fa fa-check text-success"></i>
						</td>
						@endif
						
						@else
						<td><i class="fa fa-times text-danger"></i></td><td></td>
						@endif
						
						</tr>
						@endforeach
						</tbody>
						</table>
						@endif
						</div>
						</div>
						
						<div class="box box-primary">
						<div class="box-header with-border">
						<h3 class="box-title">Prosentase</h3>
						</div>
						<div class="box-body">
						
						<div class="progress">
						<?php
						$s = $sksd > 0 ? $sksd/$sksw * 100 : 0;
						if($s < 40)
						$class = 'danger';
						elseif($s >=40 AND $s < 75)
						$class = 'warning';	
						elseif($s >= 75 and $s < 100)
						$class="success";
						else
						$class="info";
						?>
						<div class="progress-bar progress-bar-{{ $class }}" role="progressbar" aria-valuenow="{{ $s }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $s }}%">
						<span class="">{{ round($s, 1) }}%</span>
						</div>
						</div>
						
						<table>
						<tr>
						<th>SKS Wajib ditempuh</th><td>:&nbsp;</td><td>{{ $sksw }}</td>
						</tr>
						<tr>
						<th>SKS Sudah ditempuh</th><td><span class="text-success">:&nbsp;</span></td><td><span class="text-success">{{ $sksd }}</span></td>
						</tr>
						<tr>
						<th>Prosentase</th><td>:&nbsp;</td><td>{{ round($s, 1) }}%</td>
						</tr>
						</table>
						</div>
						</div>
						@endsection																																															