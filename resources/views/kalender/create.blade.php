@extends('app')

@section('title')
Tambah Kegiatan
@endsection

@section('header')
<section class="content-header">
	<h1>
		Akademik
		<small>Tambah Kegiatan Akademik</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kalender') }}"> Kalender Akademik</a></li>
		<li class="active">Tambah Kegiatan Akademik</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Kegiatan Akademik</h3>
		<div class="box-tools">
			<a href="{{ route('kalender.index') }}" class="btn btn-info btn-xs btn-flat" title="Kalender Akademik"><i class="fa fa-calendar"></i> Kalender Akademik</a>
		</div>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\Kalender, ['class' => 'form-horizontal', 'role' => 'form', 'route' => 'kalender.store']) !!}
		@include('kalender/partials/_form', ['submit_text' => 'Simpan'])
		{!! Form::close() !!}
	</div>
</div>
@endsection