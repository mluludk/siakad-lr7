@extends('app')

@section('title')
Pengajuan Judul Skripsi Baru
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pengajuan Judul Skripsi
		<small>Input Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('mahasiswa.skripsi.pengajuan.index') }}"> Pengajuan Judul Skripsi</a></li>
		<li class="active">Input Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Pengajuan Judul Skripsi Baru</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-12">
				{!! Form::model(new Siakad\PengajuanSkripsi, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.skripsi.pengajuan.store']]) !!}
				@include('mahasiswa/skripsi/pengajuan/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection