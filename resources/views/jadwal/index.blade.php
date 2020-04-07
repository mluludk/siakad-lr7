@extends('app')

@section('title')
Jadwal Kuliah
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Jadwal</small>
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
	.opr{
	position: absolute;
	right: 3px;
	bottom: 3px;
	display:none;
	}
	td:hover .opr{
	display:block;
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
		<form method="get" action="{{ url('/jadwal') }}" class="form-inline" id="filter-form">
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
		<h3 class="box-title">Jadwal Kuliah </h3>
		<div class="box-tools">
			<a href="{{ url('/jadwal/cover') }}" class='btn btn-info btn-xs btn-flat' title='Cetak Sampul'><i class='fa fa-print'></i> Cetak Sampul</a>
			<a href="{{ url('/jadwal2') }}" class='btn btn-success btn-xs btn-flat' title='List'><i class='fa fa-list'></i></a>
<!--
			<a href="{{ url('/jadwal/create') }}" class='btn btn-primary btn-xs btn-flat' title='Buat Jadwal'><i class='fa fa-plus'></i> Jadwal Baru</a>
-->
		</div>
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
		<h5 class="tbl-header" style="margin-top: 0px !important;">Prodi {{ $header[0] }}<br/>Program {{ $header[1] }}<br/>Kelas {{ $header[2] }}</h5>
		
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
						<div class="opr">
							@if($sem[$semester]['id'])
							<a href="{{ route('matkul.tapel.jadwal.edit', $sem[$semester]['id']) }}" class="btn btn-xs btn-success btn-flat" title="Ubah"><i class="fa fa-pencil"></i></a>
							<a href="{{ route('matkul.tapel.jadwal.delete', $sem[$semester]['id']) }}" class="btn btn-xs btn-danger has-confirmation btn-flat" title="Hapus"><i class="fa fa-trash"></i></a>
							@else
							<a href="{{ url('/jadwal/create?id=' . $sem[$semester]['mtid']) }}" class="btn btn-xs btn-info btn-flat" title="Baru"><i class="fa fa-plus"></i></a>
							@endif
						</div>
					</td>
					<td>{{ $sem[$semester]['sks'] }}</td>
					<td>{{ $sem[$semester]['ruang'] }}</td>
					@else
					<td class="matkuls"> -
						<div class="opr">
							<a href="{{ url('/jadwal/create?hari=' . $hari . '&jam_mulai=' . $waktu[0] . '&jam_selesai=' . $waktu[1]) }}" class="btn btn-xs btn-info btn-flat" title="Baru"><i class="fa fa-plus-square-o"></i></a>
						</div></td>
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