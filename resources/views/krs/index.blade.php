@extends('app')

@section('title')
Kartu Rencana Studi
@endsection

@push('styles')
<style>
	#preview{
	width: 166px;
	height: 220px;
	padding: 5px;
	margin: 15px auto;
	border: 1px solid #999;
	position: relative;
	overflow: hidden;
	}
	
	#preview img {
	max-width: 100%;
	max-height: 100%;
	width: auto;
	height: auto;
	position: absolute;
	left: 50%;
	top: 50%;
	-webkit-transform: translate(-50%, -50%);
	-moz-transform: translate(-50%, -50%);
	-ms-transform: translate(-50%, -50%);
	transform: translate(-50%, -50%);
	}
	
	
	.status{
	width: 100%;
	text-align: center;
	margin-bottom: 10px;
	}
	.sidebar-menu-small h5{
	text-align: center;
	background-color: #023355;
	color: white;
	padding: 5px;
	}
	.sidebar-menu-small {
    list-style: none;
    margin: 0;
    padding: 0
	}
	.sidebar-menu-small > li {
    position: relative;
    margin: 0;
    padding: 0
	}
	.sidebar-menu-small > li > a {
    padding: 5px 2px 5px 12px;
    display: block
	}
	.sidebar-menu-small > li > a > .fa{
    width: 20px
	}
	
	.sidebar-menu-small > li > a {
    border-left: 3px solid transparent;
	color: #120101;
	border-bottom: 1px solid #bbb;
	}
	.sidebar-menu-small > li:hover > a,
	.sidebar-menu-small > li.active > a {
    color: #3c8dbc;
    background: #f5f9fc;
    border-left-color: #3c8dbc
	}
	
	.checkbox{
	margin: 0px !important;
	padding-top: 0px !important;
	}
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Kartu Rencana Studi</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Kartu Rencana Studi</li>
	</ol>
</section>
@endsection


@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Data Mahasiswa</h3>
	</div>
	<div class="box-body">
		<table>
			<tr>
				<th width="14%">Nama</th><td>:&nbsp;</td><td width="40%">{{ $mhs -> nama }}</td>
				<th width="29%">NIM</th><td>:&nbsp;</td><td>{{ $mhs -> NIM }}</td>
			</tr>
			<tr>
				<th>PRODI</th><td>:&nbsp;</td><td>{{ $mhs -> prodi -> nama }}</td>
				<th>Program</th><td>:&nbsp;</td><td>{{ $mhs -> kelas -> nama }}</td>
			</tr>
			<tr>
				<th>Semester</th><td>:&nbsp;</td><td>{{ $mhs -> semesterMhs }}</td>
				<th>Tahun Akademik</th><td>:&nbsp;</td><td>{{ $tapel -> nama }}</td>
			</tr>
			<tr>
				<th>Dosen PA</th><td>:&nbsp;</td><td>{{ $mhs -> dosenwali -> nama }}</td>
			</tr>
			<tr>
				<th>Status KRS</th><td>:&nbsp;</td>
				<td>
					@if($status -> approved == 'y')
					<span class="label label-success">Disetujui</span>
					@else
					<span class="label label-danger"><i class="fa fa-times"></i> Belum Validasi</span>
					@endif
				</td>
				<th>Batas akhir pengisian KRS</th><td>:&nbsp;</td><td>{{ formatTanggal($tapel -> selesaiKrs) }}</td>
			</tr>
		</table>
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Kartu Rencana Studi</h3>
	</div>
	<div class="box-body">
		@if(!count($krs))
		@if(!$open)
		<div class="callout callout-danger">
			<h4>Error</h4>
			Waktu pengisian KRS Online sudah habis. Hubungi bagian Akademik jika anda belum melakukan KRS Online.
		</div>
		@else
		<p class="text-muted">Belum ada data Mata Kuliah. Klik <a href="{{ url('/tawaran') }}" class="btn btn-info btn-xs btn-flat" title="Input Data"><i class="fa fa-plus"></i> TAWARAN MATA KULIAH</a> untuk memilih Mata Kuliah yang diinginkan</p>
		@endif
		@else
		<?php $c=1; ?>
		{!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['krs.destroy']]) !!}
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="20px">No</th>
					<th>Mata Kuliah</th>
					<th>PROGRAM KELAS</th>
					<th>SEMESTER</th>
					<th>SKS</th>
					<th>KELAS</th>
					<th>DOSEN PENGAMPU</th>
					<th>JADWAL</th>
					<th>RUANG</th>
					<th>Kapasitas Kelas</th>
					<th>Jumlah Peserta</th>
					
				</tr>
			</thead>
			<tbody>
				@foreach($krs as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>
						@if($open)
						<div class="checkbox"><label>{!! Form::checkbox('matkul_tapel_id[]', $g -> mtid) !!} {{ $g -> nama_matkul }} ({{ $g -> kode }})</label></div>
						@else
						{{ $g -> nama_matkul }} ({{ $g -> kode }})
						@endif
					</td>
					<td>{{ $g -> program }} </td>
					<td>{{ $mhs -> semesterMhs }}</td>
					<td>{{ $g -> sks }}</td>
					<td>{{ $mhs -> semesterMhs }} {{ $g -> kelas2 }}</td>
					<td>{{ $g -> dosen }}</td>
					<td>
						@if(isset(config('custom.hari')[$g -> hari])) {{ config('custom.hari')[$g -> hari] }}
						@else
						-
						@endif
						@if($g -> jam_mulai != '')
						,
						@endif
						{{ $g -> jam_mulai ?? '' }}
						@if($g -> jam_mulai != '')
						- 
						@endif
						{{ $g -> jam_selesai ?? '' }}
					</td>
					<td>{{ $g -> ruangan }}</td>
					<td>{{ $g -> kuota }}</td>
					<td>{{ $g -> peserta }}</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
		@if($open)
		<a href="{{ url('/tawaran') }}" class="btn btn-primary btn-flat" title="Input Data"><i class="fa fa-plus"></i> Tambah</a>
		{!! Form::hidden('mahasiswa_id', $mhs -> id) !!}
		{!! Form::hidden('krs_id', $krs[0] -> krs_id) !!}
		
		@if($status -> approved != 'y')
		<button class="btn btn-danger btn-flat" type="submit"><i class="fa fa-trash"></i> Hapus</button>
		@endif 
		
		@endif 
		<a href="{{ url('/krs/print') }}" class="btn btn-warning btn-flat" target="_blank"><i class="fa fa-print"></i> Cetak</a>
		{!! Form::close() !!}
		@endif
	</div>
</div>
@endsection																							