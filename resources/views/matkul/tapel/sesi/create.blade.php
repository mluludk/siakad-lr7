<?php
$active = 'akt';
$id = $kelas->id;
?>
@extends('matkul.tapel.sesi.layout')

@section('title')
Input Sesi Pembelajaran Kelas Kuliah
@endsection

@section('tengah')
{!! Form::model(new Siakad\SesiPembelajaran, ['id' => 'post-form', 'class' => 'form', 'role' => 'form', 'route' => ['matkul.tapel.sesi.store', $kelas -> id]]) !!}
<div class="f-box-row">
	<div class="f-box-side" style="flex-grow:3;">
		<h4>Input Sesi Pembelajaran</h4>
	</div>
</div>

<div class="f-box">
	<div class="f-box-body">
		@include('matkul/tapel/sesi/partials/_form', ['btn_type' => 'btn-primary'])
	</div>
</div>

<div class="f-box-side pull-left">
	<a href="{{ route('matkul.tapel.sesi.index', $kelas -> id)}}" class="btn btn-default btn-flat btn-lg">
		<i class="fa fa-arrow-left"></i> Kembali
	</a>
</div>
{!! Form::close() !!}
@endsection