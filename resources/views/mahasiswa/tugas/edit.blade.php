@extends('app')

@section('title')
Edit Tugas Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Tugas Mahasiswa
		<small>Ubah Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa/tugas') }}"> Tugas Mahasiswa</a></li>
		<li class="active">Ubah Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Tugas Mahasiswa</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($tugas, ['id' => 'frm', 'method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.tugas.update', $tugas->id]]) !!}
				@include('mahasiswa/tugas/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection