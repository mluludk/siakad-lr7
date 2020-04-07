@extends('app')

@section('title')
Edit Jadwal
@endsection

@section('title')
Edit Jadwal
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Edit Jadwal</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/jadwal') }}"> Jadwal Perkuliahan</a></li>
		<li class="active">Edit</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Jadwal Perkuliahan</h3>
	</div>
	<div class="box-body">
		{!! Form::model($jadwal, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['matkul.tapel.jadwal.update', $jadwal->id]]) !!}
		@include('jadwal.partials._form', ['btn_type' => 'btn-warning'])
		{!! Form::close() !!}
	</div>
</div>
@endsection	