@extends('app')

@section('title')
Edit Sesi Pembelajaran
@endsection

@section('header')
<section class="content-header">
	<h1>
		Sesi Pembelajaran
		<small>Edit Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('matkul.tapel.index') }}"> Kelas Kuliah</a></li>
		<li><a href="{{ route('matkul.tapel.sesi.index', $kelas -> id) }}"> Sesi Pembelajaran</a></li>
		<li class="active">Edit Data </li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Data Sesi Pembelajaran</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($sesi, ['id' => 'post-form', 'method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['matkul.tapel.sesi.update', $kelas->id, $sesi -> id]]) !!}
				@include('matkul/tapel/sesi/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection