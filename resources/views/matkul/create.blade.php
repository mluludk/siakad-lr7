@extends('app')

@section('title')
Tambah Mata Kuliah Baru
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mata Kuliah
		<small>Tambah Mata Kuliah Baru</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/matkul') }}"> Mata Kuliah</a></li>
		<li class="active">Tambah Mata Kuliah Baru</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Mata Kuliah Baru</h3>
	</div>	
	<div class="box-body">
		{!! Form::model(new Siakad\Matkul, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['matkul.store']]) !!}
		@include('matkul/partials/_form', ['btn_type' => 'btn-primary'])
		{!! Form::close() !!}
	</div>
</div>
@endsection