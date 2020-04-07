@extends('app')

@section('title')
Edit Fungsional Dosen | {{ $dosen -> nama ?? ''}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Fungsional Dosen
		<small>Edit Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen/') }}"> Dosen</a></li>
		<li><a href="{{ url('/dosen/' . $dosen -> id) }}"> {{ $dosen -> nama }}</a></li>
		<li><a href="{{ url('/dosen/' . $dosen -> id . '/fungsional') }}"> Fungsional</a></li>
		<li class="active">Edit Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Data Fungsional Dosen</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($fungsional, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['dosen.fungsional.update', $dosen -> id, $fungsional -> id]]) !!}
				@include('dosen/fungsional/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection