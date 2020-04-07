@extends('app')

@section('title')
Edit Data Bimbingan Skripsi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Skripsi
		<small>Ubah Data Bimbingan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li><a href="{{ url('/mahasiswa/' . $skripsi -> pengarang -> id) }}"> {{ $skripsi -> pengarang -> nama }}</a></li>
		<li><a href="{{ url('/skripsi/' . $skripsi -> id) }}"> Skripsi</a></li>
		<li class="active">Ubah Data Bimbingan</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Ubah Data Bimbingan</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($bimbingan, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.skripsi.bimbingan.update', $skripsi -> id, $bimbingan -> id]]) !!}
				@include('mahasiswa/skripsi/bimbingan/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection