@extends('app')

@section('title')
Impor Data
@endsection

@section('header')
<section class="content-header">
	<h1>
		Impor Data
		<small> Mahasiswa</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Impor Data Mahasiswa</li>
	</ol>
</section>
@endsection

@section('content')
@foreach($prodi as $p)
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Import Data Mahasiswa {{ $p -> singkatan }}</h3>
		<div class="box-tools">
			<a href="{{ url('/tpl/mahasiswa_tpl.xlsx') }}" class="btn btn-success btn-flat btn-xs"><i class="fa fa-download"></i> Download Template</a>
		</div>	
	</div>
	<div class="box-body">
		{!! Form::open(array('url'=>'import/mahasiswa', 'method'=>'POST', 'files'=>true, 'class' => 'form-inline')) !!}
		{!! Form::file('excel', ['class' => 'form-control']) !!}
		{!! Form::hidden('prodi_id', $p -> id) !!}
		{!! Form::submit('Submit', array('class'=>'btn btn-primary btn-flat')) !!}
		{!! Form::close() !!}
	</div>
</div>
@endforeach
@endsection																												