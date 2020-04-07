@extends('app')

@section('title')
Edit {{ $pengajuan -> nama }}  {{ $gelombang -> nama}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		{{ $pengajuan -> nama }} {{ $gelombang -> nama}}
		<small>Edit Gelombang</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('jadwal.pengajuan.skripsi.index') }}"> Jadwal Pengajuan Skripsi</a></li>
		<li class="active">Edit {{ $pengajuan -> nama }} {{ $gelombang -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit {{ $pengajuan -> nama }} {{ $gelombang -> nama}}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($gelombang, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['jadwal.pengajuan.skripsi.gelombang.update', $gelombang -> id]]) !!}
				@include('jadwal/pengajuan/skripsi/gelombang/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection