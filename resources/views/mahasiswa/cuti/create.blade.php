@extends('app')

@section('title')
Pengajuan Cuti
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Pengajuan Cuti</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa/') }}"> Mahasiswa</a></li>
		<li class="active">Pengajuan Cuti</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Pengajuan Cuti</h3>
	</div>
	<div class="box-body">
		<div class="row">
			{!! Form::model(new Siakad\MahasiswaCuti, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.cuti.store']]) !!}
			@include('mahasiswa/cuti/partials/_form', ['btn_type' => 'btn-primary'])
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection