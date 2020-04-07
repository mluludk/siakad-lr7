@extends('app')

@section('title')
Absensi Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Input Data Absensi</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen') }}"> Dosen</a></li>
		<li><a href="{{ url('/dosen/absensi') }}"> Absensi</a></li>
		<li class="active">Input Data Absensi</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\AbsensiDosen, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['dosen.absensi.store']]) !!}
				@include('dosen/absensi/partials/_form')
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection