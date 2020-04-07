@extends('app')

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

@section('title')
Daftar Buku Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Buku Dosen
		<small>Daftar</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Buku Dosen</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Dosen - {{ $dosen->NIDN }} / {{ ucwords(strtolower($dosen -> nama)) }}</h3>
		<div class="box-tool pull-right">
			<a href="{{ route('dosen.edit', $dosen -> id) }}" class="btn btn-warning btn-xs btn-flat" title="Edit data dosen"><i class="fa fa-pencil-square-o"></i> Edit Data</a>
	</div>
	<div class="box-body" style="padding-left: 0px;">
		<div class="col-sm-3">
			<div id="preview">
				<img src="@if(isset($dosen->foto) and $dosen->foto != '')/getimage/{{ $dosen->foto }} @else/images/b.png @endif"></img>
			</div>
			<div class="status">
				@if($dosen -> statusDosen == 1)
				<span class="label label-success">{{ config('custom.pilihan.statusKepegawaian')[$dosen -> statusKepegawaian] }}</span>
				@else
				<span class="label label-default">{{ config('custom.pilihan.statusKepegawaian')[$dosen -> statusKepegawaian] }}</span>
				@endif
			</div>
	
	<ul class="sidebar-menu-small">
			<li><h5>AKSI CEPAT</h5></li>
			@include('dosen.partials._menu', ['role_id' => \Auth::user() -> role_id, 'dosen' => $dosen])
			<!--a href="{{ route('dosen.edit', $dosen -> id) }}" class="btn btn-warning btn-xs btn-flat" title="Edit data dosen"><i class="fa fa-pencil-square-o"></i> Edit Data</a>-->
			</ul>
		</div>
@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<div class="box-tool pull-right">
			<!--@include('dosen.partials._menu', ['dosen' => $dosen])-->
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-xs-12">
				<table width="100%">
					<tbody>
						<tr>
							<th width="20%">Nama</th><th width="2%">:</th><td width="30%">{{ $dosen -> nama }}</td>
							<th width="20%"></th><th width="2%"></th><td></td>
						</tr>
						<tr>
							<th>Tempat Lahir</th><th>:</th><td>{{ $dosen -> tmpLahir }}</td>
							<th>Tanggal Lahir</th><th>:</th><td>{{ $dosen -> tglLahir }}</td>
						</tr>
						<tr>
							<th>Jenis Kelamin</th><th>:</th><td>@if($dosen -> jenisKelamin == 'L') Laki-laki @else Perempuan @endif</td>
							<th>Agama</th><th>:</th><td>{{ config('custom.pilihan.agama')[$dosen -> agama] }}</td>
						</tr>
						<tr>
							<th>Status</th><th>:</th><td>{{ config('custom.pilihan.statusKepegawaian')[$dosen -> statusKepegawaian] }}</td>
							<th>NIDN</th><th>:</th><td>{{ $dosen -> NIDN }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>		
	</div>
</div>
<style>
.table>thead>tr>th {
    text-align: center;
    vertical-align: middle;
}
</style>
<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Riwayat Buku Dosen</h3>
		<div class="box-tools">
			<a href="{{ route('dosen.buku.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data Buku Dosen"><i class="fa fa-plus"></i> Tambah Buku Dosen</a>
		</div>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No.</th>
					<th>Nama</th>
					<th>Judul Buku</th>
					<th>Klasifikasi</th>
					<th>Penerbit</th>
					<th>ISBN</th>
					<th>Tahun Terbit</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($buku as $b)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $b -> dosen }}</td>
					<td>{{ $b -> judul }}</td>
					<td>@if($b -> klasifikasi == 1) Buku Referensi @else Buku Monograf @endif</td>
					<td>{{ $b -> penerbit }}</td>
					<td>{{ $b -> isbn }}</td>
					<td>{{ $b -> tahun_terbit }}</td>
					<td>
						<a href="{{ route('dosen.buku.edit', $b -> buku_id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data buku"><i class="fa fa-pencil-square-o"></i> Edit</a>
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>	
</div>	