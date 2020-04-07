@extends('app')

@section('title')
Pendaftaran Bank Baru
@endsection

@section('header')
<section class="content-header">
	<h1>
		Bank
		<small>Input Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/bank') }}"> Bank</a></li>
		<li class="active">Input Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Bank</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\Bank, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['bank.store']]) !!}
		@include('bank/partials/_form', ['btn_type' => 'btn-primary'])
		{!! Form::close() !!}
	</div>
</div>
@endsection