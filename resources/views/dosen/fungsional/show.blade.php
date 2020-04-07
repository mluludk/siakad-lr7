@extends('app')

@section('title')
Pendidikan Dosen | {{ $pendidikan -> dosen}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pendidikan Dosen
		<small>{{ $pendidikan -> dosen }}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen/pendidikan') }}"> Pendidikan Dosen</a></li>
		<li class="active">{{ $pendidikan -> dosen }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Detail Pendidikan Dosen</h3>
		<div class="box-tool pull-right">
			<a href="{{ route('dosen.pendidikan.edit', [$pendidikan -> dosen_id, $pendidikan -> id]) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data pendidikan"><i class="fa fa-pencil-square-o"></i> Edit data pendidikan</a>
		</div>
	</div>
	<div class="box-body form-horizontal">
		<div class="form-group">
			<label class="col-sm-3 control-label">Tahun Ajaran:</label>
			<div class="col-sm-9">
				<p class="form-control-static">{{ $pendidikan -> tapel }}</p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Perguruan Tinggi:</label>
			<div class="col-sm-9">
				<p class="form-control-static">213222 - STAI Ma`had Aly Al-Hikam Malang</p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Dosen:</label>
			<div class="col-sm-9">
				<p class="form-control-static">{{ $pendidikan -> NIDN }} - {{ $pendidikan -> dosen }}</p>
			</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Program Studi:</label>
				<div class="col-sm-9">
					{{ $pendidikan -> prodi }}
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">No. Surat Tugas:</label>
				<div class="col-sm-9">
					<p class="form-control-static">{{ $pendidikan -> no_surat_tugas }}</p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Tanggal Surat Tugas:</label>
				<div class="col-sm-9">
					<p class="form-control-static">{{ $pendidikan -> tgl_surat_tugas }}</p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">TMT Surat Tugas:</label>
				<div class="col-sm-9">
					<p class="form-control-static">{{ $pendidikan -> tmt_surat_tugas }}</p>
				</div>
			</div>			
		</div>				
	</div>				
@endsection	