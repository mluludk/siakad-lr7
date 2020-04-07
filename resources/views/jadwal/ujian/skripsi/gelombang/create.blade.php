@extends('app')

@section('title')
Pendaftaran Gelombang {{ $ujian -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		{{ $ujian -> nama }}
		<small>Gelombang Baru</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('jadwal.ujian.skripsi.index', $j) }}"> Jadwal Ujian {{ ucfirst($j) }} Skripsi</a></li>
		<li class="active">Gelombang Baru</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Pendaftaran Gelombang {{ $ujian -> nama }}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\JadwalUjianSkripsiGelombang, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['jadwal.ujian.skripsi.gelombang.store', $j, $ujian -> id]]) !!}
				@include('/jadwal/ujian/skripsi/gelombang/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection