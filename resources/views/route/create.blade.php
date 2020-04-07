@extends('app')

@section('title')
New Route
@endsection

@section('header')
<section class="content-header">
	<h1>
		Route
		<small>Input Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/route') }}"> Route</a></li>
		<li class="active">Input Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">New Route</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\Route, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['route.store']]) !!}
		@include('route/_form')
		
		<div class="form-group">
			<label for="crud" class="col-sm-2 control-label"></label>
			<div class="col-sm-10">
				<label class="checkbox-inline">
				<input type="checkbox" name="crud" value="y">Generate CRUD Routes</label>
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-9">
				<button class="btn btn-primary btn-flat" type="submit" id="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
			</div>		
		</div>	
		{!! Form::close() !!}
	</div>
</div>
@endsection