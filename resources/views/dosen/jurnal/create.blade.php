@extends('app')

@section('title')
Tambah Jurnal Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Input Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen/') }}"> Dosen</a></li>
		<li class="active">Input Data Jurnal Dosen</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Jurnal Dosen</h3>
	</div>
	<div class="box-body">
		<div class="row">
			{!! Form::model(new Siakad\DosenJurnal, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['dosen.jurnal.store']]) !!}
			@include('dosen/jurnal/partials/_form', ['btn_type' => 'btn-primary'])
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection