@extends('app')

@section('title')
Cetak Sampul Jadwal
@endsection

@section('header')
<section class="content-header">
	<h1>
		Jadwal
		<small>Cetak Sampul Jadwal</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/matkul/tapel') }}"> Kelas Kuliah</a></li>
		<li><a href="{{ url('/jadwal') }}"> Jadwal</a></li>
		<li class="active">Cetak Sampul</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Cetak Sampul Jadwal</h3>
	</div>
	<div class="box-body">
		{!! Form::open(['url' => url('/jadwal/cover/print'), 'class' => 'form-horizontal', 'method' => 'GET']) !!}
		<div class="form-group">
			{!! Form::label('prodi_id', 'Program Studi:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-4">
				{!! Form::select('prodi_id', $prodi, null, ['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('kelas_id', 'Program:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-3">
				{!! Form::select('kelas_id', $program, null, ['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('tapel_id', 'Tahun Akademik:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-3">
				{!! Form::select('tapel_id', $tapel, null, ['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('semester', 'Semester:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-3">
				{!! Form::select('semester', $semester, null, ['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button class="btn btn-info btn-flat" type="submit"><i class="fa fa-print"></i> Cetak</button>
			</div>		
		</div>	
		{!! Form::close() !!}
	</div>
</div>
@endsection																											