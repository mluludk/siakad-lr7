@extends('app')

@section('title')
Ubah data Pegawai Non Dosen - {{ $pegawai -> nama ?? 'Invalid'}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pegawai Non Dosen
		<small>Ubah Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pegawai') }}"> Pegawai Non Dosen</a></li>
		<li><a href="{{ route('pegawai.show', $pegawai -> id) }}"> {{ $pegawai -> nama }}</a></li>
		<li class="active">Ubah Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-body">
		<div class="row">
			<div class="col-sm-3 image_upload">
				<div id="validation-errors"></div>
				{!! Form::open(['url' => url('upload/image'), 'class' => 'form-inline', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload']) !!}
				@include('_partials/_foto', ['foto' => $pegawai -> foto, 'default_image' => 'teacher.png'])
				{!! Form::close() !!}
			</div>
			<div class="col-sm-9">
				{!! Form::model($pegawai, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['pegawai.update', $pegawai->id]]) !!}
				@include('pegawai/partials/_form')
				{!! Form::hidden('foto', null, array('id' => 'foto')) !!}
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-10">
						<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
					</div>		
				</div>	
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection