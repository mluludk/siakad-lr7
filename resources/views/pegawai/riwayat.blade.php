@extends('app')

@section('title')
Riwayat Penelitian Dosen {{ $dosen -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Penelitian</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen/') }}"> Dosen</a></li>
		<li><a href="{{ url('/dosen/' . $dosen -> id) }}"> {{ $dosen -> nama }}</a></li>
		<li class="active">Riwayat Penelitian</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Dosen</h3>
		<div class="box-tool pull-right">
			@include('dosen.partials._menu', ['dosen' => $dosen])
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-xs-12">
				<table width="100%">
					<tbody>
						<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
							<th width="20%">Nama</th><th width="2%">:</th><td width="30%">{{ $dosen -> nama }}</td>
							<th width="20%"></th><th width="2%"></th><td></td>
						</tr>
						<tr>
							<th>Tempat Lahir</th><th>:</th><td>{{ $dosen -> tmpLahir }}</td>
							<th>Tanggal Lahir</th><th>:</th><td>{{ $dosen -> tglLahir }}</td>
						</tr>
						<tr>
							<th>Jenis Kelamin</th><th>:</th><td>@if($dosen -> jenisKelamin == 'L') Laki-laki @else Perempuan @endif</td>
							<th>Agama</th><th>:</th><td>{{ config('custom.pilihan.agama')[$dosen -> agama] }}</td>
						</tr>
						<tr>
							<th>Status</th><th>:</th><td>{{ config('custom.pilihan.statusKepegawaian')[$dosen -> statusKepegawaian] }}</td>
							<th>NIDN</th><th>:</th><td>{{ $dosen -> NIDN }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>		
	</div>
</div>
<style>
.table>thead>tr>th {
    text-align: center;
    vertical-align: middle;
}
</style>
<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Riwayat Penelitian</h3>
		<div class="box-tools">
			<a href="{{ route('dosen.penelitian.create', $dosen -> id) }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i> Tambah Penelitian</a>
		</div>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th rowspan="2">No.</th>
					<th rowspan="2">Tahun</th>
					<th rowspan="2">Judul Penelitian</th>
					<th rowspan="2">Jenis Penelitian</th>
					<th rowspan="2">Ketua Penelitian</th>
					<th colspan="4">Nilai Sumber Dana (Rp)</th>
					<th rowspan="2"></th>
				</tr>
				<tr>
					<th>Mandiri</th>
					<th>Lembaga</th>
					<th>Hibah Nasional</th>
					<th>Hibah Internasional</th>
				</tr>
			</thead>
			<tbody>
				@if(!$penelitian -> count())
				<tr>
					<td colspan="10" align="center">Belum ada data penelitian</td>
				</tr>
				@else
				<?php 
					$c=1; 
				?>
				@foreach($penelitian as $b)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $b -> tahun }}</td>
					<td>{{ $b -> judul }}</td>
					<td>@if($b -> jenis == 1) Pribadi @else Kelompok @endif</td>
					<td>{{ $b -> ketua_penelitian }}</td>
					<td>{{ number_format($b -> dana_pribadi, 2, ',', '.') }}</td>
					<td>{{ number_format($b -> dana_lembaga, 2, ',', '.') }}</td>
					<td>{{ number_format($b -> dana_hibah_nasional, 2, ',', '.') }}</td>
					<td>{{ number_format($b -> dana_hibah_internasional, 2, ',', '.') }}</td>
					<td>
						<a href="{{ route('dosen.penelitian.edit', [$b -> dosen_id, $b -> penelitian_id]) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data penelitian"><i class="fa fa-pencil-square-o"></i> Edit</a>
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