@extends('app')

@section('title')
Pendaftaran Dosen Baru
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Input Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen') }}"> Dosen</a></li>
		<li class="active">Input Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Pendaftaran Dosen Baru</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-3">
				{!! Form::open(['url' => url('/upload/image'), 'class' => 'form-inline', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload']) !!}
				@include('_partials/_foto', ['default_image' => 'teacher.png'])
				{!! Form::close() !!}
				{!! Form::open(['url' => url('upload/image'), 'class' => 'form-inline', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload-ttd']) !!}
				@include('_partials/_foto', [
				'form' => 'upload-ttd',
				'file_selector' => 'file_ttd', 
				'message' => 'Pilih file TTD', 
				'default_image' => 'untitled.png',
				'target' => 'ttd',
				'resized_width' => 600,
				'resized_height' => 300
				])
				{!! Form::close() !!}
			</div>
			<div class="col-sm-9">
				{!! Form::model(new Siakad\Dosen, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['dosen.store']]) !!}
				@include('dosen/partials/_form', ['submit_text' => 'Simpan'])
				{!! Form::hidden('foto', null, array('id' => 'foto')) !!}
				
				<hr/>
				<div class="form-group">
					{!! Form::label('username', 'Username*:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-3 col-xs-6">
						{!! Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'Username')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('password', 'Password*:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-3">
						<input class="form-control" placeholder="Password" name="password" type="password" id="password">
					</div>
				</div>
				<p class="help-block">*: Sistem akan membuatkan <em>Username</em> dan <em>Password</em> secara otomatis jika tidak diisi.</p>
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