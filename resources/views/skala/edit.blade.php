@extends('app')

@section('title')
Edit Skala Nilai
@endsection

@section('header')
<section class="content-header">
	<h1>
		Skala Nilai
		<small>Edit</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/skala') }}"> Skala Nilai</a></li>
		<li class="active">Edit</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Skala Nilai</h3>
	</div>
	<div class="box-body">
		{!! Form::model($skala, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['skala.update', $skala->id]]) !!}
		@include('skala/partials/_form', ['btn_type' => 'btn-warning'])
		{!! Form::close() !!}
	</div>
</div>
@endsection