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
	
	.table > thead > tr > th {
	text-align: center;
	vertical-align: middle;
	}
</style>
@endpush

@section('title')
Daftar Buku Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Buku Mahasiswa
		<small>Daftar</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		@if($auth -> role_id != 512)
		<li><a href="{{ url('/mahasiswa/') }}"> Mahasiswa</a></li>
		@endif
		<li><a href="{{ url('/mahasiswa/' . $mahasiswa -> id) }}"> {{ $mahasiswa -> nama }}</a></li>
		<li class="active">Buku Mahasiswa</li>
	</ol>
</section>
@endsection

@section('content')
<div class="row">
	<div class="col-sm-3">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Mahasiswa - {{ $mahasiswa -> NIM }} / {{ ucwords(strtolower($mahasiswa -> nama)) }}</h3>
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
		<div class="box box-info">
			<div class="box-body">
				<div class="row">
					<div class="col-xs-12">
						<table width="100%">
							<tbody>
								<tr>
									<th width="20%">Nama</th><th width="2%">:</th><td width="30%">{{ $mahasiswa -> nama }}</td>
									<th width="20%"></th><th width="2%"></th><td></td>
								</tr>
								<tr>
									<th>Tempat Lahir</th><th>:</th><td>{{ $mahasiswa -> tmpLahir }}</td>
									<th>Tanggal Lahir</th><th>:</th><td>{{ $mahasiswa -> tglLahir }}</td>
								</tr>
								<tr>
									<th>Jenis Kelamin</th><th>:</th><td>@if($mahasiswa -> jenisKelamin == 'L') Laki-laki @else Perempuan @endif</td>
									<th>Agama</th><th>:</th><td>{{ config('custom.pilihan.agama')[$mahasiswa -> agama] }}</td>
								</tr>
								<tr>
									<th>Status</th><th>:</th><td>{{ config('custom.pilihan.statusMhs')[$mahasiswa -> statusMhs] }}</td>
									<th>NIM</th><th>:</th><td>{{ $mahasiswa -> NIM }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>		
			</div>
		</div>
		
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">Riwayat Buku Mahasiswa</h3>
				<div class="box-tools">
					<a href="{{ route('mahasiswa.buku.create', $mahasiswa -> id) }}" class="btn btn-primary btn-xs btn-flat" title="Input Data Buku Mahasiswa"><i class="fa fa-plus"></i> Tambah Buku</a>
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
						@if(!$buku -> count())
						<tr>
							<td colspan="8">Belum ada data</td>
						</tr>
						@else
						<?php $c = 1; ?>
						@foreach($buku as $b)
						<tr>
							<td>{{ $c }}</td>
							<td>{{ $b -> mahasiswa }}</td>
							<td>{{ $b -> judul }}</td>
							<td>{{ $klasifikasi[$b -> klasifikasi] }}</td>
							<td>{{ $b -> penerbit }}</td>
							<td>{{ $b -> isbn }}</td>
							<td>{{ $b -> tahun_terbit }}</td>
							<td>
								<a href="{{ route('mahasiswa.buku.edit', $b -> buku_id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data buku"><i class="fa fa-pencil-square-o"></i> Edit</a>
								<a href="{{ route('mahasiswa.buku.delete', $b -> buku_id) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Hapus data buku"><i class="fa fa-trash"></i> Hapus</a>
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
