@extends('app')

@section('title')
Edit data PRODI {{ $prodi -> nama ?? ''}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		PRODI
		<small>Edit Data PRODI</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/prodi') }}"> Daftar PRODI</a></li>
		<li class="active">Edit Data PRODI</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Data PRODI</h3>
	</div>	
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($prodi, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['prodi.update', $prodi->id]]) !!}
				{!! Form::hidden('role_id', $role -> id) !!}
				@include('prodi/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection