@extends('app')

@section('title')
Tambah Jenis Pembayaran
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Tambah Jenis Pembayaran</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/jenisbiaya') }}">Jenis Pembayaran</a></li>
		<li class="active">Tambah</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Jenis Pembayaran</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\JenisBiaya, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['jenisbiaya.store']]) !!}
		@include('jenisbiaya._partials.form', ['btn_type' => 'btn-primary'])
		{!! Form::close() !!}
	</div>
</div>
@endsection	