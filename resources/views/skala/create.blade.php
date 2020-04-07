@extends('app')

@section('title')
Tambah Skala Nilai
@endsection

@section('header')
<section class="content-header">
	<h1>
		Skala Nilai
		<small>Input Skala Nilai</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/skala') }}"> Skala Nilai</a></li>
		<li class="active">Input Skala Nilai</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Skala Nilai</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\Skala, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['skala.store']]) !!}
		@include('skala/partials/_form', ['btn_type' => 'btn-primary'])
		{!! Form::close() !!}
	</div>
</div>
@endsection