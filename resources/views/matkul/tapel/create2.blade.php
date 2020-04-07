@extends('app')

@section('title')
Tambah Kelas & Jadwal Kuliah
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pekuliahan
		<small>Detail Kelas & Jadwal</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kelasperkuliahan') }}"> Kelas Kuliah</a></li>
		<li class="active">Tambah Kelas & Jadwal Kuliah</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Kelas & Jadwal Kuliah</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\MatkulTapel, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['kelasperkuliahan.store', $kurikulum_matkul -> id]]) !!}
		@include('matkul/tapel/partials/_form2', ['btn_type' => 'btn-primary', 'submit_text' => 'Simpan'])
		{!! Form::close() !!}
	</div>
</div>
@endsection