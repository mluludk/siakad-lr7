@extends('app')

@section('title')
Ganti Password
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pengguna
		<small>Ganti Password</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Ganti Password</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Ganti Password</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($user, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['password.update', $user->id]]) !!}
				
				<div class="form-group">
					<label class="col-md-3 control-label">Username</label>
					<div class="col-md-4">
						<p class="form-control-static">{{ $user->username }}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Password Sekarang</label>
					<div class="col-md-4">
						<input type="password" class="form-control" name="old-password" required="required">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-md-3 control-label">Password Baru</label>
					<div class="col-md-4">
						<input type="password" class="form-control" name="password" required="required">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-md-3 control-label">Ulangi Password Baru</label>
					<div class="col-md-4">
						<input type="password" class="form-control" name="password_confirmation"  required="required">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-md-3 control-label"></label>
					<div class="col-md-9">
						<button type="submit" class="btn btn-primary" ><i class="fa fa-floppy-o"></i> Simpan</button>
					</div>
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection	