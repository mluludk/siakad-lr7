@extends('app')

@section('title')
Edit Data Calon Mahasiswa {{ $mahasiswa -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Periode PMB
		<small>Edit Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pmb') }}"> PMB</a></li>
		<li><a href="{{ url('/pmb/' . $pmb -> id . '/peserta') }}"> {{ $pmb -> nama }}</a></li>
		<li class="active">{{ $mahasiswa -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Data Calon Mahasiswa {{ $mahasiswa -> nama }}</h3>
	</div>
	<div class="box-body">
		{!! Form::model($mahasiswa, ['method' => 'PATCH', 'files' => true, 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['pmb.peserta.update', $pmb -> id, $mahasiswa -> kode]]) !!}
		@include('pmb/peserta/_form', ['btn_type' => 'btn-warning'])
		{!! Form::close() !!}
	</div>
</div>
@endsection	