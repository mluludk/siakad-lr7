<?php
	$active = 'akt';
	$id = $kelas -> id;
?>
@extends('matkul.tapel.sesi.layout')

@section('title')
Edit Sesi Pembelajaran
@endsection

@section('tengah')
{!! Form::model($sesi, ['id' => 'post-form', 'method' => 'PATCH', 'class' => 'form', 'role' => 'form', 'route' => ['matkul.tapel.sesi.update', $kelas->id, $sesi -> id]]) !!}
<div class="f-col-2">
	
	<div class="f-box-row">
		<div class="f-box-side" style="flex-grow:3;">
			<h4>Edit Data Sesi Pembelajaran</h4>
		</div>
	</div>
	
	<div class="f-box">
		<div class="f-box-body">	
			@include('matkul/tapel/sesi/partials/_form', ['btn_type' => 'btn-warning'])
		</div>
	</div>
	
	<div class="f-box-side pull-left">
		<a href="{{ route('matkul.tapel.sesi.index', $kelas -> id)}}" class="btn btn-default btn-flat btn-lg">
			<i class="fa fa-arrow-left"></i> Kembali
		</a>
	</div>
	
</div>
{!! Form::close() !!}
@endsection