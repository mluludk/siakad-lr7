@extends('app')

@section('title')
Setting Tahun Akademik {{ $tapel -> nama }}
@endsection

@push('styles')
<style>
	th{
	vertical-align: middle !important;
	text-align: center !important;
	}
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Tahun Akademik {{ $tapel -> nama }}
		<small>Setting</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/tapel') }}"> Daftar Tahun Akademik</a></li>
		<li class="active">Setting Tahun Akademik {{ $tapel -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Setting Tahun Akademik {{ $tapel -> nama }}</h3>
	</div>
	<div class="box-body">
		<?php $c = 1; ?>
		<table class="table table-bordered">
			<thead>
				<tr style="background-color: #70bbb0;">
					<th>NO.</th>
					<th>PRODI</th>
					<th>Target MHS Baru</th>
					<th>Pendaftar ikut Seleksi</th>
					<th>Pendaftar Lulus Seleksi</th>
					<th>Daftar Ulang</th>
					<th>Mengundurkan Diri</th>
					<th>Jumlah Minggu Pertemuan</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$tapel -> setting -> count())
				<tr>
					<td colspan="8">Belum ada data</td>
				</tr>
				@else
				@foreach($tapel -> setting as $s)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $s -> prodi -> strata }} {{ $s -> prodi -> singkatan }}</td>
					<td>{{ $s -> target_mhs_baru ?? '?' }}</td>
					<td>{{ $s -> calon_ikut_seleksi ?? '?' }}</td>
					<td>{{ $s -> calon_lulus_seleksi ?? '?' }}</td>
					<td>{{ $s -> daftar_sbg_mhs ?? '?' }}</td>
					<td>{{ $s -> pst_undur_diri ?? '?' }}</td>
					<td>{{ $s -> jml_mgu_kul ?? '?' }}</td>
					<td>
						<a href="{{ route('tapel.setting.edit', [$tapel -> id, $s -> id]) }}" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i> Edit</a>
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