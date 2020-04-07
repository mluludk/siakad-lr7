@extends('app')

@section('title')
Pendaftaran PRODI Baru
@endsection

@section('header')
<section class="content-header">
	<h1>
		PRODI
		<small>Pendaftaran PRODI Baru</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/prodi') }}"> Daftar PRODI</a></li>
		<li class="active">Pendaftaran PRODI Baru</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Pendaftaran PRODI Baru</h3>
	</div>	
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\Prodi, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['prodi.store']]) !!}
				@include('prodi/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection