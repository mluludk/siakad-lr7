@extends('app')

@section('title')
Tambah Tugas Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Tugas Mahasiswa
		<small>Tambah Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa/tugas') }}"> Tugas Mahasiswa</a></li>
		<li class="active">Tambah Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Tugas Mahasiswa</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\Tugas, ['id' => 'frm', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.tugas.store']]) !!}
				@include('mahasiswa/tugas/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection