@extends('app')

@section('title')
Input Data Lokasi PPL {{ $ppl -> strata }} {{ $ppl -> prodi }} {{ $ppl -> tapel }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Data Lokasi PPL {{ $ppl -> strata }} {{ $ppl -> prodi }} {{ $ppl -> tapel }}
		<small>Tambah Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/ppl') }}"> Data PPL</a></li>
		<li class="active">Tambah Lokasi PPL {{ $ppl -> strata }} {{ $ppl -> prodi }} {{ $ppl -> tapel }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Lokasi PPL  {{ $ppl -> strata }} {{ $ppl -> prodi }} {{ $ppl -> tapel }}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\PkmLokasi, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.ppl.lokasi.store', $ppl -> id]]) !!}
				@include('mahasiswa/ppl/lokasi/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
	</div>
	@endsection	