@extends('app')

@section('title')
Pengajuan Judul Skripsi {{ $gelombang -> jadwal -> nama ?? '' }} {{ $gelombang -> nama ?? '' }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pengajuan Judul Skripsi
		<small>{{ $gelombang -> jadwal -> nama ?? '' }} {{ $gelombang -> nama ?? '' }}</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active"><a href="{{ url('/jadwal/pengajuan/skripsi') }}">Jadwal Pengajuan Judul Skripsi</a></li>
		<li class="active">Pengajuan Judul Skripsi</li>
	</ol>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
<style>
	.percent{font-weight: bold;}
</style>
@endpush

@section('content')

@if($prodi !== null and $gelombang !== null)
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ route('jadwal.pengajuan.skripsi.gelombang.peserta', $gelombang -> id) }}" class="form-inline" id="filter-form">
			{!! csrf_field() !!}
			<div class="form-group">
				<label class="sr-only" for="prodi">PRODI</label>
				{!! Form::select('prodi', $prodi, Request::get('prodi'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
			</div>
		</form>
	</div>
</div>
@endif

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Pengajuan Judul Skripsi {{ $gelombang -> jadwal -> nama ?? '' }} {{ $gelombang -> nama ?? '' }}</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered" id="table">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="20px" rowspan="2">No</th>
					<th rowspan="2">Tanggal</th>
					<th rowspan="2">NIM</th>
					<th rowspan="2">Nama</th>
					<th rowspan="2">No. Hp</th>
					<th rowspan="2">PRODI</th>
					<th rowspan="2">Judul</th>
					<th colspan="6">Judul dalam proses pengecekan dan proses validasi</th>
				</tr>
				<tr>
					<th class="ctr" style="background-color: #f0e5e5;">Kesamaan</th>
					<th colspan="2" class="ctr" style="background-color: #70c5a5;">Validasi</th>				
					<th class="ctr" style="background-color: #00a65a">Pembimbing</th>
					<th class="ctr" style="background-color: #ddd;">Keterangan</th>
					<th class="ctr" style="background-color: #a2f5a6;">Aksi</th>
				</tr>
			</thead>
			<tbody>
				@if(!$mahasiswa -> count())
				<tr>
					<td colspan="13">Belum ada data</td>
				</tr>
				@else
				<?php $c = 0; ?>
				@foreach($mahasiswa as $g)
				<?php 
					$c++; 
					if($g -> similarity > $g -> max_similarity) $percent = 'danger';
					else $percent = 'success';
					
					if($g -> similarity2 != '')
					{
						if($g -> similarity2 > $g -> max_similarity) $percent2 = 'danger';
						else $percent2 = 'success';
					}
				?>
				<tr>
					<td>{{ $c }}</td>
					<td>{{ date('d/m/Y', strtotime($g -> created_at)) }}</td>
					<td>{{ $g -> NIM }}</td>
					<td>{{ $g -> nama }}</td>
					<td>{{ $g -> hp }}</td>
					<td>{{ $g -> strata }} {{ $g -> singkatan }}</td>
					<td>
						{{ $g -> judul }}
						@if($g -> judul_revisi != '')
						<br/>
						<strong>Revisi: </strong><br/>
						<span class="text-danger">{{ $g -> judul_revisi }}</span>
						@endif
					</td>
					<td>
						<span class="percent text-{{ $percent }}">{{ $g -> similarity }}%</span>
						@if($g -> similarity2 != '')
						<br/>
						<strong>Revisi: </strong><br/>
						<span class="percent text-{{ $percent2 }}" style="font-weight: bold">{{ $g -> similarity2 }}%</span>
						@endif
					</td>
					<td>
						@if($g -> diterima == 'y')<span class="label label-success" data-toggle="popover" data-content="Skripsi telah disetujui KAPRODI"><i class="fa fa-check"></i> DISETUJUI</span>
						@elseif($g -> diterima == 'p')<span class="label label-warning" data-toggle="popover" data-content="Skripsi belum diproses KAPRODI"><i class="fa fa-exclamation-triangle"></i> PENDING</span>
						@else($g -> diterima == 'n')<span class="label label-danger" data-toggle="popover" data-content="Skripsi tidak disetujui KAPRODI"><i class="fa fa-times"></i> DITOLAK</span>
						@endif
					</td>
					<td>
						@if($g -> diterima_dosen == 'y')<span class="label label-success" data-toggle="popover" data-content="Skripsi telah disetujui DOSPEM"><i class="fa fa-check"></i> DISETUJUI</span>
						@elseif($g -> diterima_dosen == 'r')<span class="label label-info" data-toggle="popover" data-content="Revisi Judul Skripsi"><i class="fa fa-edit"></i> REVISI</span>
						@elseif($g -> diterima_dosen == 'p')<span class="label label-warning" data-toggle="popover" data-content="Skripsi belum diproses DOSPEM"><i class="fa fa-exclamation-triangle"></i> PENDING</span>
						@else($g -> diterima_dosen == 'n')<span class="label label-danger" data-toggle="popover" data-content="Skripsi tidak disetujui DOSPEM"><i class="fa fa-times"></i> DITOLAK</span>
						@endif
					</td>
					<td>{{ $g -> dosen_id }} {{ $g -> p_gd }} {{ $g -> p_nm }} {{ $g -> p_gb }}</td>
					<td>
						{{ $g -> keterangan }}
					</td>
					<td><a class="btn btn-info btn-xs btn-flat" href="{{ route('mahasiswa.skripsi.pengajuan.edit', $g -> id) }}"><i class="fa fa-search"></i></a></td>
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
@endsection		

@push('scripts')
<script src="{{ asset('js/datatables.min.js') }}"></script>
<script>
	$(function () {
	$('#table').DataTable({
		"lengthMenu": [10,50,100,250,300]
	});
	$('[data-toggle="popover"]').popover({
		html: true,
		placement: 'auto top',
		trigger: 'hover'
	})
})
</script>
@endpush																																							