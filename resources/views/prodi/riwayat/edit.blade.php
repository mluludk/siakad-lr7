@extends('app')

@section('title')
Edit Riwayat PRODI {{ $prodi_data -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		PRODI {{ $prodi_data -> nama }}
		<small>Edit Riwayat</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/prodi') }}"> Daftar PRODI</a></li>
		<li><a href="{{ route('prodi.riwayat.index', $prodi_data -> id) }}"> Prodi {{ $prodi_data -> nama }}</a></li>
		<li class="active">Edit Data PRODI</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Riwayat PRODI {{ $prodi_data -> nama }}</h3>
	</div>	
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($riwayat, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['prodi.riwayat.update', $prodi_data -> id, $riwayat -> id]]) !!}
				@include('prodi/riwayat/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection