@extends('app')

@section('title')
Edit Pendidikan Dosen | {{ $dosen -> nama ?? ''}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pendidikan Dosen
		<small>Edit Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen/') }}"> Dosen</a></li>
		<li><a href="{{ url('/dosen/' . $dosen -> id) }}"> {{ $dosen -> nama }}</a></li>
		<li><a href="{{ url('/dosen/' . $dosen -> id . '/pendidikan') }}"> Pendidikan</a></li>
		<li class="active">Edit Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Data Pendidikan Dosen</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($pendidikan, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['dosen.pendidikan.update', $dosen -> id, $pendidikan -> id]]) !!}
				@include('dosen/pendidikan/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection