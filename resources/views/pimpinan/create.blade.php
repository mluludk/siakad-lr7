@extends('app')

@section('title')
Tambah Pimpinan Baru
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pimpinan
		<small>Tambah Pimpinan Baru</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pimpinan') }}"> Daftar Pimpinan</a></li>
		<li class="active">Tambah Pimpinan Baru</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Pimpinan Baru</h3>
	</div>	
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\Pimpinan, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['pimpinan.store']]) !!}
				@include('pimpinan/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection