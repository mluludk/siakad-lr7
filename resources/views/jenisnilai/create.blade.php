@extends('app')

@section('title')
Input data Daftar Unsur & Bobot Penilaian
@endsection

@section('header')
<section class="content-header">
	<h1>
		Daftar Unsur & Bobot Penilaian
		<small>Input data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/jenisnilai') }}"> Daftar Unsur & Bobot Penilaian</a></li>
		<li class="active">Input data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input data</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\JenisNilai, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['jenisnilai.store']]) !!}
				@include('jenisnilai/partials/_form')
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection