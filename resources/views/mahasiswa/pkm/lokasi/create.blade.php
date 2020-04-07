@extends('app')

@section('title')
Input Data Lokasi PKM {{ $pkm -> tapel -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Data Lokasi PKM {{ $pkm -> tapel -> nama }}
		<small>Tambah Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pkm') }}"> Data PKM</a></li>
		<li class="active">Tambah Lokasi PKM</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Lokasi PKM {{ $pkm -> tapel -> nama}}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\PkmLokasi, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.pkm.lokasi.store', $pkm -> id]]) !!}
				@include('mahasiswa/pkm/lokasi/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection	