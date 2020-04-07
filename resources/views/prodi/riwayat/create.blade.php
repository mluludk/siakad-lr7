@extends('app')

@section('title')
Tambah Riwayat Prodi {{ $prodi_data -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Prodi {{ $prodi_data -> nama }}
		<small>Tambah RIwayat </small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/prodi') }}"> Daftar PRODI</a></li>
		<li><a href="{{ route('prodi.riwayat.index', $prodi_data -> id) }}"> Prodi {{ $prodi_data -> nama }}</a></li>
		<li class="active">Pendaftaran PRODI Baru</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Riwayat Prodi {{ $prodi_data -> nama }}</h3>
	</div>	
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\ProdiRiwayat, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['prodi.riwayat.store', $prodi_data -> id]]) !!}
				@include('prodi/riwayat/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection