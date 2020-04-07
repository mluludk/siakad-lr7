@extends('app')

@section('title')
Reset Password Semua Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pengguna
		<small>Reset Password Semua Mahasiswa</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pengguna/?filter=mahasiswa') }}"> Pengguna Mahasiswa</a></li>
		<li class="active">Reset Password Semua Mahasiswa</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Reset Password Semua Mahasiswa</h3>
		<div class="box-tools">
			<a href="{{ url('/pengguna/resetpassword/mahasiswa/filter') }}" class="btn btn-danger btn-xs btn-flat" title="Reset Password Mahasiswa"><i class="fa fa-user"></i></a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				<form role="form" method="post" action="{{ url('/pengguna/resetpassword/mahasiswa') }}" class="form-horizontal">
					{!! csrf_field() !!}
					<div class="form-group">
						<label class="col-md-2 control-label">Password baru</label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="password">
							<span class="help-block">Jika tidak diisi maka password akan dibuat secara otomatis oleh sistem</span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-9">
							<button type="submit" class="btn btn-primary btn-flat btn-danger" ><i class="fa fa-refresh"></i> Reset</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	</div>
	@endsection		