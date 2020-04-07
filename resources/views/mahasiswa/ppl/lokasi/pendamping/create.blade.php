@extends('app')

@section('title')
Input Data Pendamping PPL {{ $ppl -> strata }} {{ $ppl -> prodi }} {{ $ppl -> tapel }} {{ $lokasi -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pendamping PPL {{ $ppl -> strata }} {{ $ppl -> prodi }} {{ $ppl -> tapel }} {{ $lokasi -> nama }}
		<small>Tambah Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/ppl') }}"> Data PPL</a></li>
		<li class="active">Tambah Pendamping PPL {{ $ppl -> strata }} {{ $ppl -> prodi }} {{ $ppl -> tapel }} {{ $lokasi -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Pendamping PPL {{ $ppl -> strata }} {{ $ppl -> prodi }} {{ $ppl -> tapel }} {{ $lokasi -> nama }}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\PkmLokasiDosen, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.ppl.lokasi.pendamping.store', $ppl -> id, $lokasi -> id]]) !!}
				@include('mahasiswa/ppl/lokasi/pendamping/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection