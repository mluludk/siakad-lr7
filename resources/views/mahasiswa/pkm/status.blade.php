@extends('app')

@section('title')
Status PKM Mahasiswa
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
		Mahasiswa
		<small>Status PKM</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa/') }}"> Mahasiswa</a></li>
		<li><a href="{{ url('/mahasiswa/' . $mahasiswa -> id) }}"> {{ $mahasiswa -> nama }}</a></li>
		<li class="active">PKM</li>
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
		
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">Status Pendaftaran PKM</h3>
			</div>
			<div class="box-body">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Tahun</th>
							<th>Tanggal</th>
							<th>Tempat</th>
							<th>SK</th>
							<th>Tanggal SK</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						@if(!$status)
						<tr>
							<td colspan="6" align="center"><span class="text-danger"><strong>BELUM TERDAFTAR</strong></span></td>
						</tr>
						@else
						<tr>
							<td>{{ $status -> nama_tapel ?? '-' }}</td>
							<td>{{ $status -> tanggal_mulai ?? '' }} - {{ $status -> tanggal_selesai ?? '' }}</td>
							<td>{{ $status -> lokasi_pkm ?? '-' }}</td>
							<td>{{ $status -> sk ?? '-' }}</td>
							<td>{{ $status -> tanggal_sk ?? '-' }}</td>
							<td>
								<span class="text-success"><strong>TERDAFTAR</strong></span>
							</td>
						</tr>
						@endif
					</tbody>
				</table>
			</div>	
		</div>	
	</div>	
</div>	
@endsection																																																				