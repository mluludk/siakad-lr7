@extends('app')

@section('title')
Ubah Kelas & Jadwal Kuliah
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Edit Kelas & Jadwal Kuliah</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kelasperkuliahan') }}"> Kelas Kuliah</a></li>
		<li class="active">Edit  Kelas & Jadwal Kuliah</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Kelas & Jadwal Kuliah</h3>
	</div>
	<div class="box-body">
		{!! Form::model($matkul_tapel, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['kelasperkuliahan.update', $matkul_tapel -> id]]) !!}
		@include('matkul/tapel/partials/_form2', ['btn_type' => 'btn-warning', 'submit_text' => 'Simpan'])
		{!! Form::close() !!}
	</div>
</div>
@endsection						