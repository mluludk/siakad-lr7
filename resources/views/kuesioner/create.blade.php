@extends('app')

@section('title')
Input Pertanyaan Kuesioner
@endsection

@section('header')
<section class="content-header">
	<h1>
		Kuesioner
		<small>Input Pertanyaan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kuesioner') }}"> Kuesioner</a></li>
		<li class="active">Input Pertanyaan</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Pertanyaan</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\Kuesioner, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['kuesioner.store']]) !!}
		@include('kuesioner/partials/_form', ['btn_type' => 'btn-primary'])
		{!! Form::close() !!}
	</div>
</div>
@endsection