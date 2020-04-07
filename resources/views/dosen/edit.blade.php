@extends('app')

@section('title')
Ubah data Dosen - {{ $dosen -> nama ?? 'Invalid'}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Ubah Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen') }}"> Dosen</a></li>
		<li><a href="{{ route('dosen.show', $dosen -> id) }}"> {{ $dosen -> nama }}</a></li>
		<li class="active">Ubah Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-body">
		<div class="row">
			<div class="col-sm-3 image_upload">
				<div id="validation-errors"></div>
				{!! Form::open(['url' => url('upload/image'), 'class' => 'form-inline', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload']) !!}
				@include('_partials/_foto', ['foto' => $dosen -> foto, 'default_image' => 'teacher.png'])
				{!! Form::close() !!}
				{!! Form::open(['url' => url('upload/image'), 'class' => 'form-inline', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload-ttd']) !!}
				@include('_partials/_foto', [
				'form' => 'upload-ttd',
				'foto' => $dosen -> ttd, 
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
				{!! Form::model($dosen, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['dosen.update', $dosen->id]]) !!}	
				
				@if(!$hasAccount)
				<div class="form-group">
					{!! Form::label('username', 'Username:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-3 col-xs-6">
						{!! Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'Username')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('password', 'Password:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-3">
						<input class="form-control" placeholder="Password" name="password" type="password" id="password">
					</div>
				</div>
				<hr/>
				@endif
				
				@include('dosen/partials/_form')
				{!! Form::hidden('foto', null, array('id' => 'foto')) !!}
				{!! Form::hidden('ttd', null, array('id' => 'ttd')) !!}
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
					</div>		
				</div>	
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection