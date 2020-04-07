@extends('app')

@section('title')
Tambah Pegawai Non Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pegawai Non Dosen
		<small>Input Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pegawai') }}"> Non Dosen</a></li>
		<li class="active">Input Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Pegawai Non Dosen</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-3">
				{!! Form::open(['url' => url('/upload/image'), 'class' => 'form-inline', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload']) !!}
				@include('_partials/_foto', ['default_image' => 'teacher.png'])
				{!! Form::close() !!}
			</div>
			<div class="col-sm-9">
				{!! Form::model(new Siakad\Pegawai, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['pegawai.store']]) !!}
				@include('pegawai/partials/_form', ['submit_text' => 'Simpan'])
				{!! Form::hidden('foto', null, array('id' => 'foto')) !!}
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-10">
						<button class="btn btn-primary btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
					</div>		
				</div>	
				
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection