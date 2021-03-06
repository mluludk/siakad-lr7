@extends('app')

@section('title')
Tambah Setup Biaya
@endsection

@section('header')
<section class="content-header">
	<h1>
		Setup Biaya
		<small>Input Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/biaya/setup') }}"> Setup Biaya</a></li>
		<li class="active">Input Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Setup Biaya</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\BiayaKuliah, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['biaya.setup.store']]) !!}
				@include('biaya/setup/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection