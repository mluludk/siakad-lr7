@extends('app')

@section('title')
Pendaftaran Pengguna
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pengguna
		<small>Input Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pengguna') }}">Pengguna</a></li>
		<li class="active">Input Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Data Pengguna</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-3">
				{!! Form::open(['url' => url('/upload/image'), 'class' => 'form-inline', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload']) !!}
				@include('_partials/_foto', ['default_image' => 'a.png'])
				{!! Form::close() !!}
			</div>
			<div class="col-sm-9">
				{!! Form::model(new Siakad\User, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['pengguna.store']]) !!}
				
				<div class="form-group">
					<label class="col-md-3 control-label">Username</label>
					<div class="col-md-4">
						<input type="text" class="form-control" name="username" value="{{ old('username') }}" required="required">
					</div>
				</div>
				{!! Form::hidden('foto', null, array('id' => 'foto')) !!}
				@include('users/partials/_form', ['required' => 'required="required"', 'btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection