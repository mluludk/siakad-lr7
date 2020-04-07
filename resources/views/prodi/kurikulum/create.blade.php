@extends('app')

@section('title')
Tambah Kurikulum
@endsection

@section('header')
<section class="content-header">
	<h1>
		Kurikulum
		<small>Tambah data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kurikulum') }}"> Kurikulum</a></li>
		<li class="active">Tambah Kurikulum</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Kurikulum</h3>
	</div>	
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\Kurikulum, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['prodi.kurikulum.store']]) !!}
				@include('prodi/kurikulum/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection