@extends('app')

@section('title')
Edit Jadwal Ujian {{ ucfirst($j) }} Skripsi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Ujian {{ ucfirst($j) }} Skripsi
		<small>Edit Jadwal</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('jadwal.ujian.skripsi.index', $j) }}"> Jadwal Pendaftaran Ujian {{ ucfirst($j) }} Skripsi</a></li>
		<li class="active">Edit Jadwal Pendaftaran Ujian {{ ucfirst($j) }} Skripsi {{ $ujian -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Jadwal Pendaftaran Ujian {{ ucfirst($j) }} Skripsi</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($ujian, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['jadwal.ujian.skripsi.update', $j, $ujian -> id]]) !!}
				@include('/jadwal/ujian/skripsi/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection