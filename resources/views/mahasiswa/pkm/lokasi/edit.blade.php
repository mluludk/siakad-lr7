@extends('app')

@section('title')
Edit Data Lokasi PKM {{ $lokasi -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		PKM
		<small>Ubah Data PKM</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pkm') }}"> Data PKM</a></li>
		<li class="active">Ubah Data Lokasi PKM {{ $lokasi -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Ubah Data Lokasi PKM {{ $lokasi -> nama }}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($lokasi, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.pkm.lokasi.update', $pkm -> id, $lokasi -> id]]) !!}
				@include('mahasiswa/pkm/lokasi/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection