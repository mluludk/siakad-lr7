@extends('app')

@section('title')
Ekspor Data Mahasiswa EMIS
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Ekspor Data EMIS</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url('/mahasiswa/') }}"><i class="fa fa-dashboard"></i> Mahasiswa</a></li>
		<li class="active">Ekspor Data EMIS</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Ekspor Data</h3>
	</div>
	<div class="box-body">
		{!! Form::open(['url' => url('/export/emis/mahasiswa'), 'class' => 'form-horizontal', 'autocomplete' => 'off']) !!}
		<div class="form-group">
			{!! Form::label('format', 'Format:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-2">
				{!! Form::select('format', $format, null, ['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('angkatan', 'Angkatan:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-3">
				{!! Form::select('angkatan', $angkatan, null, ['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button class="btn btn-success btn-flat" type="submit"><i class="fa fa-download"></i> Download</button>
			</div>		
		</div>	
		{!! Form::close() !!}
	</div>
</div>
@endsection																											