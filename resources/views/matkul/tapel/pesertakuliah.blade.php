@extends('app')

@section('title')
Peserta Kuliah
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Peserta Kuliah</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kelaskuliah') }}"> Mengajar Kelas</a></li>
		<li class="active">Peserta Kuliah</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Data Mata Kuliah</h3>
		<div class="box-tools">	
			<a href='/kelaskuliah/{{ $matkul_tapel_id}}/jurnal' class='btn btn-warning btn-xs btn-flat' title='Jurnal'><i class='fa fa-book'></i></a>
			<a href='/kelaskuliah/{{ $matkul_tapel_id}}/absensi' class='btn btn-danger btn-xs btn-flat' title='Absensi'><i class='fa fa-font'></i></a>
			<a href='/matkul/tapel/{{ $matkul_tapel_id}}/nilai' class='btn btn-success btn-xs btn-flat' title='Nilai'><i class='fa fa-bar-chart'></i></a>
		</div>
	</div>
	<div class="box-body">	
		<table width="100%">
			<tr>
				<th width="20%">Matakuliah & Semester</th><th width="2%">:</th><td width="30%">{{ $data -> matkul }} ({{ $data -> kd }}) ({{ $data -> semester }})</td>
				<th width="20%">Dosen</th><th width="2%">:</th><td>{!! formatTimDosen($data -> tim_dosen) !!}</td>
			</tr>
			<tr>
				<th>Program & Kelas</th><th>:</th><td>{{ $data -> program }} @if(isset($data -> kelas)) ({{ $data -> semester }}{{ $data -> kelas }})@endif</td>
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
<?php $c = 1; $x = 0;?>

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Peserta Kuliah</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered table-hover" style="max-width: 500px">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="10px">No</th>
					<th width="150px">NIM</th>
					<th>Nama</th>
					<th width="30px">Nilai</th>
				</tr>
			</thead>
			<tbody>
				@if(!$anggota -> count())
				<td colspan="4" align="center">Belum ada data</td>
				@else
				@foreach($anggota as $mhs)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $mhs -> NIM }}</td>
					<td>{{ $mhs -> nama }}</td>
					<td>{{ $mhs -> nilai }}</td>
				</tr>
				<?php $c++;  $x++; ?>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
@endsection																													