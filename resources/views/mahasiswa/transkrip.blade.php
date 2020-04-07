@extends('app')

@section('title')
Transkrip Nilai - {{ $mahasiswa -> nama }}
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
				
				<ul class="sidebar-menu-small">
					<li><h5>AKSI CEPAT</h5></li>
					@include('mahasiswa.partials._menu2', ['role_id' => \Auth::user() -> role_id, 'mahasiswa' => $mahasiswa])
					<!--li><a href="{{ route('mahasiswa.edit', $mahasiswa -> id) }}" class="btn btn-warning btn-xs btn-flat" title="Edit data dosen"><i class="fa fa-pencil-square-o"></i> Edit Data</a>-->
				</ul>
			</div>
		</div>
	</div>
	
	@section('header')
	<section class="content-header">
		<h1>
			Perkuliahan
			<small>Transkrip Nilai</small>
		</h1>		
		<ol class="breadcrumb">
			<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
			<li><a href="{{ route('mahasiswa.show', $mahasiswa -> id) }}"> {{ ucwords(strtolower($mahasiswa -> nama)) }}</a></li>
			<li class="active">Transkrip Nilai</li>
		</ol>
	</section>
	@endsection
	
	
	<div class="col-sm-9">
	
		@include('mahasiswa.partials._data', ['mahasiswa' => $mahasiswa])		
		
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Transkrip Nilai</h3>
				<div class="box-tools">
					<a href="{{ route('mahasiswa.transkrip.print', $mahasiswa -> id) }}" class="btn btn-success btn-xs btn-flat" title="Print Transkrip"><i class="fa fa-print"></i> Cetak Transkrip</a>
				</div>
			</div>
			<div class="box-body">
				@if(!$data->count())
				<p class="text-muted">Belum ada data</p>
				@else
				<?php $c=1; ?>
				<table class="table table-bordered table-striped">
					<thead>
						<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
							<th>No.</th>
							<th>Kode MK</th>
							<th>Nama MK</th>
							<th>SKS</th>
							<th>Semester</th>
							<th>PROGRAM</th>
							<th>Tahun Akademik</th>
						<th>Nilai</th>
						</tr>
						</thead>
						<tbody>
						@foreach($data as $g)
						<tr>
						<td>{{ $c }}</td>
						<td>{{ $g -> kode }}</td>
						<td>{{ $g -> matkul }}</td>
						<td>{{ $g -> sks }}</td>
						<td>{{ $g -> semester }}</td>
						<td>{{ $mahasiswa -> kelas -> nama }}</td>
						<td>{{ $g -> tapel }}</td>
						<td>{{ $g -> nilai }}</td>
						</tr>
						<?php $c++; ?>
						@endforeach
						</tbody>
						</table>
						@endif
						</div>
						</div>
						@endsection																		