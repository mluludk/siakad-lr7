@extends('app')

@section('title')
Input Data PPL
@endsection

@section('header')
<section class="content-header">
	<h1>
		Data PPL
		<small>Input Data PPL</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/ppl') }}"> Data PPL</a></li>
		<li class="active">Input Data PPL</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Data PPL</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\Ppl, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.ppl.store']]) !!}
				@include('mahasiswa/ppl/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection