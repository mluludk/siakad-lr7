@extends('app')

@section('title')
Pendaftaran Mahasiswa Baru
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Pendaftaran Mahasiswa Baru</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li class="active">Pendaftaran Mahasiswa Baru</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Pendaftaran Mahasiswa Baru</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-3">
				{!! Form::open(['url' => url('/upload/image'), 'class' => 'form-inline', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload']) !!}
				@include('_partials/_foto', ['default_image' => 'b.png'])
				{!! Form::close() !!}
				{!! config('custom.ketentuan.foto') !!}
			</div>
			<div class="col-sm-9">
				{!! Form::model(new Siakad\Mahasiswa, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.store']]) !!}
				@include('mahasiswa/partials/_form')
				
				<hr/>
				<div class="form-group">
					{!! Form::label('username', 'Username*:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-3 col-xs-6">
						{!! Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'Username')) !!}
					</div>
					</div>
					<div class="form-group">
					{!! Form::label('password', 'Password*:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-3">
					<input class="form-control" placeholder="Password" name="password" type="password" id="password">
					</div>
					</div>
					<p class="help-block">*: NIM akan digunakan sebagai <em>Username</em> jika kolom tidak diisi. <em>Password</em> akan dibuatkan secara otomatis jika tidak diisi.</p>
					{!! Form::hidden('foto', null, array('id' => 'foto')) !!}
					<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
					<button class="btn btn-primary btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
					</div>		
					</div>
					{!! Form::close() !!}
					</div>
					</div>
					</div>
					</div>
					@endsection																						