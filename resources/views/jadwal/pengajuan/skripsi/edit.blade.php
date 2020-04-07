@extends('app')

@section('title')
Edit Jadwal Pengajuan Skripsi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pengajuan Skripsi
		<small>Edit Jadwal</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('jadwal.pengajuan.skripsi.index') }}"> Jadwal Pengajuan Skripsi</a></li>
		<li class="active">Edit Jadwal Pengajuan Skripsi {{ $pengajuan -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Jadwal Pengajuan Skripsi</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($pengajuan, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['jadwal.pengajuan.skripsi.update', $pengajuan -> id]]) !!}
				@include('/jadwal/pengajuan/skripsi/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection