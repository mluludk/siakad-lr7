@extends('app')

@section('title')
Edit Daftar Unsur & Bobot Penilaian
@endsection

@section('header')
<section class="content-header">
	<h1>
		Daftar Unsur & Bobot Penilaian
		<small>Edit data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/jenisnilai') }}"> Daftar Unsur & Bobot Penilaian</a></li>
		<li class="active">Edit data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Daftar Unsur & Bobot Penilaian</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($jenis, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['jenisnilai.update', $jenis->id]]) !!}
				@include('jenisnilai/partials/_form')
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection