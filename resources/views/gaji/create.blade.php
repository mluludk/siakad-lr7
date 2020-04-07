@extends('app')

@section('title')
Pembayaran Gaji
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Pembayaran Gaji</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen') }}"> Dosen</a></li>
		<li class="active">Pembayaran Gaji</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Pembayaran Gaji</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\Gaji, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['gaji.store']]) !!}
		@include('gaji._partials.form')
		{!! Form::close() !!}
	</div>
</div>
@endsection	