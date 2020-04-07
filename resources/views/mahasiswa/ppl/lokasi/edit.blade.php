@extends('app')

@section('title')
Edit Data Lokasi PPL {{ $lokasi -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		PPL
		<small>Ubah Data PPL</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/ppl') }}"> Data PPL</a></li>
		<li class="active">Ubah Data Lokasi PPL {{ $lokasi -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Ubah Data Lokasi PPL {{ $lokasi -> nama }}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($lokasi, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.ppl.lokasi.update', $ppl -> id, $lokasi -> id]]) !!}
				@include('mahasiswa/ppl/lokasi/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection