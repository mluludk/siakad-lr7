@extends('app')

@section('title')
Input Data PKM
@endsection

@section('header')
<section class="content-header">
	<h1>
		Data PKM
		<small>Input Data PKM</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pkm') }}"> Data PKM</a></li>
		<li class="active">Input Data PKM</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Data PKM</h3>
	</div>
	<div class="box-body">
		<div class="row">
			{!! Form::model(new Siakad\Pkm, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.pkm.store']]) !!}
			@include('mahasiswa/pkm/partials/_form', ['btn_type' => 'btn-primary'])
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection