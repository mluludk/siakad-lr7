@extends('app')

@section('title')
Edit data ruang kuliah {{ $bank -> nama ?? ''}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Bank 
		<small>Ubah Data</small>
	</h1>
	<ol class="breadcrumb"> 
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/bank') }}"> Bank</a></li>
		<li class="active">Ubah Data Bank {{ $bank -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Data Bank</h3>
	</div>
	<div class="box-body">
		{!! Form::model($bank, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['bank.update', $bank->id]]) !!}
		@include('bank/partials/_form', ['btn_type' => 'btn-warning'])
		{!! Form::close() !!}
	</div>
</div>
@endsection