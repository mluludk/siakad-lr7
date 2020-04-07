@extends('app')

@section('title')
Input Data Tahun Akademik
@endsection

@section('header')
<section class="content-header">
	<h1>
		Tahun Akademik
		<small>Input Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/tapel') }}"> Tahun Akademik</a></li>
		<li class="active">Input Data Tahun Akademik</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Data Tahun Akademik</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\Tapel, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['tapel.store']]) !!}
		<div class="form-group">
			{!! Form::label('tahun', 'Tahun:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-2">
				{!! Form::select('tahun', array_combine($r = range($d = (date('Y') + 2), ($d - 5)), $r), null, array('class' => 'form-control')) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('semester', 'Semester:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-8">
				<label class="radio-inline">
					{!! Form::radio('semester', 'Ganjil') !!} Ganjil
				</label>
				<label class="radio-inline">
					{!! Form::radio('semester', 'Genap') !!} Genap
				</label>
			</div>
		</div>
		
		@include('tapel/partials/_form')
		
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button class="btn btn-primary btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
			</div>		
		</div>		
		{!! Form::close() !!}
	</div>
</div>
@endsection