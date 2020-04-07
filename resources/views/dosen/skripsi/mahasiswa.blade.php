@extends('app')

@section('title')
Mahasiswa Bimbingan Dosen  | {{ $dosen -> nama }}
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
	
/* 	.table td, th{
	border-top-width: 0px !important;
	} */
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Mahasiswa Bimbingan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen/') }}"> Dosen</a></li>
		<li><a href="{{ url('/dosen/' . $dosen -> id) }}"> {{ $dosen -> nama }}</a></li>
		<li class="active">Mahasiswa Bimbingan</li>
	</ol>
</section>
@endsection

@push('scripts')
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({
			no_results_text: "Tidak ditemukan hasil pencarian untuk: ",
			placeholder_text_single: "Pilih program studi terlebih dahulu"
		});
	});  
</script>
@endpush
@push('styles')
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<style>
	.chosen-container{
	font-size: inherit;
	}
	.chosen-single{
	padding: 6px 10px !important;
	box-shadow: none !important;
    border-color: #d2d6de !important;
	background: white !important;
	height: 34px !important;
	border-radius: 0px !important;
	}
	.chosen-drop{
    border-color: #d2d6de !important;	
	box-shadow: none;
	}
</style>
@endpush

@section('content')

<div class="row">
	<div class="col-sm-3">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Nama : {{ $dosen -> NIY }} / {{ ucwords(strtolower($dosen -> nama)) }}</h3>
			</div>
			<div class="box-body" style="padding-left: 0px;">
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
		</div>
	</div>
		
	<div class="col-sm-9">
		<div class="box box-primary">
			<div class="box-header with-border">	
				<div class="box-tool pull-right">
			</div>
		</div>
			<div class="box-body">
				<div class="row">
					<div class="col-xs-12">
						<table width="100%">
							<tbody>
								<tr><th width="20%">NAMA</th><td width="30%">: {{ $dosen->nama }}</td><th width="20%">NAMA IBU</th><td>: {{ $dosen->nama_ibu }}</td></tr>
								<tr><th>TEMPAT LAHIR</th><td>: {{ $dosen->tmpLahir }}</td><th>TANGGAL LAHIR</th><td>: {{ $dosen->tglLahir }}</td></tr>
								<tr><th>JENIS KELAMIN</th><td>: {{ config('custom.pilihan.jenisKelamin')[$dosen -> jenisKelamin] }}</td><th>AGAMA</th><td>: {{ config('custom.pilihan.agama')[$dosen->agama] }}</td></tr>
								<tr><th>STATUS AKTIF</th><td>: {{ config('custom.pilihan.statusKepegawaian')[$dosen -> statusKepegawaian] }}</td><th>NIDN / NUP / NIDK</th><td>: {{ $dosen->NIDN }}</td></tr>
							</tbody>
						</table>
					</div>
				</div>		
			</div>
		</div>
		
<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Mahasiswa Bimbingan</h3>
	</div>
	<div class="box-body">
		<?php $c = 1; ?>
		<table class="table table-bordered table-hover">
		<thead>
			<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
				<th>No</th>
				<th>NIM</th>
				<th>Mahasiswa</th>
				<th>Prodi</th>
				<th>Program</th>
				<th>Judul</th>
				<th>Tanggal Awal Bimbingan</th>
				<th>Tanggal Akhir Bimbingan</th>
				<th></th>
			</tr>
			</thead>
			<tbody>
			@if(!count($data))
			<td colspan="8" align="center">Belum ada data</td>
			@else
			@foreach($data as $mk)
			<tr>
			<td>{{ $c }}</td>
			<td>{{ $mk -> NIM }}</td>
			<td>{{ $mk -> mhs }}</td>
			<td>{{ $mk -> strata }} {{ $mk -> prodi }}</td>
			<td>{{ $mk -> program }}</td>
			<td>{{ $mk -> judul }}</td>
			<td>{{ $mk -> tglAwal }}</td>
			<td>{{ $mk -> tglAkhir }}</td>
			<td>
			<a href="{{ route('dosen.skripsi.mahasiswa.delete', [$dosen -> id, $mk -> skripsi_id]) }}" title="Hapus data Mahasiswa" class="btn btn-danger btn-xs btn-flat has-confirmation"><i class="fa fa-trash"></i></a>
			</td>
			</tr>
			<?php $c++; ?>
			@endforeach
			@endif
			</tbody>
			</table>
			</div>
			</div>
			@endsection																											