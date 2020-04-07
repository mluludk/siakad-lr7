@extends('app')

@section('title')
Edit Penelitian Mahasiswa {{ $mahasiswa -> nama ?? ''}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Penelitian Mahasiswa
		<small>Edit Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		@if($auth -> role_id != 512)
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		@endif
		<li><a href="{{ url('/mahasiswa/' . $mahasiswa -> id) }}"> {{ $mahasiswa -> nama }}</a></li>
		<li><a href="{{ url('/mahasiswa/' . $mahasiswa -> id) }}/penelitian"> Penelitian Mahasiswa</a></li>
		<li class="active">Edit Data Penelitian Mahasiswa</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Data Penelitian Mahasiswa</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($penelitian, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.penelitian.update', $penelitian -> id]]) !!}
				@include('mahasiswa/penelitian/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection