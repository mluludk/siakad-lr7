@extends('app')

@section('title')
Setup Biaya Kuliah
@endsection

@push('scripts')

<script>
	$('.filter').change(function(){
		var prodi = $('select[name=prodi]').val();
		var angkatan = $('select[name=angkatan]').val();
		var program = $('select[name=program]').val();
		var jenis = $('select[name=jenis]').val();		
		if(prodi != '-' && angkatan != '-' && program != '-' && jenis != '-') $('form#filter').submit();
	});
</script>
@endpush

@push('styles')
<style>		
	.table th{
	vertical-align: middle !important;
	text-align: center !important;
	}
	.rp{
	border-right: none !important;
	width: 20px;
	}
	.rp ~ td{
	border-left: none !important;
	}
	.ib{
	display: inline-block;
	width: auto;
	}
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Setup Biaya Kuliah</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Setup Biaya Kuliah</li>
	</ol>
</section>
@endsection

@section('content')

<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
		<div class="box-tools">
			<form method="post" action='{{ url("/biaya/setup/copy") }}'>
				{{ csrf_field() }}
				{!! Form::select('from[prodi]', $prodi, null, ['class' => 'form-control input-sm ib']) !!}
				{!! Form::select('from[angkatan]', $angkatan, null, ['class' => 'form-control input-sm ib']) !!}
				{!! Form::select('from[program]', $program, null, ['class' => 'form-control input-sm ib']) !!}
				{!! Form::select('from[jenis]', $jenis, null, ['class' => 'form-control input-sm ib']) !!}
				
				{!! Form::hidden('to[prodi]', Request::get('prodi')) !!}
				{!! Form::hidden('to[angkatan]', Request::get('angkatan')) !!}
				{!! Form::hidden('to[program]', Request::get('program')) !!}
				{!! Form::hidden('to[jenis]', Request::get('jenis')) !!}
				
				<button class="btn btn-danger btn-flat btn-submit btn-xs" type="submit"><i class="fa fa-copy"></i> Salin dari</button>
			</form>
		</div>
	</div>
	<div class="box-body">
		<form method="get" action='{{ url("/biaya/setup") }}' id="filter" class="form-inline">
			{{ csrf_field() }}
			<div class="form-group">
				<label class="sr-only" for="prodi">PRODI</label>
				{!! Form::select('prodi', $prodi, Request::get('prodi'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="angkatan">Angkatan</label>
				{!! Form::select('angkatan', $angkatan, Request::get('angkatan'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="program">Program</label>
				{!! Form::select('program', $program, Request::get('program'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="jenis">Jenis</label>
				{!! Form::select('jenis', $jenis, Request::get('jenis'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<button class="btn btn-warning btn-flat btn-submit" type="submit"><i class="fa fa-filter"></i> Filter</button>
			</div>
		</div>
	</form>
</div>

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Setup Biaya Kuliah</h3>
		<div class="box-tools">
			<a href="{{ route('biaya.setup.create') }}" class="btn btn-primary btn-xs btn-flat" title="Tambah Setup Biaya"><i class="fa fa-plus"></i> Tambah Setup Biaya</a>
		</div>
	</div>
	<div class="box-body">
		<?php $c = 1; $total = 0;?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,3 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th rowspan="2" width="20px">No.</th>
					<th rowspan="2">Biaya</th>
					<th rowspan="2" width="170px">Nominal</th>
					<th colspan="4">Syarat</th>
					<th rowspan="2"></th>
				</tr>
				<tr>
					<th>KRS</th>
					<th>UTS</th>
					<th>UAS</th>
					<th>Login</th>
				</tr>
			</thead>
			<tbody>
				@if(count($biaya) <= 0)
				<tr>
					<td colspan="8" align="center">Belum ada data</td>
				</tr>
				@else
				@foreach($biaya as $k => $v)
				<tr class="info">
					<td colspan="2"><strong>{{ $golongan[$k] }}</strong></td>
					<td><strong>Rp <span style="display:inline-block; float:right;">{{ number_format($jumlah_nominal_golongan[$k], 0, ',', '.') }}</span></strong></td>
					<td colspan="5"></td>
				</tr>
				@foreach($v as $b)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $b['biaya'] }}</td>
					<td>Rp <span style="display:inline-block; float:right;">{{ $b['jumlah'] }}</span></td>
					<td>{{ $b['krs'] }} %</td>
					<td>{{ $b['uts']  }} %</td>
					<td>{{ $b['uas']  }} %</td>
					<td align="center">
						@if($b['login'] == 'y') <i class="fa fa-check-square fa-lg text-success"></i> @else <i class="fa fa-square-o fa-lg text-danger"></i> @endif
					</td>
					<td>
						<a href="{{ route('biaya.setup.edit', $b['route']) }}" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-edit"></i> Edit</a>
						<a href="{{ route('biaya.setup.delete', $b['route']) }}" class="btn btn-xs btn-danger btn-flat has-confirmation"><i class="fa fa-trash"></i> Delete</a>
					</td>
				</tr>
				<?php 
					$cicilan = json_decode($b['cicilan']);
					$c++; 
				?>
				@if(!empty($cicilan))
				@foreach($cicilan as $xx => $yy)
				<tr>
					<td></td>
					<td>Cicilan {{ $xx }}</td>
					<td>Rp <span style="display:inline-block; float:right;">{{ number_format($yy -> jml, 0, ',', '.')  }}</span></td>
					<td colspan="5">{{ $yy -> tgla }} s/d {{ $yy -> tglb }}</td>
				</tr>
				@endforeach
				@endif
				
				@endforeach
				<?php 
					$total += $jumlah_nominal_golongan[$k];
				?>
				@endforeach
				<tr>
					<td colspan="2" align="right"><strong>TOTAL</strong></td>
					<td><strong>Rp <span style="display:inline-block; float:right;">{{ number_format($total, 0, ',', '.') }}</span></strong></td>
					<td colspan="5"></td>
				</tr>
				@endif
			</tbody>
		</table>
	</div>
</div>	
@endsection																																																																																																																																																				