@extends('app')

@section('title')
Edit data Periode PMB {{ $pmb -> nama ?? ''}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Periode PMB
		<small>Edit Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pmb') }}"> Periode PMB</a></li>
		<li class="active">Edit Data Periode PMB {{ $pmb -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Periode PMB</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($pmb, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['pmb.update', $pmb -> id]]) !!}
				@include('pmb/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection