@extends('app')

@section('title')
Status Pembayaran
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

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Status Pembayaran</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Status Pembayaran</li>
	</ol>
</section>
@endsection

@push('scripts')
@if(isset($status))
<script>
 	$(document).ready(function()
		{
		var st = $('#status').detach();
		st.insertBefore('#tanggungan');
	});
</script>
@endif
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
				<?php
					$auth = \Auth::user();
				?>
				@if($auth -> role_id != 512)
				<ul class="sidebar-menu-small">
					<li><h5>AKSI CEPAT</h5></li>
					@include('mahasiswa.partials._menu2', ['role_id' => $auth -> role_id, 'mahasiswa' => $mahasiswa])
				</ul>
				@endif
				
			</div>
		</div>
	</div>
	<div class="col-sm-9">
		
		@include('mahasiswa.partials._data', ['mahasiswa' => $mahasiswa])		
		
		<div class="box box-danger" id="tanggungan">
			<div class="box-header with-border">
				<h3 class="box-title">Detail Status Pembayaran</h3>
			</div>
			<div class="box-body">
				<?php 
					$c = 1; 
					$tanggungan = $dibayar = $sisa = $tp = 0;
				?>
				<table class="table table-striped table-bordered">
					<thead>
						<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
							<th>No.</th>
							<th>Jenis Pembayaran</th>
							<th>Harus dibayar</th>
							<th>Sudah dibayar</th>
							<th>Sisa</th>
							<th>Prosentase</th>
							<th>Keterangan</th>
						</tr>
					</thead>
					<tbody>
						@if(!isset($status))
						<tr>
							<td colspan="7" align="center">Belum ada data</td>
						</tr>
						@else
						@foreach($status as $g => $s)
						<?php 
							$prosentase = (int)$s['tg'] == 0 ? 0 : (int)$s['db'] / (int)$s['tg'] * 100; 
						?>
						<tr>
							<td>{{ $c }}</td>
							<td>{{ $golongan[$g] }}</td>
							<td>Rp {{ number_format($s['tg'], 0, ',', '.') }}</td>
							<td>Rp {{ number_format($s['db'], 0, ',', '.') }}</td>
							<td>Rp {{ number_format($s['ss'], 0, ',', '.') }}</td>
							<td>{{ round($prosentase, 1) }}%</td>
							<td>@if($s['ss'] > 0)Tunggakan Rp {{ number_format($s['ss'], 0, ',', '.') }}@else<span>Lunas</span>@endif</td>
						</tr>
						<?php 
							$c++; 
							$tanggungan += $s['tg'];
							$dibayar += $s['db'];
							$sisa += $s['ss'];
						?>
						@endforeach
						<tr>
							<th colspan="2" style="text-align:right">Total</th>
							<th>Rp {{ number_format($tanggungan, 0, ',', '.') }}</th>
							<th>Rp {{ number_format($dibayar, 0, ',', '.') }}</th>
							<th colspan="3">Rp {{ number_format($sisa, 0, ',', '.') }}</th>
						</tr>
					</tbody>
					@endif
				</table>
			</div>
		</div>
	</div>
</div>
@if(isset($status))
<div class="box box-info" id="status">
	<div class="box-header with-border">
		<h3 class="box-title">Status Pembayaran</h3>
	</div>
	<div class="box-body">
		<div class="progress">
			<?php
				$tp = $dibayar / $tanggungan * 100;
				if($tp > 100) $tp = 100; // max 100%
				if($tp < 40)
				$class = 'danger';
				elseif($tp >=40 AND $tp < 75)
				$class = 'warning';	
				elseif($tp >= 75 and $tp < 100)
				$class="success";
				else
				$class="info";
			?>
			<div class="progress-bar progress-bar-{{ $class }}" role="progressbar" aria-valuenow="{{ $tp }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $tp }}%">
				<span class="">{{ round($tp, 1) }}%</span>
			</div>
		</div>
	</div>			
</div>
@endif
@endsection																																																																																																																		