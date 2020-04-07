@extends('app')

@section('title')
Edit Jenis Pembayaran
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Edit Jenis Pembayaran</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/jenisbiaya') }}">Jenis Pembayaran</a></li>
		<li class="active">Edit</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Jenis Pembayaran</h3>
	</div>
	<div class="box-body">
		{!! Form::model($jbiaya, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['jenisbiaya.update', $jbiaya->id]]) !!}
		@include('jenisbiaya._partials.form', ['btn_type' => 'btn-warning'])
		{!! Form::close() !!}
	</div>
</div>
@endsection	