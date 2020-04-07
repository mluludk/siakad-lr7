@extends('app')

@section('title')
Edit Jurnal Dosen {{ $dosen -> nama ?? ''}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Jurnal Dosen
		<small>Edit Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen/') }}"> Dosen</a></li>
		<li class="active">Edit Data Jurnal Dosen</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Data Jurnal Dosen</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($jurnal, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['dosen.jurnal.update', $jurnal -> id]]) !!}
				@include('dosen/jurnal/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection