@extends('app')

@section('title')
Ubah Setting Tahun Akademik {{ $tapel -> nama }} {{ $prodi -> strata }} {{ $prodi -> singkatan }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Tahun Akademik {{ $tapel -> nama }}
		<small>Ubah Setting PRODI {{ $prodi -> strata }} {{ $prodi -> singkatan }}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/tapel') }}"> Tahun Akademik</a></li>
		<li><a href="{{ url('/tapel/' . $tapel -> id) }}"> {{ $tapel -> nama }}</a></li>
		<li class="active">Ubah Setting {{ $prodi -> strata }} {{ $prodi -> singkatan }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Ubah Setting Tahun Akademik {{ $tapel -> nama }} {{ $prodi -> strata }} {{ $prodi -> singkatan }}</h3>
	</div>
	<div class="box-body">		
		{!! Form::model($setting, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['tapel.setting.update', $tapel -> id, $setting -> id]]) !!}		
		<div class="form-group">
			{!! Form::label('', 'Tahun Akademik:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-9">
				<div class="form-control-static">{{ $tapel -> nama }}</div>
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('', 'Program Studi:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-9">
				<div class="form-control-static">{{ $prodi -> strata }} {{ $prodi -> nama }}</div>
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('target_mhs_baru', 'Target Mahasiswa Baru:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-1">
				<input class="form-control" placeholder="Target Mahasiswa Baru" name="target_mhs_baru" type="number" id="target_mhs_baru" min="0" required='required' value="{{ $setting -> target_mhs_baru ?? '0' }}">
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('calon_ikut_seleksi', 'Pendaftar Ikut Seleksi:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-1">
				<input class="form-control" placeholder="Pendaftar Ikut Seleksi" name="calon_ikut_seleksi" type="number" id="calon_ikut_seleksi" min="0" required='required' value="{{ $setting -> calon_ikut_seleksi ?? '0' }}">
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('calon_lulus_seleksi', 'Pendaftar Lulus Seleksi:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-1">
				<input placeholder="Pendaftar Lulus Seleksi" name="calon_lulus_seleksi" id="calon_lulus_seleksi" value="{{ $setting -> calon_lulus_seleksi ?? '0' }}" class="form-control" type="number" min="0" required='required'>
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('daftar_sbg_mhs', 'Daftar Ulang:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-1">
				<input placeholder="Daftar Ulang" name="daftar_sbg_mhs" id="daftar_sbg_mhs" value="{{ $setting -> daftar_sbg_mhs ?? '0' }}" class="form-control" type="number" min="0" required='required'>
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('pst_undur_diri', 'Mengundurkan Diri:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-1">
				<input placeholder="Mengundurkan Diri" name="pst_undur_diri" id="pst_undur_diri" value="{{ $setting -> pst_undur_diri ?? '0' }}" class="form-control" type="number" min="0" required='required'>
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('jml_mgu_kul', 'Jumlah Minggu Pertemuan:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-1">
				<input placeholder="Jumlah Minggu Pertemuan" name="jml_mgu_kul" id="jml_mgu_kul" value="{{ $setting -> jml_mgu_kul ?? '0' }}" class="form-control" type="number" min="0" required='required'>
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