@extends('app')

@section('title')
Input Jadwal
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Input Jadwal</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/jadwal') }}"> Jadwal Perkuliahan</a></li>
		<li class="active">Input Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Jadwal Perkuliahan</h3>
	</div>
	<div class="box-body">
		@if($matkul === null)
		<div class="alert alert-info">
			<h4><i class="icon fa fa-info"></i> Informasi</h4>
			Belum ada Kelas Kuliah yang dibuka untuk Tahun Akademik yang sedang aktif. Hubungi Administrator / Bagian Akademik
		</div>
		@else
		{!! Form::model(new Siakad\Jadwal, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['matkul.tapel.jadwal.store']]) !!}
		@include('jadwal/partials/_form', ['btn_type' => 'btn-primary'])
		{!! Form::close() !!}
		@endif
	</div>
</div>
@endsection