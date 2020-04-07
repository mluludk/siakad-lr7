@extends('app')

@section('title')
Video Conference untuk Kelas Kuliah {{ $kelas -> kurikulum -> matkul -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Video Conference
		<small>{{ $kelas -> kurikulum -> matkul -> nama }}</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/matkul/tapel') }}"> Kelas Kuliah</a></li>
		<li class="active">Video Conference untuk Kelas Kuliah {{ $kelas -> kurikulum ->  matkul -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Video Conference</h3>
		<div class="box-tools">
			<a href="{{ route('matkul.tapel.meeting.create', $kelas -> id) }}" class="btn btn-primary btn-xs btn-flat" title="Tambah Video Conference"><i class="fa fa-plus"></i> Tambah  Video Conference</a>
		</div>
	</div>
	<div class="box-body">
		<?php $c=1; ?>
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No</th>
					<th>Topik</th>
					<th>Mulai</th>
					<th>Join</th>
				</tr>
			</thead>
			<tbody>
				@if(!$kelas -> meeting ->count())
				<tr>
					<td colspan="4">Belum ada data</td>
				</tr>
				@else
				@foreach($kelas -> meeting as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> topic }}</td>
					<td>
					@if($g -> started == 'n')
						<a href="{{ route('meeting.start', $g -> id) }}" class="btn btn-info btn-flat" title="Klik untuk memulai Conference" target="_blank"><i class="fa fa-video-camera"></i> Mulai Conference</a>
					@else
						<a href="" class="btn btn-info btn-flat" disabled="disabled" title="Conference sudah dimulai"><i class="fa fa-video-camera"></i> Mulai Conference</a>
					@endif
					</td>
					<td><a href="{{ $g -> join_url }}" class="btn btn-success btn-flat"><i class="fa fa-video-camera"></i> Join</a></td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
@endsection												