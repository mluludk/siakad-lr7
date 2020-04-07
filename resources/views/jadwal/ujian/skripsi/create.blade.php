@extends('app')

@section('title')
Tambah Jadwal Pendaftaran Ujian {{ ucfirst($j) }} Skripsi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Jadwal Pendaftaran Ujian {{ ucfirst($j) }} Skripsi
		<small>Tambah Jadwal Baru</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('jadwal.ujian.skripsi.index', $j) }}"> Jadwal Pendaftaran Ujian {{ ucfirst($j) }} Skripsi</a></li>
		<li class="active">Jadwal Baru</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Jadwal Pendaftaran Ujian {{ ucfirst($j) }} Skripsi</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\JadwalUjianSkripsi, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['jadwal.ujian.skripsi.store', $j]]) !!}
				@include('/jadwal/ujian/skripsi/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection