@extends('app')

@section('title')
Ubah Kelas Kuliah
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Edit Kelas Kuliah</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/matkul/tapel') }}"> Kelas Kuliah</a></li>
		<li class="active">Edit Kelas Kuliah</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Kelas Kuliah</h3>
	</div>
	<div class="box-body">
		{!! Form::model($matkul_tapel, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['matkul.tapel.update', $matkul_tapel -> id]]) !!}
		<div class="form-group">
			{!! Form::label('prodi_id', 'Program Studi:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-8">
				<p class="form-control-static">
					{{ $prodi -> strata }} {{ $prodi -> nama }} {{ $kelas -> nama }} 
				</p>
				{!! Form::hidden('prodi_id', $prodi -> id) !!}
				{!! Form::hidden('kelas', $kelas -> id) !!}
			</div>
		</div>
		@include('matkul/tapel/partials/_form', ['btn_type' => 'btn-warning', 'submit_text' => 'Simpan'])
		{!! Form::close() !!}
	</div>
</div>
@endsection						