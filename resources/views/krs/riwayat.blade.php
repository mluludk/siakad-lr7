@extends('app')

@section('title')
Riwayat Kartu Rencana Studi Mahasiswa
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
		<small>Riwayat Kartu Rencana Studi</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li><a href="{{ route('mahasiswa.show', $mhs -> id) }}"> {{ ucwords(strtolower($mhs -> nama)) }}</a></li>
		<li class="active">Riwayat Kartu Rencana Studi</li>
	</ol>
</section>
@endsection

@section('content')
<div class="row">
	<div class="col-sm-3">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Nama : {{ $mhs -> NIM }} / {{ ucwords(strtolower($mhs -> nama)) }}</h3>
			</div>
			<div class="box-body" style="padding-left: 0px;">
				<div id="preview">
					<img src="@if(isset($mhs->foto) and $mhs->foto != '')/getimage/{{ $mhs->foto }} @else/images/b.png @endif"></img>
				</div>
				<div class="status">
					@if($mhs -> statusMhs == 1)
					<span class="label label-success">{{ config('custom.pilihan.statusMhs')[$mhs -> statusMhs] }}</span>
					@else
					<span class="label label-default">{{ config('custom.pilihan.statusMhs')[$mhs -> statusMhs] }}</span>
					@endif
				</div>
				
				<ul class="sidebar-menu-small">
					<li><h5>AKSI CEPAT</h5></li>
					@include('mahasiswa.partials._menu2', ['role_id' => \Auth::user() -> role_id, 'mahasiswa' => $mhs])
				</ul>
			</div>
		</div>
	</div>
	
	<div class="col-sm-9">
		@include('mahasiswa.partials._data', ['mahasiswa' => $mhs])		
		
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Riwayat Kartu Rencana Studi</h3>
			</div>
			<div class="box-body">
				@if(!count($krs))
				<p class="text-muted">Belum ada data</p>
				@else
				<?php $c=1; ?>
				<table class="table table-bordered">
					<thead>
						<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
							<th width="20px">No</th>
							<th>Kode</th>
							<th>Mata Kuliah</th>
							<th>Semester</th>
							<th>Oleh</th>
						</tr>
					</thead>
					<tbody>
						@foreach($krs as $g)
						<tr>
							<td>{{ $c }}</td>
							<td>{{ $g -> kode }}</td>
							<td>{{ $g -> nama }}</td>
							<td>{{ $g -> semester }}</td>
							<td>@if(isset($g -> krs_id))<span class="label label-success">Mandiri</span>@else<span class="label label-danger">Admin</span>@endif</td>
						</tr>
						<?php $c++; ?>
						@endforeach
					</tbody>
				</table>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection																						