@extends('app')

@section('title')
Pembukaan Periode PMB Baru
@endsection

@section('header')
<section class="content-header">
	<h1>
		PMB
		<small>Pembukaan Periode PMB Baru</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pmb') }}"> PMB</a></li>
		<li class="active">Pembukaan Periode PMB Baru</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Pembukaan Periode PMB Baru</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\Pmb, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['pmb.store']]) !!}
				@include('pmb/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection