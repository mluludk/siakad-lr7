@extends('app')

@section('title')
Aktifitas Mengajar
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Aktifitas Mengajar</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Aktifitas Mengajar</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Aktifitas Mengajar</h3>
	</div>
	<div class="box-body">
		<?php $c=1; $total_sks=0;?>
		<table class="table table-bordered table-hover table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No</th>
					<th>Periode</th>
					<th>Mata Kuliah</th>
					<th>Ket</th>
					<th>Prodi</th>
					<th>Program</th>
					<th>Smt</th>
					<th>SKS</th>
					<th>Mhs</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$data -> count())
				<td colspan="10" align="center">Belum ada data</td>
				@else
				@foreach($data as $mk)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $mk -> ta }}</td>
					<td>{{ $mk -> matkul}} ({{ $mk -> kode }})</td>
					<td>{{ $mk -> keterangan }}</td>
					<td>{{ $mk -> strata }} {{ $mk -> prodi }}</td>
					<td>{{ $mk -> program -> nama }}</td>
					<td>{{ $mk -> semester }}</td>
					<td>{{ $mk -> sks }}</td>
					<td>{{ $mk -> mahasiswa -> count() }}</td>
					<td>
						<a href='{{ url("/kelaskuliah/" . $mk -> id ."/peserta") }}' class='btn btn-xs btn-flat btn-primary' title='Peserta'><i class='fa fa-group'></i></a>
						<a href='{{ url("/kelaskuliah/" . $mk -> id ."/jurnal") }}' class='btn btn-xs btn-flat btn-warning' title='Jurnal'><i class='fa fa-book'></i></a>
						<a href='{{ url("/kelaskuliah/" . $mk -> id ."/absensi") }}' class='btn btn-xs btn-flat btn-danger' title='Absensi'><i class='fa fa-font'></i></a>
						<a href='{{ url("/matkul/tapel/" . $mk -> id ."/nilai") }}' class='btn btn-xs btn-flat btn-success' title='Nilai'><i class='fa fa-bar-chart'></i></a>
					</td>
				</tr>
				<?php $c++; $total_sks += $mk -> sks; ?>
				@endforeach
				<tr>
					<td colspan="7" align="right"><strong>TOTAL SKS</strong></td>
					<td colspan="3"><strong>{{ $total_sks }}</strong></td>
				</tr>
				@endif
			</tbody>
		</table>
	</div>
	</div>
		@endsection																					