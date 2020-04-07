@extends('app')

@section('title')
Video Conference Baru
@endsection

@section('header')
<section class="content-header">
	<h1>
		Video Conference untuk Kelas Kuliah {{ $kelas -> kurikulum -> matkul -> nama }}
		<small>Baru</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/matkul/tapel') }}"> Kelas Kuliah</a></li>
		<li><a href="{{ url('/matkul/tapel/' . $kelas -> id . '/meeting') }}"> Video Conference untuk Kelas Kuliah {{ $kelas -> kurikulum ->  matkul -> nama }}</a></li>
		<li class="active">Baru</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Video Conference</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\MatkulTapelMeeting, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['matkul.tapel.meeting.store', $kelas -> id]]) !!}
		@include('matkul/tapel/meeting/partials/_form', ['btn_type' => 'btn-primary'])
		{!! Form::close() !!}
	</div>
</div>
@endsection