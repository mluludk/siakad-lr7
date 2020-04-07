@extends('app')

@section('title')
Ubah data Tahun Akademik
@endsection

@section('header')
<section class="content-header">
	<h1>
		Tahun Akademik
		<small>Ubah Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/tapel') }}"> Tahun Akademik</a></li>
		<li class="active">Ubah Data Tahun Akademik</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Ubah Data Tahun Akademik</h3>
	</div>
	<div class="box-body">
		
		<div class="callout callout-warning">
			<h4>PERHATIAN !</h4>
			Pengaktifan Tahun Akademik akan memproses hal-hal berikut secara otomatis.
			<ol>
				<li>Status Mahasiswa <span class="label label-success">AKTIF</span> menjadi <span class="label label-danger">NON AKTIF</span>.</li>
				<li>Semester Mahasiswa.</li>
				<li>Aktifitas perkuliahan.</li>
				<li>Penugasan Dosen.</li>
			</ol>
			Pastikan Nilai dan semua Proses Administrasi Mahasiswa sudah selesai sebelum mengaktifkan Tahun Akademik yang baru.
		</div>
		
		{!! Form::model($tapel, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['tapel.update', $tapel ->id]]) !!}
		<input type="hidden" name="id" value="{{ $tapel  -> id }}"/>
		
		<div class="form-group">
			{!! Form::label('nama', 'Tahun Akademik:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-2">
				<div class="form-control-static"><strong>{{ $tapel -> nama }}</strong></div>
			</div>
		</div>
		
		@include('tapel/partials/_form')
		
		<div class="form-group">
			{!! Form::label('aktif', 'Status:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-8">
				<label class="radio-inline">
					{!! Form::radio('aktif', 'y') !!} Aktif
				</label>
				<label class="radio-inline">
					{!! Form::radio('aktif', 'n') !!} Tidak aktif
				</label>
			</div>
		</div>
		
		<!--
			<div class="form-group">
			{!! Form::label('check_krs', 'KRS:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-8">
			<label class="radio-inline">
			{!! Form::radio('check_krs', 'y') !!} Buka
			</label>
			<label class="radio-inline">
			{!! Form::radio('check_krs', 'n') !!} Tutup
			</label>
			</div>
			</div>
		-->
		
		<div class="form-group">
			{!! Form::label('check_nilai_sem_aktif', 'Penilaian Semester Aktif:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-8">
				<label class="radio-inline">
				{!! Form::radio('check_nilai_sem_aktif', 'y') !!} Buka
				</label>
			<label class="radio-inline">
			{!! Form::radio('check_nilai_sem_aktif', 'n') !!} Tutup
			</label>
			</div>
			</div>
			
			<div class="form-group">
			{!! Form::label('check_nilai_sem_non_aktif', 'Penilaian Semester Non-Aktif:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-8">
			<label class="radio-inline">
			{!! Form::radio('check_nilai_sem_non_aktif', 'y') !!} Buka
			</label>
			<label class="radio-inline">
			{!! Form::radio('check_nilai_sem_non_aktif', 'n') !!} Tutup
			</label>
			</div>
			</div>
			
			<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
			</div>		
			</div>		
			{!! Form::close() !!}
			</div>
			</div>
			@endsection			