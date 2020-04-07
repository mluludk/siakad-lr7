@extends('app')

@section('title')
Edit data Pimpinan {{ $pimpinan -> dosen -> nama ?? ''}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pimpinan
		<small>Edit Data Pimpinan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pimpinan') }}"> Daftar Pimpinan</a></li>
		<li class="active">Edit Data Pimpinan</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Data Pimpinan</h3>
	</div>	
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($pimpinan, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['pimpinan.update', $pimpinan->id]]) !!}
				@include('pimpinan/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection