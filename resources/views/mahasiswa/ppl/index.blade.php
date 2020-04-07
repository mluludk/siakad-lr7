@extends('app')

@section('title')
PPL
@endsection

@section('header')
<section class="content-header">
	<h1>
		PPL
		<small>Data PPL</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Data PPL</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Data PPL</h3>
		<div class="box-tools">
			<a href="{{ route('mahasiswa.ppl.create') }}" class="btn btn-primary btn-xs btn-flat" title="Tambah Data PPL"><i class="fa fa-plus"></i> Tambah Data PPL</a>
		</div>
	</div>
	<div class="box-body">
		<?php
			$c=1;
			$now = time();
		?>
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No</th>
					<th>Mata Kuliah PPL</th>
					<th>Tahun Akademik</th>
					<th>Prodi</th>
					<th>Tanggal</th>
					<th>Lokasi</th>
					<th>SK</th>
					<th>Tanggal SK</th>
					<th>Pendaftaran</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$ppl -> count())
				<tr>
					<td colspan="10" align="center">Belum ada data</td>
				</tr>
				@else
				@foreach($ppl as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> matkul }} ({{ $g -> kode }})</td>
					<td class='rotate'><span>{{ $g -> tapel }}</span></td>
					<td class='rotate'><span>{{ $g -> strata }} {{ $g -> prodi }}</span></td>
					<td class='rotate'><span>{{ formatTanggal(date('Y-m-d', strtotime($g -> tanggal_mulai))) }} - {{ formatTanggal(date('Y-m-d', strtotime($g -> tanggal_selesai))) }}</span></td>
					<td>
						{!! formatLokasi($g -> lokasi, $user, 'ppl') !!}
						@if($user -> role_id < 128)
						<a href="{{ route('mahasiswa.ppl.lokasi.create', $g -> id) }}" class="btn btn-primary btn-flat btn-xs" title="Tambah Lokasi PPL"><i class="fa fa-map-marker"></i> Tambah Lokasi</a>
						@endif
					</td>
					<td class='rotate'><span>{{ $g -> sk ?? '' }}</span></td>
					<td class='rotate'><span>{{ formatTanggal(date('Y-m-d', strtotime($g -> tanggal_sk))) }}</span></td>
					<td>
						@if($now >= strtotime($g -> tgl_mulai_daftar . ' 00:00:00') and $now <= strtotime($g -> tgl_selesai_daftar . ' 23:59:59'))
						<span class="label label-success label-flat">Buka</span>
						@else
						<span class="label label-danger label-flat">Tutup</span>
						@endif
					</td>
					<td>
						<a href="{{ route('mahasiswa.ppl.lokasi.peserta.index', $g -> id) }}" class="btn btn-info btn-flat btn-xs" title="Peserta PPL"><i class="fa fa-share-alt"></i> Peserta</a>
						@if($user -> role_id < 128)
						<a href="{{ route('mahasiswa.ppl.edit', $g -> id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit Data PPL"><i class="fa fa-pencil-square-o"></i> Edit</a>
						<a href="{{ route('mahasiswa.ppl.delete', $g -> id) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Hapus PPL"><i class="fa fa-trash"></i> Hapus</a>
						@endif
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
@push('styles')
		<style type="text/css">
td.rotate
{
  vertical-align: middle !important;
  text-align: center !important;
}

td.rotate span
{
  text-align: center !important;
  -ms-writing-mode: tb-rl;
  -webkit-writing-mode: vertical-rl;
  writing-mode: vertical-rl;
  transform: rotate(180deg);
  white-space: nowrap;
}
		</style>
		@endpush																	