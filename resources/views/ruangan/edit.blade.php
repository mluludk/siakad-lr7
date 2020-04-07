@extends('app')

@section('title')
Edit data ruang kuliah {{ $ruangan -> nama ?? ''}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Ruang Kuliah
		<small>Ubah Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/ruangan') }}"> Ruang Kuliah</a></li>
		<li class="active">Ubah Data Ruang Kuliah {{ $ruangan -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Data Ruang Kuliah</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($ruangan, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['ruangan.update', $ruangan->id]]) !!}
				@include('ruangan/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection