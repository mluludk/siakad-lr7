@extends('app')

@section('title')
Daftar Tahun Akademik
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
		Tahun Akademik
		<small>Daftar Tahun Akademik</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Tahun Akademik</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Tahun Akademik </h3>
		<div class="box-tools">
			<a href="{{ route('tapel.create') }}" class="btn btn-primary btn-xs btn-flat" title="Pendaftaran Tahun Akademik Baru"><i class="fa fa-plus"></i> Tambah Tahun Akademik</a>
		</div>
	</div>
	<div class="box-body">
		@if(!$tapel->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c = 1; ?>
		<table class="table table-bordered">
			<tr style="background-color: #70bbb0;">
				<th rowspan="2">NO.</th>
				<th rowspan="2">TAHUN AKADEMIK</th>
				<th colspan="4" style="background-color: #36e6d4;">WAKTU</th>
				<th colspan="3" style="background-color: #f1f60b;">BUKA</th>
				<th rowspan="2">Status</th>
				<th rowspan="2"></th>
			</tr>
			
			<tr>
				<th colspan="2" class="ctr" style="background-color: #70c5a5;">PERKULIAHAN</th>				
				<th colspan="2" class="ctr" style="background-color: #00a65a">KRS</th>
				
				<th class="ctr" style="background-color: #ddd;">Penilaian<br/>Semester Aktif</th>
				<th class="ctr" style="background-color: #a2f5a6;">Penilaian<br/>Semester Non-Aktif</th>
				<th class="ctr" style="background-color: #f0e5e5;">KRS</th>
			</tr>
			@foreach($tapel as $smt)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ $smt -> nama }}</td>
				<td>{{ formatTanggal($smt -> mulai) }}</td>
				<td>{{ formatTanggal($smt -> selesai) }}</td>
				<td>{{ formatTanggal($smt -> mulaiKrs) }}</td>
				<td>{{ formatTanggal($smt -> selesaiKrs) }}</td>				
				<td align="center">@if($smt -> check_nilai_sem_aktif == 'y') <i class="fa fa-check-square fa-lg text-success"></i> @else <i class="fa fa-square-o fa-lg text-danger"></i> @endif</td>
				<td align="center">@if($smt -> check_nilai_sem_non_aktif == 'y') <i class="fa fa-check-square fa-lg text-success"></i> @else <i class="fa fa-square-o fa-lg text-danger"></i> @endif</td>
				<td align="center">@if($smt -> check_krs == 'y') <i class="fa fa-check-square fa-lg text-success"></i> @else <i class="fa fa-square-o fa-lg text-danger"></i> @endif</td>
				<td>@if($smt -> aktif == 'y')<span class="label label-success"><i class="fa fa-check"></i> AKTIF</span>@else<span class="label label-default"><i class="fa fa-times"></i> TIDAK AKTIF</span>@endif</td>
				<td>
					<a href="{{ route('tapel.edit', $smt->id) }}" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-pencil-square-o"></i> Edit</a>
					<a href="{{ route('tapel.setting.index', $smt->id) }}" class="btn btn-info btn-xs btn-flat" title="Setting Periode Perkuliahan"><i class="fa fa-cog"></i> Setting</a>
				</td>
			</tr>
			<?php $c++; ?>
			@endforeach
		</table>
		@endif
	</div>
</div>
@endsection															