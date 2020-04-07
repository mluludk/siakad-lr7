@extends('app')

@section('title')
Pendaftaran Jadwal Wisuda
@endsection

@section('header')
<section class="content-header">
	<h1>
		Jadwal Wisuda
		<small>Input Jadwal Wisuda</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/wisuda') }}"> Jadwal Wisuda</a></li>
		<li class="active">Input Jadwal Wisuda</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Jadwal Wisuda</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\Wisuda, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.wisuda.store']]) !!}
				@include('mahasiswa/wisuda/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection