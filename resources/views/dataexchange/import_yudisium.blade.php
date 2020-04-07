@extends('app')

@section('title')
Impor Data
@endsection

@section('header')
<section class="content-header">
	<h1>
		Impor Data Yudisium
		<small> Mahasiswa</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Impor Data Yudisium Mahasiswa</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Impor Data Yudisium Mahasiswa</h3>
		<div class="box-tools">
			<a href="{{ url('/tpl/yudisium_tpl.xlsx') }}" class="btn btn-success btn-flat btn-xs"><i class="fa fa-download"></i> Download Template</a>
		</div>		
	</div>
	<div class="box-body">
		{!! Form::open(array('url'=>'/mahasiswa/yudisium/impor', 'method'=>'POST', 'files'=>true, 'class' => 'form-inline')) !!}
		{!! Form::file('excel', ['class' => 'form-control']) !!}
		<button class="btn btn-primary btn-flat" type="submit">Submit</button>
		{!! Form::close() !!}
	</div>
</div>
@endsection																												