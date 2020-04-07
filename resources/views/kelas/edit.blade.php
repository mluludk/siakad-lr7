@extends('app')

@section('title')
Edit data program {{ $kelas -> nama ?? ''}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Program
		<small>Ubah Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kelas') }}"> Program</a></li>
		<li class="active">Ubah Data Program {{ $kelas -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Program</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($kelas, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['kelas.update', $kelas->id]]) !!}
				@include('kelas/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection