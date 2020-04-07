@extends('app')

@section('title')
Pendaftaran Ruang Kuliah Baru
@endsection

@section('header')
<section class="content-header">
	<h1>
		Ruang Kuliah
		<small>Input Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/ruangan') }}"> Ruang Kuliah</a></li>
		<li class="active">Input Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Ruang Kuliah</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\Ruang, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['ruangan.store']]) !!}
				@include('ruangan/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection