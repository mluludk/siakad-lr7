@extends('app')

@section('title')
Pengumpulan Tugas Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Tugas Mahasiswa
		<small>Pengumpulan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa/tugas') }}"> Tugas Mahasiswa</a></li>
		<li class="active">Pengumpulan</li>
	</ol>
</section>
@endsection

@section('content')
<style>
	/* 	.pilihan{
	padding-left: 12px;
	} */
</style>
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Deskripsi Tugas Mahasiswa</h3>
	</div>
	<div class="box-body">
		<table width="100%">
			<tr>
				<th width="15%">Tahun Akademik</th><th width="2%">:</th><td width="30%">{{ $tugas -> tapel }}</td>
				<th width="15%">Mata Kuliah</th><th width="2%">:</th><td>{{ $tugas -> matkul }} ({{ $tugas -> kode }})</td>
			</tr>
			<tr>
				<th>Prodi</th><th>:</th><td>{{ $tugas -> strata }} {{ $tugas -> prodi }}</td>
				<th>Program</th><th>:</th><td>{{ $tugas -> program }} <strong>Semester</strong> {{ $tugas -> semester }}{{ $tugas -> kelas2 }}</td>
			</tr>
			<tr>
				<th>Judul Tugas</th><th>:</th><td>{{ $tugas -> judul }}</td>
				<th>Dosen</th><th>:</th><td>{{ formatTimDosen($tugas -> perkuliahan -> tim_dosen) }}</td>
			</tr>
			<tr>
				<th valign="top">Jenis Tugas</th><th valign="top">:</th><td valign="top">
					@if($tugas -> jenis_tugas == 1) <i class="fa fa-upload"></i>
					@elseif($tugas -> jenis_tugas == 2) <i class="fa fa-file-text-o"></i>
					@elseif($tugas -> jenis_tugas == 3) <i class="fa fa-check-square"></i>
					@endif
					{{ $jenis[$tugas -> jenis_tugas] }}
				</td>
				<!--<th valign="top">Deskripsi Tugas</th><th valign="top">:</th><td valign="top">{!! $tugas -> keterangan !!}</td> -->
			</tr>
			<tr>
				<th>Tanggal Tugas</th><th>:</th><td>{{ $tugas -> tanggal }}</td>
				<th>Batas Akhir Tugas</th><th>:</th><td>{{ $tugas -> batas }}</td>
			</tr>
			<tr>
				<th>Status Publikasi *</th><th>:</th><td>
					@if($tugas -> published == 'y') <span class="label label-success label-flat">Sudah</span>
					@else <span class="label label-danger label-flat">Belum</span>
					@endif
				</td>
				<th>Jenis Penilaian (bobot)</th><th>:</th>
				<td>
					@if($tugas -> jnilai == '__FINAL__') Akhir @else {{ $tugas -> jnilai }} @endif 
					({{ $tugas -> bobot }}%)
				</td>
			</tr>
		</table>
		<h3>DESKRIPSI TUGAS MAHASISWA</h3>
			<ol style="padding-left: 18px;">
			<td valign="top">{!! $tugas -> keterangan !!}</td>
	</div>
</div>
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Pengumpulan Tugas Mahasiswa</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="30px">No</th>
					<th>Nama</th>
					<th>NIM</th>
					<th>Prodi</th>
					<th>Semester</th>
					<th>Program</th>
					<th>Status Tugas</th>
					<th>Nilai</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php $c=1; ?>
				@if(!$hasil -> count())
				<tr>
					<td colspan="9" align="center">Belum ada data</td>
				</tr>
				@else
				@foreach($hasil as $h)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $h -> mahasiswa }}</td>
					<td>{{ $h -> NIM }}</td>
					<td>{{ $h -> strata }} {{ $h -> singkatan }}</td>
					<td>{{ $h -> semester }}</td>
					<td>{{ $h -> program }}</td>
					<td>
						@if($h -> status == 1)
						<span class="label label-info label-flat">Dikirim</span>
						@elseif($h -> status == 2)
						<span class="label label-warning label-flat">Diperiksa</span>
						@elseif($h -> status == 3)
						<span class="label label-danger label-flat">Perbaikan</span>
						@elseif($h -> status == 4)
						<span class="label label-success label-flat">Selesai</span>
						@else
						<span class="label label-default label-flat">Belum</span>
						@endif
					</td>
					<td>{{ $h -> nilai }}</td>
					<td>
						<a href="{{ route('mahasiswa.tugas.hasil.detail', [$tugas -> id, $h -> mahasiswa_id]) }}" class="btn btn-info btn-flat btn-xs" title="Detail Pengumpulan Tugas"><i class="fa fa-search"></i> Lihat</a>
						<a href="{{ route('mahasiswa.tugas.hasil.status', [$tugas -> id, $h -> mahasiswa_id, 3]) }}" class="btn btn-danger btn-flat btn-xs" title="Perbaikan"><i class="fa fa-wrench"></i> Perbaiki</a>
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