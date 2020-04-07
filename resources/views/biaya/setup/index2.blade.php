@extends('app')

@section('title')
Setup Biaya Kuliah
@endsection

@push('scripts')

<script>
	$('.filter').change(function(){
		$('form#filter').submit();
	});
</script>
@endpush

@push('styles')
<style>	
	.input-group{
	position: relative;
	}
	.loader{
	color: #f00900;
	position: absolute;
	z-index: 999;
	top: 10px;
	right: 50%;
	}
	
	.radio-inline+.radio-inline, .checkbox-inline+.checkbox-inline {
	margin-top: 0;
	margin-left: 0;
	margin-right: 10px;
	}
	.radio-inline:not(first-child){
	margin-right: 10px;
	}
	.table th{
	vertical-align: middle !important;
	text-align: center !important;
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
				<label class="sr-only" for="biaya">Biaya</label>
				{!! Form::select('biaya', $jbiaya, Request::get('biaya'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
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
					<th rowspan="2">PRODI</th>
					<th rowspan="2">Angkatan</th>
					<th rowspan="2">Program</th>
					<th rowspan="2">Jenis Pembiayaan</th>
					<th rowspan="2">Biaya</th>
					<th rowspan="2" width="180px">Jumlah</th>
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
				@if(!$biaya -> count())
				<tr>
					<td colspan="11" align="center">Belum ada data</td>
				</tr>
				@else
				@foreach($biaya as $j)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $j -> prodi -> strata }} {{ $j -> prodi -> singkatan }}</td>
					<td>{{ $j -> angkatan }}</td>
					<td>{{ $j -> program -> nama }}</td>
					<td>{{ $jenis[$j -> jenisPembayaran] }}</td>
					<td>{{ $j -> jenis -> nama }}</td>
					<td align="right">{{ number_format($j -> jumlah, 0, ',', '.') }}</td>
					<td>{{ $j -> krs ?? 0 }} %</td>
					<td>{{ $j -> uts ?? 0  }} %</td>
					<td>{{ $j -> uas ?? 0  }} %</td>
					<td>{{ $j -> login }}</td>
					<td>
						<a href="{{ route('biaya.setup.edit', [$j -> jenis_biaya_id, $j -> angkatan, $j -> prodi_id, $j -> kelas_id, $j -> jenisPembayaran]) }}" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-edit"></i> Edit</a>
						<a href="{{ route('biaya.setup.delete', [$j -> jenis_biaya_id, $j -> angkatan, $j -> prodi_id, $j -> kelas_id, $j -> jenisPembayaran]) }}" class="btn btn-xs btn-danger btn-flat has-confirmation"><i class="fa fa-trash"></i> Delete</a>
					</td>
				</tr>
				<?php 
					$total += $j -> jumlah;
					$c++; 
				?>
				@endforeach
				@endif
				<tr>
					<td colspan="6" align="right"><strong>TOTAL</strong></td>
					<td><strong>Rp <span style="display:inline-block; float:right;" id="total">{{ number_format($total, 0, ',', '.') }}</span></strong></td>
					<td colspan="5"></td>
				</tr>
			</tbody>
		</table>
		{!! $biaya -> appends([
		'_token' => csrf_token(), 
		'prodi' => Request::get('prodi'), 
		'angkatan' => Request::get('angkatan'), 
		'program' => Request::get('program'), 
		'jenis' => Request::get('jenis')
		]) -> render() !!}
	</div>
	</div>	
	@endsection																																																																																																																									