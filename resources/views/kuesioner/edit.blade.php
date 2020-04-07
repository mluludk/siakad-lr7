@extends('app')

@section('title')
Edit Pertanyaan
@endsection

@section('header')
<section class="content-header">
	<h1>
		Kuesioner
		<small>Edit Pertanyaan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kuesioner') }}"> Kuesioner</a></li>
		<li class="active">Edit Pertanyaan</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Pertanyaan</h3>
	</div>
	<div class="box-body">
		{!! Form::model($kuesioner, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['kuesioner.update', $kuesioner ->id]]) !!}
		@include('kuesioner/partials/_form', ['btn_type' => 'btn-warning'])
		{!! Form::close() !!}
	</div>
</div>
@endsection