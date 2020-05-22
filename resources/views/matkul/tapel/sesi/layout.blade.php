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
<div class="row">

	<div class="col-md-3 col-sm-3 col-xs-3">
		<h4><i class="fa fa-align-justify"></i> Menu</h4>
		<div class="f-box">
			<div class="f-box-body">
				<?php
				$menus = [
					'akt' => [
						'label' => 'Aktivitas Pembelajaran',
						'url' => route('matkul.tapel.sesi.index', $id),
						'icon' => 'list'
					],
					'dis' => [
						'label' => 'Diskusi',
						'url' => route('matkul.tapel.diskusi.index', $id),
						'icon' => 'comments'
					],
					'ang' => [
						'label' => 'Anggota',
						'url' => route('matkul.tapel.anggota.index', $id),
						'icon' => 'users'
					],
					'lap' => [
						'label' => 'Laporan',
						'url' => route('matkul.tapel.laporan.index', $id),
						'icon' => 'area-chart'
					]
				];
				?>
				@foreach($menus as $k => $v)
				@if($active == $k)
				<a href="#" onclick="return false;" class="btn btn-block btn-social btn-success btn-flat">
					@else
					<a href="{{ $v['url'] }}" class="btn btn-block btn-social-inactive">
						@endif
						<i class="fa fa-{{ $v['icon'] }}"></i> {{ $v['label'] }}
					</a>
					@endforeach

			</div>
		</div>

		@if(isset($sesi_side) and $sesi_side -> count())
		<h4>Sesi Pembelajaran</h4>
		<div class="f-box">
			<div class="f-box-body">
				@foreach($sesi_side as $s)
				<div class="kelas-detail">
					<div class="text-muted">Sesi ke {{ $s -> sesi_ke }}</div>
					<h4 style="margin:0px;">
						<a href="{{ route('matkul.tapel.sesi.kegiatan.index', [$kelas -> id, $s -> id]) }}">{{ $s -> judul }}</a>
					</h4>
					<div class="text-muted">{{ $s -> kegiatan -> count() }} Materi</div>
				</div>
				@endforeach
			</div>
		</div>
		@endif

	</div>

	<div class="col-md-6 col-sm-6 col-xs-6">
		@yield('tengah')
	</div>
	
	<div class="col-md-3 col-sm-3 col-xs-3">
		<h4><i class="fa fa-info"></i> Informasi Kelas Kuliah</h4>
		<div class="f-box">
			<div class="f-box-body" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
				<div class="kelas-logo">
					{{ $kelas -> kurikulum -> matkul -> singkatan }}
				</div>
				<h4>{{ $kelas -> kurikulum -> matkul -> nama }}</h4>
				<p>KELAS: {{ $kelas -> kurikulum -> semester }} {{ $kelas -> kelas2 }}</p>
				<div class="kelas-detail">
					<div class="text-muted">Dosen pengajar</div>
					@foreach($kelas -> tim_dosen as $d)
					<div class="kelas-detail-ket">
						<i class="fa fa-graduation-cap"></i> {{ $d -> gelar_depan }} {{ $d -> nama }} {{ $d -> gelar_belakang }}
					</div>
					@endforeach
				</div>
				<div class="kelas-detail">
					<div class="text-muted">Periode Akademik</div>
					<div class="kelas-detail-ket">
						{{ $kelas -> tapel -> nama }}
					</div>
				</div>
				<div class="kelas-detail">
					<div class="text-muted">Nama Kelas</div>
					<div class="kelas-detail-ket">
						{{ $kelas -> kurikulum -> semester }} {{ $kelas -> kelas2 }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('styles')
<style>
	.f-box {
		background-color: #ffffff;
		margin-bottom: 15px;
		box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
		border-radius: 3px;
	}

	.f-box-body {
		padding: 10px;
	}

	.f-box-body h4 {
		font-weight: bold;
	}

	.f-box-row {
		display: flex;
		align-items: flex-start;
	}

	.box-title-half {
		margin-top: 0;
		color: #ffffff;
		background-color: #00a65a;
		padding: 10px;
		width: 120px;
		border-radius: 0 0 40px 0;
	}

	.kelas-logo {
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

	.kelas-detail {
		border: 1px solid #eee;
		padding: 5px;
		width: 100%;
		margin: 3px 0;
	}

	.kelas-detail-ket {
		font-size: 16px;
	}

	a.btn-social-inactive {
		color: black;
	}

	a.btn-social-inactive:hover {
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

	time {
		font-size: 85%;
	}

	/* @media (min-width: 768px) {
    .pull-right-md {
        float: right !important;
    } */
	}
</style>
@endpush

@push('scripts')
<script>
	function notImplemented() {
		alert('Maaf, Bagian ini masih dalam proses pengembangan.');
	}
</script>
@endpush