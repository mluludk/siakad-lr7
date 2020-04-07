@extends('app')

@section('title')
Prestasi Mahasiswa
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
	<small>Prestasi</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		@if($auth -> role_id != 512)
		<li><a href="{{ url('/mahasiswa/') }}"> Mahasiswa</a></li>
		@endif
		<li><a href="{{ url('/mahasiswa/' . $mahasiswa -> id) }}"> {{ $mahasiswa -> nama }}</a></li>
		<li class="active">Prestasi</li>
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
					@include('mahasiswa.partials._menu2', ['role_id' => \Auth::user() -> role_id, 'mahasiswa' => $mahasiswa])
				</ul>
				@endif
				
			</div>
		</div>
	</div>
	<div class="col-sm-9">
		
		@include('mahasiswa.partials._data', ['mahasiswa' => $mahasiswa])		
		
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">Riwayat Prestasi Mahasiswa</h3>
				<div class="box-tools">
				<a href="{{ route('mahasiswa.prestasi.create', [ $mahasiswa -> id]) }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i> Tambah Prestasi</a>
				</div>
				</div>
				<div class="box-body">
				<table class="table table-bordered table-striped">
				<thead>
				<tr>
				<th>No.</th>
				<th>Bidang Prestasi</th>
				<th>Tingkat Prestasi</th>
				<th>Nama Prestasi</th>
				<th>Tahun</th>
				<th>Penyelenggara</th>
				<th>Peringkat</th>
				<th></th>
				</tr>
				</thead>
				<tbody>
				@if(!$prestasi->count())
				<tr>
				<td colspan="9" align="center">Data tidak ditemukan</td>
				</tr>
				@else
				<?php 
				$c=1; 
				$jenis = config('custom.pilihan.dikti.jenis_prestasi');
				$tingkat = config('custom.pilihan.dikti.tingkat_prestasi');
				?>
				@foreach($prestasi as $b)
				<tr>
				<td>{{ $c }}</td>
				<td>{{ $jenis[$b -> jenis] }}</td>
				<td>{{ $tingkat[$b -> tingkat] }}</td>
				<td>{{ $b -> nama }}</td>
				<td>{{ $b -> tahun }}</td>
				<td>{{ $b -> penyelenggara }}</td>
				<td>{{ $b -> peringkat }}</td>
				<td>
				<a href="{{ route('mahasiswa.prestasi.edit', [$b -> mahasiswa_id, $b -> id]) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data prestasi"><i class="fa fa-pencil-square-o"></i> Edit</a>
				<a href="{{ route('mahasiswa.prestasi.delete', [$b -> mahasiswa_id, $b -> id]) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Hapus data prestasi"><i class="fa fa-trash"></i> Hapus</a>
				</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
				</tbody>
				</table>
				</div>	
				</div>	
				</div>	
				</div>	
				@endsection																																																								