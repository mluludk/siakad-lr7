@extends('app')

@section('title')
Status Tagihan Mahasiswa
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
		<small>Status Tagihan Mahasiswa</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Status Tagihan Mahasiswa</li>
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
				<table class="table table-striped table-bordered">
					<thead>
						<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
							<th>No.</th>
							<th>Jenis Tagihan</th>
							<th>Nominal</th>
							<th>Lunas</th>
							<th>Semester</th>
							<th>Tahun Akademik</th>
							<th>Keterangan</th>
							<th>Token Pembayaran</th>
						</tr>
					</thead>
					<tbody>
						@if(!isset($tagihan))
						<tr>
							<td colspan="8" align="center">Belum ada data</td>
						</tr>
						@else
						<?php $c = 1; ?>						
						@if(isset($tagihan['her']))
						@foreach($tagihan['her'] as $t => $d)
						<?php 
							$persen = (int)$d['jumlah'] == 0 ? 0 : round((int)$d['bayar'] / (int)$d['jumlah'] * 100, 1);
						?>	
						<tr>
							<td>{{ $c }}</td>
							<td>Her registrasi</td>
							<td>{{ formatRupiah($d['jumlah'] - $d['bayar']) }}</td>
							<td>{{ $persen }}%</td>
							<td>{{ hitungSemester($mahasiswa -> tapelMasuk, $t) }}</td>
							<td>{{ $t }}</td>
							<td>Belum lunas</td>
							<td><a href="{{ route('mahasiswa.tagihan.token', $d['id']) }}" class="btn btn-primary btn-flat btn-xs"><i class="fa fa-credit-card"></i> Bayar</a></td>
						</tr>
						<?php 
							$c++; 
						?>
						@endforeach
						@endif
						
						@if(isset($tagihan['spp']))
						@foreach($tagihan['spp'] as $t => $d)
						@foreach($d as $e)
						<?php 
							$persen = (int)$e['jumlah'] == 0 ? 0 : round((int)$e['bayar'] / (int)$e['jumlah'] * 100, 1);
						?>	
						<tr>
							<td>{{ $c }}</td>
							<td>{{ $e['nama'] }}</td>
							<td>{{ formatRupiah($e['jumlah'] - $e['bayar']) }}</td>
							<td>{{ $persen }}%</td>
							<td colspan="2">
								@if(isset($e['tgl_cicilan_awal']))
								<span class="text-danger">Tanggal Bayar</span><br/>
								{{ $e['tgl_cicilan_awal'] }} - {{ $e['tgl_cicilan_akhir'] }}
								@endif
							</td>
							<td>Belum lunas</td>
							<td><a href="{{ route('mahasiswa.tagihan.token', $e['id']) }}" class="btn btn-primary btn-flat btn-xs"><i class="fa fa-credit-card"></i> Bayar</a></td>
						</tr>
						<?php 
							$c++; 
						?>
						@endforeach
						@endforeach
						@endif
						
						@if(isset($tagihan['bamk']))
						<?php 
							$persen = (int)$tagihan['bamk']['jumlah'] == 0 ? 0 : round((int)$tagihan['bamk']['bayar'] / (int)$tagihan['bamk']['jumlah'] * 100, 1);
						?>	
						<tr>
							<td>{{ $c }}</td>
							<td>BAMK</td>
							<td>{{ formatRupiah($tagihan['bamk']['jumlah'] - $tagihan['bamk']['bayar']) }}</td>
							<td>{{ $persen }}%</td>
							<td></td>
							<td></td>
							<td>
								<span class="text-danger">Sisa Tanggungan</span><br/>
								{{ formatRupiah($tagihan['bamk']['jumlah'] - $tagihan['bamk']['bayar']) }}
							</td>
							<td><a href="{{ route('mahasiswa.tagihan.token', [$tagihan['bamk']['id'], 'bamk']) }}" class="btn btn-primary btn-flat btn-xs"><i class="fa fa-credit-card"></i> Bayar</a></td>
						</tr>
						<?php 
							$c++; 
						?>
						@endif
						
						@if(isset($tagihan['bamp']))
						<?php 
							$persen = (int)$tagihan['bamp']['jumlah'] == 0 ? 0 : round((int)$tagihan['bamp']['bayar'] / (int)$tagihan['bamp']['jumlah'] * 100, 1);
						?>	
						<tr>
							<td>{{ $c }}</td>
							<td>BAMP</td>
							<td>{{ formatRupiah($tagihan['bamp']['jumlah'] - $tagihan['bamp']['bayar']) }}</td>
							<td>{{ $persen }}%</td>
							<td></td>
							<td></td>
							<td>
								<span class="text-danger">Sisa Tanggungan</span><br/>
								{{ formatRupiah($tagihan['bamp']['jumlah'] - $tagihan['bamp']['bayar']) }}
							</td>
							<td><a href="{{ route('mahasiswa.tagihan.token', [$tagihan['bamp']['id'], 'bamp']) }}" class="btn btn-primary btn-flat btn-xs"><i class="fa fa-credit-card"></i> Bayar</a></td>
						</tr>
						<?php 
							$c++; 
						?>
						@endif
						
						@if(isset($tagihan['kel']))
						@foreach($tagihan['kel'] as $t)
						<?php 
							$persen = (int)$t['jumlah'] == 0 ? 0 : round((int)$t['bayar'] / (int)$t['jumlah'] * 100, 1);
						?>	
						<tr>
							<td>{{ $c }}</td>
							<td>{{ $t['nama'] }}</td>
							<td>{{ formatRupiah($t['jumlah'] - $t['bayar']) }}</td>
							<td>{{ $persen }}%</td>
							<td></td>
							<td></td>
							<td>Belum lunas</td>
							<td><a href="{{ route('mahasiswa.tagihan.token', $t['id']) }}" class="btn btn-primary btn-flat btn-xs"><i class="fa fa-credit-card"></i> Bayar</a></td>
						</tr>
						<?php 
							$c++; 
						?>
						@endforeach
						@endif
						
					</tbody>
					@endif
				</table>
			</div>
		</div>
	</div>
</div>
@endsection																																																																																																																		