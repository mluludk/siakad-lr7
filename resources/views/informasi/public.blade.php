@extends('app')

@section('title')
Informasi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pengumuman
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Pengumuman</li>
	</ol>
</section>
@endsection

@push('styles')
<style>
	em{
	display: block;
	margin-top: -6px;
	margin-bottom: 15px;
	font-size: 12px;
	}
</style>
@endpush

@section('content')
<div class="box">
	<div class="box-body">
		@if(!isset($data))
		<p class="text-muted">Data tidak ditemukan</p>
		@else
		<div class="media">
			<div class="media-body">
				<h3 class="media-heading">{{ $data -> judul }}</h3>
				<em>{{ formatTanggalWaktu($data -> updated_at) }} oleh {{ $data -> poster -> authable -> nama }}</em>
				{!! $data -> isi !!}
			</div>
		</div>
		@endif
	</div>
</div>
@endsection