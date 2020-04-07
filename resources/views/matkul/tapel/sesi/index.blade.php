@extends('app')

@section('title')
Data Kelas Kuliah {{ $kelas -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Kelas Kuliah
		<small>Informasi</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/matkul/tapel') }}"> Kelas Kuliah</a></li>
		<li class="active">{{ $kelas -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="f-row">
	
	@include('matkul.tapel.sesi.menu', ['active' => 'akt', 'id' => $kelas -> id])
	
	<div class="f-col-2">
		<div class="f-box-row">
			<div class="f-box-side" style="flex-grow:3;">
				<h4><i class="fa fa-list"></i> Sesi Pembelajaran</h4>
			</div>
			<div class="f-box-side" style="flex-grow:1;  text-align: right; padding-top: 11px;">
				<a href="{{ route('matkul.tapel.sesi.create', $kelas -> id) }}" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus"></i> Tambah</a>&nbsp;&nbsp;
				<a href="#" class="btn btn-info btn-xs btn-flat"><i class="fa fa-cog"></i> Pengaturan</a>
			</div>
		</div>		
		@if($kelas -> sesi -> count())
		@php
		$hari = config('custom.hari');
		@endphp
		@foreach($sesi as $s)
		<div class="f-box">
			<div class="f-box-row">
				<div class="f-box-side" style="width: 58px; padding: 10px;">
					<button class="btn btn-default"><i class="fa fa-navicon"></i></button>
				</div>
				<div class="f-box-side" style="width: 100%; border-left: 1px solid #ddd;">
					<h4 class="box-title-half">Sesi ke {{ $s -> sesi_ke }}</h4>
					<div class="f-box-body">
						<h4><a href="{{ route('matkul.tapel.sesi.materi.index', [$kelas -> id, $s -> id]) }}">{{ $s -> judul }}</a></h4>
						@foreach($kelas -> jadwal as $j)
						<div class="text-muted">{{ $hari[$j -> hari] }}, 06 April 2020</div>
						<span class="text-muted">
							{{ $j -> jam_mulai }} - {{ $j -> jam_selesai }} &nbsp;&nbsp;
							<i class="fa fa-building"></i>&nbsp;&nbsp;{{ $j -> ruang -> nama }}
						</span>
						@endforeach
					</div>
				</div>
				<div class="f-box-side" style="width: 58px; padding: 10px;">
					<div class="dropdown">
						<button class="btn btn-default dropdown-toggle" type="button" id="dm1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<i class="fa fa-ellipsis-v"></i>
						</button>
						<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dm1">
							<li><a href="{{ route('matkul.tapel.sesi.materi.index', [$kelas -> id, $s -> id]) }}"><i class="fa fa-bookmark-o"></i> Materi</a></li>
							<li><a href="{{ route('matkul.tapel.sesi.edit', [$kelas -> id, $s -> id]) }}"><i class="fa fa-edit"></i> Ubah</a></li>
							<li><a href="{{ route('matkul.tapel.sesi.duplicate', [$kelas -> id, $s -> id]) }}" onclick="notImplemented();return false;"><i class="fa fa-paste"></i> Duplikat Sesi</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="{{ route('matkul.tapel.sesi.delete', [$kelas -> id, $s -> id]) }}" class="has-confirmation"><span class="text-danger"><i class="fa fa-trash"></i> Hapus</span></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		@endforeach
		@else
		<div class="callout callout-info">
			<h4>Informasi</h4>
			<p>Belum ada Sesi Pembelajaran</p>
		</div>
		@endif
	</div>
	
	@include('matkul.tapel.sesi.informasi', ['kelas' => $kelas])
	
</div>
@endsection

@push('styles')
<style>
	.f-row{
	margin: 0 -15px;
	display: flex;
	flex-direction: row;
	align-content: space-between;
	flex-wrap: wrap;
	}
	.f-col-1{
	flex-grow: 1;
	margin: 15px;
	}
	.f-col-2{
	flex-grow: 2;
	flex-basis: 40%;
	margin: 15px;
	}
	
	@media all and (max-width: 700px) {
	.f-row{
	flex-direction: column;
	align-content: stretch;
	}
	}
	
	.f-box{
	background-color: #ffffff;	
	margin-bottom: 15px;
	}
	
	.f-box-body{
	padding: 10px;
	}
	
	.f-box-body h4{
	font-weight: bold;
	}
	
	.f-box-row{
	display: flex;
	align-items: flex-start;
	}
	
	.box-title-half{
	margin-top: 0;
	color: #ffffff;
	background-color: #00a65a;
	padding: 10px;
	width: 120px;
	border-radius: 0 0 40px 0;
	}
	
	.kelas-logo{
	width: 100px;
	height: 100px;
	background-color: #e1e1e1;
	font-size: 50px;
	line-height: 50px;
	text-align: center;
	color: #000000;
	font-weight: bold;
	padding: 25px 0;
	}
	
	.kelas-detail{
	border: 1px solid #eee;
	padding: 5px;
	width: 100%;
	margin: 3px 0;
	}
	.kelas-detail-ket{
	font-size: 16px;
	}
	
	a.btn-social-inactive{
	color: black;
	}
	a.btn-social-inactive:hover{
	color: #337ab7;
	}
	.btn-social-inactive {
	position: relative;
	padding-left: 44px;
	text-align: left;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	}
	
	.btn-social-inactive>:first-child {
	position: absolute;
	left: 0;
	top: 0;
	bottom: 0;
	width: 32px;
	line-height: 34px;
	font-size: 1.6em;
	text-align: center;
	}
</style>
@endpush		

@push('scripts')
<script>
	function notImplemented(){
		alert('Maaf, Bagian ini masih dalam proses pengembangan.');
	}
</script>
@endpush										