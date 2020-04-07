@extends('app')

@section('title')
Cuti Mahasiswa
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
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Cuti</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa/') }}"> Mahasiswa</a></li>
		<li><a href="{{ url('/mahasiswa/' . $mahasiswa -> id) }}"> {{ $mahasiswa -> nama }}</a></li>
		<li class="active">Cuti</li>
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
		
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">Riwayat Cuti Mahasiswa</h3>
				<div class="box-tools">
					<a href="{{ route('mahasiswa.cuti.create', [ $mahasiswa -> id]) }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i> Tambah Cuti</a>
				</div>
			</div>
			<div class="box-body">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>No.</th>
							<th>NIM</th>
							<th>Nama</th>
							<th>Prodi</th>
							<th>Status</th>
							<th>Tahun Akademik</th>
							<th>Tanggal Mulai</th>
							<th>Keterangan</th>
							<th></th>
						</tr>
					</thead>
				<tbody>
				@if(!$data -> count())
				<tr>
				<td colspan="8" align="center">Belum ada data</td>
				</tr>
				@else
				<?php 
				$c = 0;
				$status = [0 => 'Aktif Kuliah', 11 => 'Cuti Resmi', 12 => 'Cuti Tanpa Keterangan'];
				?>
				@foreach($data as $b)
				<?php $c++; ?>
				<tr>
				<td>{{ $c }}</td>
				<td>{{ $b -> NIM }}</td>
				<td>{{ $b -> nama }}</td>
				<td>{{ $b -> prodi}}</td>
				<td>{{ $status[$b -> status] }}</td>
				<td>{{ $b -> ta }}</td>
				<td>{{ $b -> tgl_mulai }}</td>
				<td>{{ $b -> keterangan }}</td>
				<td>
				@if($b -> status > 10)
				<a href="{{ route('mahasiswa.cuti.edit', $b -> id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data"><i class="fa fa-pencil-square-o"></i> Edit</a>
				<a href="{{ route('mahasiswa.cuti.reactivate', $b -> id) }}" class="btn btn-success btn-flat btn-xs" title="Aktifkan Mahasiswa"><i class="fa fa-check"></i> Aktifkan</a>
				@else
				<button class="btn btn-warning btn-flat btn-xs" title="Edit data" disabled><i class="fa fa-pencil-square-o"></i> Edit</button>
				<button class="btn btn-success btn-flat btn-xs" title="Aktifkan Mahasiswa" disabled><i class="fa fa-check"></i> Aktifkan</button>						
				@endif
				<a href="{{ route('mahasiswa.cuti.delete', $b -> id) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Delete"><i class="fa fa-trash"></i> Delete</a>
				</td>
				</tr>
				@endforeach
				@endif
				</tbody>
				</table>
				</div>	
				</div>	
				@endsection																																																						