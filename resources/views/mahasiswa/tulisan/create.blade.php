@extends('app')

@section('title')
Tambah Tulisan Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Input Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		@if($auth -> role_id != 512)
		<li><a href="{{ url('/mahasiswa/') }}"> Mahasiswa</a></li>
		@endif
		<li><a href="{{ url('/mahasiswa/' . $mahasiswa -> id) }}"> {{ $mahasiswa -> nama }}</a></li>
		<li><a href="{{ url('/mahasiswa/' . $mahasiswa -> id) }}/tulisan"> Tulisan Mahasiswa</a></li>
		<li class="active">Input Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Tulisan Mahasiswa</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\MahasiswaTulisan, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.tulisan.store', $mahasiswa -> id]]) !!}
		@include('mahasiswa/tulisan/partials/_form', ['btn_type' => 'btn-primary'])
		{!! Form::close() !!}
	</div>
</div>
@endsection