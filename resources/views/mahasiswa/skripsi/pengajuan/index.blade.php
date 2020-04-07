@extends('app')

@section('title')
Pengajuan Judul Skripsi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Skripsi
		<small>Pengajuan Judul</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Pengajuan Judul Skripsi</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Pengajuan Judul Skripsi</h3>
		<div class="box-tools">
			<a href="{{ route('mahasiswa.skripsi.pengajuan.create') }}" class="btn btn-primary btn-xs btn-flat"><i class=" fa fa-plus"></i> Pengajuan Judul Skripsi</a>
		</div>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th rowspan="2"width="20px">No</th>
					<th rowspan="2">Tanggal</th>
					<th rowspan="2">Judul</th>
					<th rowspan="2">Latar Belakang</th>
					<th rowspan="2">Rumusan Masalah</th>
					<th colspan="6">Judul dalam proses pengecekan dan proses validasi</th>	
				</tr>
				<tr>
					<th class="ctr" style="background-color: #f0e5e5;">Kesamaan</th>
					<th colspan="2" class="ctr" style="background-color: #70c5a5;">Validasi</th>				
					<th class="ctr" style="background-color: #00a65a">Pembimbing</th>
					<th class="ctr" style="background-color: #ddd;">Keterangan</th>
				</tr>
			</thead>
			<tbody>
				@if(!$pengajuan->count())
				<tr>
					<td colspan="9">Belum ada data</td>
				</tr>
				@else
				<?php $c = 0; ?>
				@foreach($pengajuan as $g)
				<?php 
					$c++; 
					if($g -> similarity > $g -> gelombang  -> jadwal -> max_similarity) $percent = 'danger';
					else $percent = 'success';
					
					if($g -> similarity2 != '')
					{
						if($g -> similarity2 > $g -> gelombang  -> jadwal -> max_similarity) $percent2 = 'danger';
						else $percent2 = 'success';
					}
				?>
				<tr>
					<td>{{ $c }}</td>
					<td>{{ date('d/m/Y', strtotime($g -> created_at)) }}</td>
					<td>
						{{ $g -> judul }}
						
						@if($g -> judul_revisi != '')
						<br/>
						<strong>Revisi: </strong><br/>
						<span class="text-danger">{{ $g -> judul_revisi }}</span>
						@endif
						
						@if($g -> diterima_dosen == 'r')
						<a href="{{ route('skripsi.revisi', $g -> skripsi_id) }}" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i> Revisi Judul</a>
						@endif
					</td>
					<td>{{ $g -> latar_belakang }}</td>
					<td>
						<ol class="tim_dosen">
							@foreach($g -> rumusan_masalah as $r)
							<li>{{ $r }}</li>
							@endforeach
						</ol>
					</td>
					<td>
						<span class="percent text-{{ $percent }}" style="font-weight: bold">{{ $g -> similarity }}%</span>
						@if($g -> similarity2 != '')
						<br/>
						<strong>Revisi: </strong><br/>
						<span class="percent text-{{ $percent2 }}" style="font-weight: bold">{{ $g -> similarity2 }}%</span>
						@endif
					</td>
					<td>
						@if($g -> diterima == 'y')<span class="label label-success" data-toggle="popover" data-content="Skripsi telah disetujui KAPRODI"><i class="fa fa-check"></i> DISETUJUI</span>
						@elseif($g -> diterima == 'p')<span class="label label-warning" data-toggle="popover" data-content="Skripsi belum diproses KAPRODI"><i class="fa fa-exclamation-triangle"></i>PENDING</span>
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
					</td>
					<td>{{ $g -> p_gd }} {{ $g -> p_nm }} {{ $g -> p_gb }}</td>
					<td>{{ $g -> keterangan }}</td>
					</tr>
					@endforeach
					@endif
					</tbody>
					</table>
					</div>
					</div>
					@endsection		
					
					@push('scripts')
					<script>
					$(function () {
					$('[data-toggle="popover"]').popover({
					html: true,
					placement: 'auto top',
					trigger: 'hover'
					})
					})
					</script>
					@endpush								