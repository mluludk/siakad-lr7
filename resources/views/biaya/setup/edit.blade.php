@extends('app')

@section('title')
Edit Data Setup Biaya
@endsection

@section('header')
<section class="content-header">
	<h1>
		Setup Biaya
		<small>Ubah Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/biaya/setup') }}"> Setup Biaya</a></li>
		<li class="active">Ubah Data Setup Biaya</li>
	</ol>
</section>
@endsection

@section('content')
@if($message !== null)
<div class="callout callout-warning">
	<h4>Peringatan</h4>
	<p>{{ $message }}</p>
</div>
@endif
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Data Setup Biaya</h3>
	</div>
	<div class="box-body">
		{!! Form::model($setup, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['biaya.setup.update']]) !!}
		@include('biaya/setup/partials/_form', ['btn_type' => 'btn-warning'])
		{!! Form::close() !!}
	</div>
</div>
@endsection