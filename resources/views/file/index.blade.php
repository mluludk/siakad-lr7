@extends('app')

@section('title')
Dokumen
@endsection

@section('header')
<section class="content-header">
	<h1>
		File
		<small>Daftar File</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar File</li>
	</ol>
</section>
@endsection

@push('styles')
<style>
	span.text-muted{
	font-size: 10px;
	}
</style>
@endpush

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar File</h3>
		<div class="box-tools">
			<a href="{{ url('/upload/file') }}" class="btn btn-danger btn-xs btn-flat" title="Upload File"><i class="fa fa-upload"></i></a>
		</div>
	</div>
	<div class="box-body">
		@if(!$files->count())
		<p class="text-muted">Belum ada file yang di-upload</p>
		@else
		<?php 
			$c = 1; 
			$user_role = \Auth::user() -> role_id;
			$user_id = \Auth::user() -> id;
		?>
		<table class="table table-bordered">
			<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
				<th>No.</th>
				<th>Tanggal</th>
				<th>Nama</th>
				<th>File</th>
			</tr>
			@foreach($files as $file)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ formatTanggal(substr($file -> created_at, 0, 10)) }}</td>
				<td>{{ $file -> nama }} <span class="text-muted">{{ $file -> ukuran }}</span></td>
				<td>
					<?php
						$akses = json_decode($file -> akses, true);
					?>
					@if(in_array($user_role, [1, 2]) ?? in_array($user_role, $akses) ?? $file -> user_id == $user_id)
					<a href="{{ url('/getfile/' . $file -> namafile) }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-download"></i> Download</a>
					@endif
					@if(in_array($user_role, [1, 2]) ?? $file -> user_id == $user_id)
					<a href="{{ url('/file/delete/'. $file -> id) }}" class="btn btn-danger btn-xs has-confirmation btn-flat" title="Hapus file"><i class="fa fa-trash-o"></i> Hapus</a>
					@endif
				</td>
			</tr>
			<?php $c++; ?>
			@endforeach
		</table>
		{!! $files -> render() !!}
		@endif
	</div>
</div>
@endsection																													