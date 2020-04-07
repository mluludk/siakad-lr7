@extends('app')

@section('title')
Edit Jadwal Wisuda {{ $wisuda -> nama ?? ''}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Wisuda
		<small>Ubah Jadwal Wisuda</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/wisuda') }}"> Jadwal Wisuda</a></li>
		<li class="active">Ubah Jadwal Wisuda {{ $wisuda -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Ubah Jadwal Wisuda</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($wisuda, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.wisuda.update', $wisuda->id]]) !!}
				@include('mahasiswa/wisuda/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection