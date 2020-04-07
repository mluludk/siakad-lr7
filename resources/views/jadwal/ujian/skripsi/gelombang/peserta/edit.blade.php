@extends('app')

@section('title')
Edit Peserta Ujian {{ ucfirst($j) }} Skripsi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Ujian {{ ucfirst($j) }} Skripsi
		<small>Edit Peserta</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('jadwal.ujian.skripsi.index', $j) }}"> Jadwal Ujian {{ $j }} Skripsi</a></li>
		<li><a href="{{ route('jadwal.ujian.skripsi.gelombang.peserta.index', [$j, $gelombang -> id]) }}"> Peserta {{ $gelombang -> ujian -> nama }} {{ $gelombang -> nama }}</a></li>
		<li class="active">Edit Peserta</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Peserta</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($jus, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['jadwal.ujian.skripsi.gelombang.peserta.update', $j, $jus -> jusg_id, $jus -> mahasiswa_id]]) !!}
				@include('/jadwal/ujian/skripsi/gelombang/peserta/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection