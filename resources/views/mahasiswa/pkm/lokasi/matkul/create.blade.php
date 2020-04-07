@extends('app')

@section('title')
Input Data Mata Kuliah PKM {{ $pkm -> tapel -> nama }} {{ $lokasi -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mata Kuliah PKM {{ $pkm -> tapel -> nama }} {{ $lokasi -> nama }}
		<small>Tambah Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pkm') }}"> Data PKM</a></li>
		<li class="active">Tambah Mata Kuliah PKM {{ $pkm -> tapel -> nama }} {{ $lokasi -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Mata Kuliah PKM {{ $pkm -> tapel -> nama }} {{ $lokasi -> nama }}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\PkmLokasiMatkul, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.pkm.lokasi.matkul.store', $pkm -> id, $lokasi -> id]]) !!}
				@include('mahasiswa/pkm/lokasi/matkul/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection