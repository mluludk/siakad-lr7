@extends('app')

@section('title')
Edit Kegiatan
@endsection

@section('header')
<section class="content-header">
	<h1>
		Akademik
		<small>Edit Kegiatan Akademik</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kalender') }}"> Kalender Akademik</a></li>
		<li class="active">Edit Kegiatan Akademik</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Kegiatan Akademik</h3>
		<div class="box-tools">
			<a href="{{ route('kalender.index') }}" class="btn btn-info btn-xs btn-flat" title="Kalender Akademik"><i class="fa fa-calendar"></i> Kalender Akademik</a>
		</div>
	</div>
	<div class="box-body">
		{!! Form::model($agenda, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['kalender.update', $agenda->id]]) !!}	
		{{ csrf_field() }}
		@include('kalender/partials/_form', ['submit_text' => 'Simpan'])
		{!! Form::close() !!}
	</div>
</div>
@endsection