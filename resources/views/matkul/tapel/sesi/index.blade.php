<?php
	$active = 'akt';
	$id = $kelas -> id;
?>
@extends('matkul.tapel.sesi.layout')

@section('tengah')
<div class="f-col-2">
	<div class="f-box-row">
		<div class="f-box-side" style="flex-grow:3;">
			<h4><i class="fa fa-list"></i> Sesi Pembelajaran</h4>
		</div>
		<div class="f-box-side" style="flex-grow:1;  text-align: right; padding-top: 11px;">
			<a href="{{ route('matkul.tapel.sesi.create', $kelas -> id) }}" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus"></i> Tambah</a>&nbsp;&nbsp;
			<a href="#" class="btn btn-info btn-xs btn-flat"><i class="fa fa-cog"></i> Pengaturan</a>
		</div>
	</div>		
	@if($sesip -> count())
	@php
	$hari = config('custom.hari');
	@endphp
	@foreach($sesip as $s)
	<div class="f-box">
		<div class="f-box-row">
			<div class="f-box-side" style="width: 58px; padding: 10px;">
				<button class="btn btn-default"><i class="fa fa-navicon"></i></button>
			</div>
			<div class="f-box-side" style="width: 100%; border-left: 1px solid #ddd;">
				<h4 class="box-title-half"><a href="{{ route('matkul.tapel.sesi.kegiatan.index', [$kelas -> id, $s -> id]) }}" style="color: white;">Sesi ke {{ $s -> sesi_ke }}</a></h4>
				<div class="f-box-body">
					<h4><a href="{{ route('matkul.tapel.sesi.kegiatan.index', [$kelas -> id, $s -> id]) }}">{{ $s -> judul }}</a></h4>
					@foreach($kelas -> jadwal as $j)
					<div class="text-muted">{{ $hari[$j -> hari] }}, 06 April 2020</div>
					<span class="text-muted">
						{{ $j -> jam_mulai }} - {{ $j -> jam_selesai }} &nbsp;&nbsp;
						<i class="fa fa-building"></i>&nbsp;&nbsp;{{ $j -> ruang -> nama }}
					</span>
					@endforeach
				</div>
			</div>
			<div class="f-box-side" style="width: 58px; padding: 10px;">
				<div class="dropdown">
					<button class="btn btn-default dropdown-toggle" type="button" id="dm1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						<i class="fa fa-ellipsis-v"></i>
					</button>
					<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dm1">
						<li><a href="{{ route('matkul.tapel.sesi.kegiatan.index', [$kelas -> id, $s -> id]) }}"><i class="fa fa-bookmark-o"></i> Materi</a></li>
						<li><a href="{{ route('matkul.tapel.sesi.edit', [$kelas -> id, $s -> id]) }}"><i class="fa fa-edit"></i> Ubah</a></li>
						<li><a href="{{ route('matkul.tapel.sesi.duplicate', [$kelas -> id, $s -> id]) }}" onclick="notImplemented();return false;"><i class="fa fa-paste"></i> Duplikat Sesi</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="{{ route('matkul.tapel.sesi.delete', [$kelas -> id, $s -> id]) }}" class="has-confirmation"><span class="text-danger"><i class="fa fa-trash"></i> Hapus</span></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	@endforeach
	@else
	<div class="f-box">
		<div class="f-box-body">
			Belum ada Sesi Pembelajaran
		</div>
	</div>
	@endif
</div>
@endsection
