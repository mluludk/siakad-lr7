@extends('app')

@section('title')
Data Jurnal Perkuliahan
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Buat Jurnal Perkuliahan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kelaskuliah') }}"> Mengajar Kelas</a></li>
		<li><a href="{{ route('matkul.tapel.jurnal.index', $matkul_tapel_id) }}"> Jurnal Perkuliahan</a></li>
		<li class="active"> Buat Jurnal Perkuliahan</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Data Mata Kuliah</h3>
		<div class="box-tools">
			<a href="{{ url('/kelaskuliah/' . $matkul_tapel_id . '/peserta') }}" class='btn btn-primary btn-xs btn-flat' title='Peserta'><i class='fa fa-group'></i></a>
			<a href="{{ url('/kelaskuliah/' . $matkul_tapel_id . '/absensi') }}" class='btn btn-danger btn-xs btn-flat' title='Absensi'><i class='fa fa-font'></i></a>
			<a href="{{ url('/kelaskuliah/' . $matkul_tapel_id . '/nilai') }}" class='btn btn-success btn-xs btn-flat' title='Nilai'><i class='fa fa-bar-chart'></i></a>
		</div>
	</div>
	<div class="box-body">	
		<table width="100%">
			<tr>
				<th width="20%">Matakuliah & Semester</th><th width="2%">:</th><td width="30%">{{ $data -> matkul }} ({{ $data -> kd }}) ({{ $data -> semester }})</td>
				<th width="20%">Dosen</th><th width="2%">:</th><td>{{ $data -> dosen }}</td>
			</tr>
			<tr>
				<th>Program & Kelas</th><th>:</th><td>{{ $data -> program }} @if(isset($data -> kelas)) ({{ $data -> kelas }})@endif</td>
				<th>PRODI</th><th>:</th><td>{{ $data -> prodi }} ({{ $data -> singkatan }})</td>
			</tr>
			<tr>
				<th>Jadwal & Ruang</th><th>:</th><td>@if(isset($data -> hari)){{ config('custom.hari')[$data -> hari] }}, {{ $data -> jam_mulai }} - {{ $data -> jam_selesai }} ({{ $data -> ruang }})@else<span class="text-muted">Belum ada jadwal</span>@endif</td>
				<th>Tahun Akademik</th><th>:</th><td>{{ $data -> ta }}</td>
			</tr>
			<tr>
				<th>Jumlah Mahasiswa</th><th>:</th><td>{{ $anggota -> count() }}</td>
				<th></th><th></th><td></td>
			</tr>
		</table>
	</div>
</div>
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Buat Jurnal Perkuliahan</h3>
		<div class="box-tools">
			<a href="{{ url('/kelaskuliah/' . $matkul_tapel_id . '/jurnal') }}" class='btn btn-warning btn-xs btn-flat' title='Jurnal'><i class='fa fa-book'></i></a>
		</div>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\Jurnal, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['matkul.tapel.jurnal.store', $matkul_tapel_id], 'files' => true]) !!}
		@include('matkul/tapel/jurnal/partials/_form')
		{!! Form::close() !!}
	</div>
</div>
@endsection