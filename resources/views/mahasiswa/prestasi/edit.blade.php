@extends('app')

@section('title')
Edit Prestasi Mahasiswa {{ $mahasiswa -> nama ?? ''}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Prestasi Mahasiswa
		<small>Edit Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa/') }}"> Mahasiswa</a></li>
		<li class="active">Edit Data Prestasi Mahasiswa</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Data Prestasi Mahasiswa</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($prestasi, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.prestasi.update', $prestasi -> mahasiswa_id, $prestasi -> id]]) !!}
				@include('mahasiswa/prestasi/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection