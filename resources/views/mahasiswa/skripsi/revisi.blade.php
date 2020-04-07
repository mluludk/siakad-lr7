@extends('app')

@section('title')
Revisi Judul Skripsi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Skripsi
		<small>Revisi Judul</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Revisi Judul Skripsi</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit data Skripsi</h3>
	</div>
	<div class="box-body">
		{!! Form::model($skripsi, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['skripsi.revisi.post', $skripsi -> id]]) !!}				
		<div class="form-group">
			{!! Form::label('nama', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-5">
				<p class="form-control-static">{{ $mahasiswa -> nama }}</p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">NIM:</label>
			<div class="col-sm-9">
				<p class="form-control-static">{{ $mahasiswa -> NIM }}</p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">PRODI:</label>
			<div class="col-sm-9">
				<p class="form-control-static">{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }} {{ $mahasiswa -> kelas -> nama }}</p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Pembimbing:</label>
			<div class="col-sm-9">
				<p class="form-control-static">
					{{ $skripsi -> pembimbing[0] -> gelar_depan }} {{ $skripsi -> pembimbing[0] -> nama }} {{ $skripsi -> pembimbing[0] -> gelar_belakang }}
				</p>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('judul', 'Judul Skripsi:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-9">
				<p class="form-control-static">{{ $skripsi -> judul }}</p>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('revisi', 'Revisi:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-9">
				{!! Form::textarea('revisi', null, array('class' => 'form-control', 'placeholder' => 'Revisi Judul Skripsi', 'rows' => '3')) !!}
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-7">
				<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
			</div>		
		</div>	
		{!! Form::close() !!}
	</div>
</div>
@endsection											