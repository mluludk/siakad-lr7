@extends('app')

@section('title')
Periksa Pembaruan {{ $config['app']['abbr'] }} {{ htmlspecialchars_decode($config['app']['title'], ENT_QUOTES) }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pembaruan
		<small>Periksa Pembaruan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Periksa Pembaruan</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Periksa Pembaruan</h3>
	</div>
	<div class="box-body">
		@if($result -> success == 0)
		<p>
			Versi Aplikasi saat ini ({{ $config['app']['version'] }} rev.{{ $config['app']['version_timestamp'] }}) adalah merupakan versi terbaru. 
			Belum ada pembaruan untuk Aplikasi {{ $config['app']['abbr'] }} {{ htmlspecialchars_decode($config['app']['title'], ENT_QUOTES) }}
		</p>
		@if(isset($result -> message))
		<p>{{ $result -> message }}</p>
		@endif
		@else
		<address>
			<strong>Versi saat ini:</strong> {{ $config['app']['version'] }} rev.{{ $config['app']['version_timestamp'] }} <br>
			<strong>Versi terbaru:</strong> {{ $config['app']['version'] }} rev.{{ $result -> version }} <br>
		</address>
		@if(env('APP_ENV') == 'local')
			<label class="label label-danger">local</label>
		@else
		<p>Klik tombol dibawah untuk melakukan pembaruan</p>
		<a href="{{ route('patch.process', [$result -> version, env('PATCHER_TOKEN')]) }}" class="btn btn-success btn-flat"><i class="fa fa-download"></i> Unduh Pembaruan </a>		
		@endif
		@endif
	</div>
	</div>
	@endsection	