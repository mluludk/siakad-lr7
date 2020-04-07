@extends('app')

@section('title')
Tambah Transaksi Keuangan
@endsection

@section('header')
<section class="content-header">
	<h1>
		Transaksi Keuangan
		<small>Baru</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="/transaksi"> Transaksi Keuangan</a></li>
		<li class="active">Baru</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Transaksi Keuangan</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\Transaksi, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['transaksi.store']]) !!}
		@include('transaksi._partials.form')
		{!! Form::close() !!}
	</div>
</div>
@endsection	