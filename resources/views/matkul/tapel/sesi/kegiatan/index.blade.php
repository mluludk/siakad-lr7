<?php
	$active = null;
	$id = $kelas -> id;
	$sesi_side = $kelas -> sesi;
?>
@extends('matkul.tapel.sesi.layout')

@section('tengah')
<div class="f-col-2">
	<div class="f-box-row">
		<div class="f-box-side" style="flex-grow:3;">
			<h4><i class="fa fa-list"></i> Sesi ke {{ $sesi -> sesi_ke }} - {{ $sesi -> judul }}</h4>
		</div>
	</div>
	
	<div class="f-box">
		<div class="f-box-body">
			<h4>Topik</h4>
			<p>{{ $sesi -> judul }}</p>
			<h4>Tujuan Pembelajaran</h4>
			<p>{!! $sesi -> tujuan !!}</p>
		</div>
	</div>
	
	<div class="f-box-row">
		<div class="f-box-side" style="flex-grow:3;">
			<h4>Materi Pembelajaran</h4>
		</div>
		<div class="f-box-side" style="flex-grow:1;  text-align: right; padding-top: 11px;">
			<div class="dropdown">
				<button class="btn btn-success btn-xs btn-flat dropdown-toggle" type="button" id="dm1" data-toggle="dropdown" aria-haspopup="true" 
				aria-expanded="true">
					<i class="fa fa-plus"></i> Tambah
				</button>
				<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dm1">
					@foreach($jenis as $k => $v)
					<li><a href="{{ route('matkul.tapel.sesi.kegiatan.create', [$kelas -> id, $sesi -> id, $k]) }}"> {{ $v }}</a></li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
	
	@if($kegiatan -> count())
	@foreach($kegiatan as $k)
	<div class="f-box">
		<div class="f-box-row">
			<div class="f-box-side" style="width: 58px; padding: 10px;">
				<button class="btn btn-default"><i class="fa fa-navicon"></i></button>
			</div>
			<div class="f-box-side" style="width: 100%; border-left: 1px solid #ddd;">
				<div class="f-box-body">
					<h4>{{ $k -> topik }} <small><time class="timeago" datetime="{{ $k -> created_at }}"></time></small></h4>
					<p>
						<span class="text-muted">{{ $jenis[$k -> jenis] ?? '-' }}</span>
					</p>
					<a href="{{ route('matkul.tapel.sesi.kegiatan.show', [$kelas -> id, $sesi -> id, $k -> id])}}" class="btn btn-link btn-block">Detail Materi</a>
				</div>
			</div>
			<div class="f-box-side" style="width: 58px; padding: 10px;">
				<div class="dropdown">
					<button class="btn btn-default dropdown-toggle" type="button" id="dm1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						<i class="fa fa-ellipsis-v"></i>
					</button>
					<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dm1">
						<li><a href="{{ route('matkul.tapel.sesi.kegiatan.duplicate', [$kelas -> id, $sesi -> id, $k -> jenis]) }}" onclick="notImplemented();return false;"><i class="fa fa-paste"></i> Duplikat Sesi</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="{{ route('matkul.tapel.sesi.kegiatan.delete', [$kelas -> id, $sesi -> id, $k -> id]) }}" class="has-confirmation"><span class="text-danger"><i class="fa fa-trash"></i> Hapus</span></a></li>
					</ul>
				</div>
			</div>
			</div>
		</div>
		@endforeach
		@else
		<div class="f-box">
			<div class="f-box-body">
				Belum ada Materi.
			</div>
		</div>
		@endif
	</div>
	@endsection
	
	@push('scripts')
	<script src="{{ asset('/js/jquery.timeago.js') }}" type="text/javascript"></script>
	<script>
		jQuery.timeago.settings.strings = {
		prefixAgo: null,
		prefixFromNow: null,
		suffixAgo: "yang lalu",
		suffixFromNow: "dari sekarang",
		seconds: "kurang dari semenit",
		minute: "sekitar satu menit",
		minutes: "%d menit",
		hour: "sekitar sejam",
		hours: "sekitar %d jam",
		day: "sehari",
		days: "%d hari",
		month: "sekitar sebulan",
		months: "%d bulan",
		year: "sekitar setahun",
		years: "%d tahun"
		};
		
		$(function () {
			$("time.timeago").timeago();
		});
	</script>
	@endpush
