@extends('app')

@section('title')
Tambah Jenis Gaji
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Input Jenis Gaji</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('jenisgaji.index') }}"> Jenis Gaji</a></li>
		<li class="active">Input Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Jenis Gaji</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\JenisGaji, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['jenisgaji.store']]) !!}
		@include('jenisgaji._partials.form', ['btn_type' => 'btn-primary'])
		{!! Form::close() !!}
	</div>
</div>
@endsection		