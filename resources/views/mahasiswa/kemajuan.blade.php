@extends('app')

@section('title')
Laporan Kemajuan Belajar - {{ $mahasiswa -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Kemajuan Belajar</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li><a href="{{ route('mahasiswa.show', $mahasiswa -> id) }}"> {{ ucwords(strtolower($mahasiswa -> nama)) }}</a></li>
		<li class="active">Kemajuan Belajar</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Data Mahasiswa</h3>
		<div class="box-tools">
			@include('mahasiswa.partials._menu', ['role_id' => \Auth::user() -> role_id, 'mahasiswa' => $mahasiswa])
		</div>
	</div>
	<div class="box-body">
		<table>
			<tr>
				<th>Nama</th><td>:&nbsp;</td><td>{{ $mahasiswa -> nama }}</td>
			</tr>
			<tr>
				<th>NIM</th><td>:&nbsp;</td><td>{{ $mahasiswa -> NIM }}</td>
			</tr>
			<tr>
				<th>PRODI</th><td>:&nbsp;</td><td>{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }}</td>
			</tr>
			<tr>
				<th>Program</th><td>:&nbsp;</td><td>{{ $mahasiswa -> kelas -> nama }}</td>
			</tr>
			<tr>
				<th>Semester</th><td>:&nbsp;</td><td>{{ $mahasiswa -> semesterMhs }}</td>
			</tr>
		</table>
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Kemajuan Belajar</h3>
				<div class="box-tools">
					<!--
						<a href="" class="btn btn-success btn-xs btn-flat" title="Print Transkrip"><i class="fa fa-print"></i> Cetak Transkrip</a>
					-->
				</div>
			</div>
			<div class="box-body">
				@if(count($matkul) < 1)
				<p class="text-muted">Belum ada data</p>
				@else
				<?php $c = $d = 0;?>
				<table class="table table-bordered table-striped">
					<thead>
						<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
							<th>No.</th>
							<th>Kode MK</th>
							<th>Nama MK</th>
							<th>SKS</th>
							<th>Semester</th>
							<th>Ditempuh</th>
							<th>Nilai</th>
						</tr>
					</thead>
					<tbody>
						@foreach($matkul as $k => $g)
						<?php
							$c++; 
							$sudah = false;
							if(isset($ditempuh[$k])) { $sudah = true; $d++;}
							$status = $sudah ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
							$nilai = $sudah ? $ditempuh[$k]['nilai'] : '-';
						?>
						<tr>
							<td>{{ $c }}</td>
							<td>{{ $g['kode'] }}</td>
							<td>{{ $g['nama'] }}</td>
							<td>{{ $g['sks'] }}</td>
							<td>{{ $g['semester'] }}</td>
							<td>{!! $status !!}</td>
							<td>{{ $nilai }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				@endif
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Prosentase</h3>
			</div>
			<div class="box-body">
				
				<div class="progress">
					<?php
						$s = $d > 0 ? $d/$c * 100 : 0;
						if($s < 40)
						$class = 'danger';
						elseif($s >=40 AND $s < 75)
						$class = 'warning';	
						elseif($s >= 75 and $s < 100)
						$class="success";
						else
						$class="info";
					?>
					<div class="progress-bar progress-bar-{{ $class }}" role="progressbar" aria-valuenow="{{ $s }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $s }}%">
						<span class="">{{ round($s, 1) }}%</span>
					</div>
				</div>
				
				<table>
					<tr>
						<th>Total Mata Kuliah</th><td>:&nbsp;</td><td>{{ $c }}</td>
					</tr>
					<tr>
						<th><span class="text-success">Sudah ditempuh</span></th><td><span class="text-success">:&nbsp;</span></td><td><span class="text-success">{{ $d }}</span></td>
					</tr>
					<tr>
						<th>Prosentase</th><td>:&nbsp;</td><td><?php $s = $d > 0 ? $d/$c * 100 : 0; ?>{{ round($s, 1) }}%</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection																