@extends('app')

@section('title')
e-Tugas
@endsection

@section('header')
<section class="content-header">
	<h1>
		e-Tugas
		<small>Daftar</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar e-Tugas</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar e-Tugas</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="30px">No</th>
					<th>Judul</th>
					<th>Mata Kuliah</th>
					<th>Dosen</th>
					<th>Jenis</th>
					<th>Deskripsi</th>
					<th>Status</th>
					<th>Nilai</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$tugas->count())
				<tr>
					<td colspan="9" align="center">Belum ada data</td>
				</tr>
				@else
				<?php $c=1; ?>
				@foreach($tugas as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> judul }}</td>
					<td>{{ $g -> matkul }} ({{ $g -> kode }})</td>
					<td>{{ formatTimDosen($g -> perkuliahan -> tim_dosen) }}</td>
					<td>
						@if($g -> jenis_tugas == 1) <i class="fa fa-upload"></i>
						@elseif($g -> jenis_tugas == 2) <i class="fa fa-file-text-o"></i>
						@elseif($g -> jenis_tugas == 3) <i class="fa fa-check-square"></i>
						@endif
						{{ $jenis[$g -> jenis_tugas] }}
					</td>
					<td>{!! $g -> keterangan !!}</td>
					<td>
						@if($g -> status == 1)
						<span class="label label-info label-flat">Dikirim</span>
						@elseif($g -> status == 2)
						<span class="label label-warning label-flat">Diperiksa</span>
						@elseif($g -> status == 3)
						<span class="label label-danger label-flat">Perbaikan</span>
						@elseif($g -> status == 4)
						<span class="label label-success label-flat">Selesai</span>
						@else
						<span class="label label-default label-flat">Belum</span>
						@endif
					</td>
					<td>{{ $g -> nilai }}</td>
					<td>
						<a href="{{ route('mahasiswa.tugas.detail2.index', $g -> id) }}" class="btn btn-info btn-flat btn-xs" title="Detail Tugas"><i class="fa fa-search"></i> Detail</a>
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
@endsection																												