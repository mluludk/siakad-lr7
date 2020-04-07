@extends('app')

@section('title')
Jadwal Kuliah
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Jadwal 2</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/matkul/tapel') }}"> Kelas Kuliah</a></li>
		<li class="active">Jadwal</li>
	</ol>
</section>
@endsection

@push('scripts')
<script>
	$('.filter').change(function(){
		$('form#filter-form').submit();
	});
	$('.table tr.danger').on({
		mouseenter: function () {
			$('tr#' + $(this).attr('id')).addClass('blink');
		},
		mouseleave: function () {
			$('tr#' + $(this).attr('id')).removeClass('blink');
		}
	});
</script>
@endpush

@push('styles')
<style>
	tr.blink > td{
	background-color: #ef3f3f;
	}
	.blink {
	animation: blinker 1s linear infinite;
	}
	@keyframes blinker {  
	50% { 
	color: #fff;
	}
	}
</style>
@endpush

@section('content')
@if(isset($prodi))
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/jadwal2') }}" class="form-inline" id="filter-form">
			{!! csrf_field() !!}
			<div class="form-group">
				<label class="sr-only" for="prodi">PRODI</label>
				{!! Form::select('prodi', $prodi, Request::get('prodi'), ['class' => 'form-control filter']) !!}
				</div>
			<div class="form-group">
				<label class="sr-only" for="ta">Tahun Akademik</label>
				{!! Form::select('ta', $ta, Request::get('ta'), ['class' => 'form-control filter']) !!}
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
		<h3 class="box-title">Kelas Kuliah </h3>
		<div class="box-tools">
			<a href="{{ url('/jadwal/cover') }}" class='btn btn-info btn-xs btn-flat' title='Cetak Sampul'><i class='fa fa-print'></i> Cetak Sampul</a>
			<a href="{{ url('/jadwal') }}" class='btn btn-success btn-xs btn-flat' title='Tabel'><i class='fa fa-table'></i></a>
		</div>
	</div>
	<div class="box-body">
		@if(count($data) < 1)
		<div class="alert alert-info alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h4><i class="icon fa fa-info"></i> Informasi</h4>
			Data jadwal tidak ditemukan
		</div>
		@else
		<?php $c = 1; ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>NO.</th>
					<th>HARI</th>
					<th>JAM</th>
					<th>MATA KULIAH</th>
					<th>PROGRAM</th>
					<th>DOSEN</th>
					<th>SEMESTER</th>
					<th>SKS</th>
					<th>PRODI</th>
					<th>KELAS</th>
					<th>RUANG</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$data -> count())
				<td colspan="7" align="center">Belum ada data</td>
				@else
				<?php $crash = ''; $j = []; ?>
				@foreach($data as $mk)
				<?php
					$d = $mk -> hari . '-' . str_slug($mk -> jam_mulai) . '-' .  $mk -> matkul_tapel -> tim_dosen[0] -> id;
					if(in_array($d, $j)) $crash = 'danger';
					$j[] = $d;
				?>
				<tr class="{{ $crash }}" id="{{ $mk -> hari . '-' .  str_slug($mk -> jam_mulai) . '-' .  $mk -> matkul_tapel -> tim_dosen[0] -> id }}">
					<td>{{ $c }}</td>
					<td>
						@if($mk -> hari != '' )
						{{ config('custom.hari')[$mk -> hari] }}
						@else
						-
						@endif
					</td>
					<td>
						@if($mk -> hari != '' )
						{{ $mk -> jam_mulai }} - {{ $mk -> jam_selesai }}
						@else
						-
						@endif
					</td>
					<td>{{ $mk -> matkul }} ({{ $mk -> kd }}) Kelas {{ $mk -> kelas }}</td>
					<td>{{ $mk -> program }}</td>
					<td>
						{{ formatTimDosen($mk -> matkul_tapel -> tim_dosen) }}
					</td>
					<td>{{ $mk -> semester }}</td>
					<td>{{ $mk -> sks }}</td>
					<td>{{ $mk -> prodi }}</td>
					
					<td>{{ $mk -> kelas }}</td>
					<td>{{ $mk -> ruang }}</td>
					<td>
						@if($mk -> jid)
						<a href="{{ route('matkul.tapel.jadwal.edit', $mk -> jid) }}" class="btn btn-xs btn-success btn-flat"><i class="fa fa-pencil"></i></a>
						<a href="{{ route('matkul.tapel.jadwal.delete', $mk -> jid) }}" class="btn btn-xs btn-danger has-confirmation btn-flat"><i class="fa fa-trash"></i></a>
						@else
						<a href="{{ url('/jadwal/create?id=' . $mk -> mtid) }}" class="btn btn-xs btn-info btn-flat" title="Baru"><i class="fa fa-plus-square-o"></i></a>
					@endif
					</td>
					</tr>
					<?php $c++; ?>
					<?php $crash = ''; $d = ''; ?>
					@endforeach
					@endif
					</tbody>
					</table>
					@endif
					</div>
					</div>
					@endsection																																																																										