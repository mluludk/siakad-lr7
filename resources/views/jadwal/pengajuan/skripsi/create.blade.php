@extends('app')

@section('title')
Pendaftaran Jadwal Pengajuan Skripsi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Jadwal Pengajuan Skripsi
		<small>Jadwal Baru</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('jadwal.pengajuan.skripsi.index') }}"> Jadwal Pengajuan Skripsi</a></li>
		<li class="active">Jadwal Baru</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Jadwal Pengajuan Skripsi</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\JadwalPengajuanSkripsi, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['jadwal.pengajuan.skripsi.store']]) !!}
				@include('/jadwal/pengajuan/skripsi/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection