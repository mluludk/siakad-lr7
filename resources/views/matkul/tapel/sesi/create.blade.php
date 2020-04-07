@extends('app')

@section('title')
Input Sesi Pembelajaran Kelas Kuliah
@endsection

@section('header')
<section class="content-header">
	<h1>
		Sesi Pembelajaran
		<small>Input Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('matkul.tapel.index') }}"> Kelas Kuliah</a></li>
		<li><a href="{{ route('matkul.tapel.sesi.index', $kelas -> id) }}"> Sesi Pembelajaran</a></li>
		<li class="active">Input Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Sesi Pembelajaran</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\SesiPembelajaran, ['id' => 'post-form', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['matkul.tapel.sesi.store', $kelas -> id]]) !!}
		@include('matkul/tapel/sesi/partials/_form', ['btn_type' => 'btn-primary'])
		{!! Form::close() !!}
	</div>
</div>
@endsection