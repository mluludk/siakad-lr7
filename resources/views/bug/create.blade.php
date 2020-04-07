@extends('app')

@section('title')
Laporan Bug Baru
@endsection

@section('header')
<section class="content-header">
	<h1>
		Laporan Bug
		<small>Tambah Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/report') }}"> Laporan Bug</a></li>
		<li class="active">Tambah Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Laporan Bug Baru</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\BugReport, ['id' => 'frm', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true, 'route' => ['report.store']]) !!}
		@include('bug/partials/_form')
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-9">
				<button class="btn btn-primary btn-flat btn-primary" type="submit" id="post"><i class="fa fa-bug"></i> Simpan</button>
			</div>		
		</div>
		{!! Form::close() !!}
	</div>
</div>
@endsection