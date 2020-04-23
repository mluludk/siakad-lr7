@extends('app')

@section('title')
Input Kegiatan Pembelajaran
@endsection

@section('header')
<section class="content-header">
	<h1>
		Kegiatan
		<small>Input Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('matkul.tapel.index') }}"> Kelas Kuliah</a></li>
		<li><a href="{{ route('matkul.tapel.sesi.index', $kelas -> id) }}"> Sesi Pembelajaran</a></li>
		<li><a href="{{ route('matkul.tapel.sesi.kegiatan.index', [$kelas -> id, $sesi -> id]) }}"> Kegiatan</a></li>
		<li class="active">Input Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Kegiatan</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\Kegiatan, ['id' => 'post-form', 'files' => true, 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['matkul.tapel.sesi.kegiatan.store', $kelas -> id, $sesi -> id, $jenis_id]]) !!}
		@include('matkul/tapel/sesi/kegiatan/partials/_form', ['btn_type' => 'btn-primary'])
		{!! Form::close() !!}
	</div>
</div>
@endsection