<?php
$active = null;
$id = $kelas->id;
$sesi_side = $kelas->sesi;
?>
@extends('matkul.tapel.sesi.layout')

@section('tengah')
{!! Form::model($kegiatan, ['method' => 'PATCH', 'id' => 'post-form', 'files' => true, 'role' => 'form', 'route' => ['matkul.tapel.sesi.kegiatan.update', $kelas -> id, $sesi -> id, $kegiatan -> id]]) !!}
<div class="f-box-row">
	<div class="f-box-side" style="flex-grow:3;">
		<h4><i class="fa fa-list"></i> Sesi ke {{ $sesi -> sesi_ke }}</h4>
	</div>
</div>

<div class="f-box">
	<div class="f-box-body">
		<div class="f-box-side pull-left">
			<h4>Ubah {{ $jenis }}</h4>
		</div>

		<div class="f-box-side pull-right">
			<button class="btn btn-flat btn-warning" type="button" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
		</div>
		<div class="clearfix"></div>
		@include('matkul/tapel/sesi/kegiatan/partials/_form')
	</div>
</div>

@if($jenis_id == 2)
@include('matkul/tapel/sesi/kegiatan/pertanyaan/index')
@endif

@if($jenis_id == 3)
@include('matkul/tapel/sesi/kegiatan/tugas/index')
@endif

@include('matkul/tapel/sesi/kegiatan/partials/_catatan', ['catatan' => $kegiatan -> catatan ?? ''])
<div class="f-box-side pull-left">
	<a href="{{ route('matkul.tapel.sesi.kegiatan.index', [$kelas -> id, $sesi -> id])}}" class="btn btn-default btn-flat btn-lg">
		<i class="fa fa-arrow-left"></i> Kembali
	</a>
</div>

{!! Form::close() !!}
@endsection