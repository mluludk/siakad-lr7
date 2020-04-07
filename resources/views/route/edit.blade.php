@extends('app')

@section('title')
Edit Navigation Link
@endsection

@section('header')
<section class="content-header">
	<h1>
		Navigation Link
		<small>Edit Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/route') }}"> Navigation Link</a></li>
		<li class="active">Edit Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Navigation Link</h3>
	</div>
	<div class="box-body">
		{!! Form::model($route, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['route.update', $route->id]]) !!}
		@include('route/_form')
		
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-9">
				<button class="btn btn-warning btn-flat" type="submit" id="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
			</div>		
		</div>	
		{!! Form::close() !!}
	</div>
</div>
@endsection