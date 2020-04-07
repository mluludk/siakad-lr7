@extends('app')

@section('title')
Jadwal Kuliah
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Jadwal Kuliah</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Jadwal Kuliah</li>
	</ol>
</section>
@endsection

@push('scripts')
<script>
	$('.filter').change(function(){
		$('form#filter-form').submit();
	});
</script>
@endpush

@push('styles')
<style>
	.semester{
	width: 200px;
	}
	.tbl-header{
	text-align: center;
	text-transform: uppercase;
	}
	.table{
	font-size: 12px;
	}
	td{
	padding: 4px 8px !important;
	}
	td:not(.matkuls){
	vertical-align:middle !important;
	}
	.matkuls{
	position: relative;
	}
</style>
@endpush

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/jadwalsemua') }}" class="form-inline" id="filter-form">
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

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Jadwal Kuliah</h3>
	</div>
	<div class="box-body">
		<?php
			$today = date('N');
		?>
		@if(count($data) < 1)
		<div class="alert alert-info alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h4><i class="icon fa fa-info"></i> Informasi</h4>
			Data jadwal tidak ditemukan
		</div>
		@else
		@foreach($data as $table => $jadwal)
		<?php
			$header = explode('|', $table);
		?>
		<h4 class="tbl-header" style="margin-bottom: 3px !important;">Jadwal Kuliah Tahun akademik {{ $aktif[0] }} {{ $aktif[1] }}</h4>
		<h5 class="tbl-header" style="margin-top: 0px !important;">prodi {{ $header[0] }}<br/>Program {{ $header[1] }}</h5>
		
		<table class="table table-bordered table-hover">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>Hari</th>
					<th>Jam</th>
					@foreach($smt as $semester)
					<th class="semester">Semester {!! arabicToRoman($semester) !!}</th>
					<th>SKS</th>
					<th>Ruang</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				@foreach($jadwal as $hari => $mk)
				<?php
					$c = count($mk);
					$h = '<td rowspan="' . $c . '">';
					$h .= $hari != '' ? config('custom.hari')[$hari] : '-';
					$h .= '</td>';
				?>
				@foreach($mk as $jam => $sem)
				<?php
					$waktu = explode(' - ', $jam);
				?>
				<tr @if($hari == $today)class="info" @endif >
					{!! $h !!}
					<td>{{ $jam }}</td>
					@foreach($smt as $semester)
					@if(isset($sem[$semester]))
					<td class="matkuls">
						{{ $sem[$semester]['matkul'] }} <br/> {{ $sem[$semester]['dosen'] }}
					</td>
					<td>{{ $sem[$semester]['sks'] }}</td>
					<td>{{ $sem[$semester]['ruang'] }}</td>
					@else
					<td class="matkuls">-</td>
						<td>-</td>
						<td>-</td>
						@endif
						@endforeach
				</tr>
				<?php $h = ''; ?>
				@endforeach
				@endforeach
			</tbody>
		</table>
		@endforeach
		@endif
	</div>
</div>
@endsection																			