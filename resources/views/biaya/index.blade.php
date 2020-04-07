@extends('app')

@section('title')
Riwayat Pembayaran
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
<style>
	.fl::first-letter {
	text-transform: uppercase;
	}
</style>
@endpush

@push('scripts')
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script>
	$(function(){
	$('.filter').change(function(){
	$('form#filter-form').submit();
	});
	
	$(".date").datepicker({
	format:"yyyy-mm-dd", 
	autoHide:true,
	daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
	daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
	monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
	});
	});
</script>
@endpush

@section('header')
<section class="content-header">
	<h1>
	Keuangan
<small>Riwayat Pembayaran</small>
</h1>
<ol class="breadcrumb">
	<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
	<li class="active">Riwayat Pembayaran</li>
</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter data Pembayaran mahasiswa</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/biaya') }}" class="form-inline" id="filter-form">
			{!! csrf_field() !!}
			<div class="form-group{{ $errors -> has('tgla') ? ' has-error' : '' }}">
				<label class="sr-only" for="tgla">Tanggal Awal</label>
				<input type="text" class="form-control date" name="tgla" autocomplete="off" placeholder="Tanggal Awal" value="{{ Request::get('tgla') }}">
			</div>
			<div class="form-group{{ $errors -> has('tglb') ? ' has-error' : '' }}">
				<label class="sr-only" for="tglb">Tanggal Akhir</label>
				<input type="text" class="form-control date" name="tglb" autocomplete="off" placeholder="Tanggal Akhir" value="{{ Request::get('tglb') }}">
			</div>
			<div class="form-group{{ $errors -> has('q') ? ' has-error' : '' }}">
				<label class="sr-only" for="q">Kata Kunci</label>
				<input type="text" class="form-control" name="q" placeholder="NIM / Nama / Jenis Pembayaran" value="{{ Request::get('q') }}">
			</div>
			<div class="form-group{{ $errors -> has('t') ? ' has-error' : '' }}">
				<label class="sr-only" for="t">Tahun Akademik</label>
				{!! Form::select('t', $tapel, Request::get('t'), ['class' => 'form-control']) !!}
			</div>
			<div class="form-group{{ $errors -> has('m') ? ' has-error' : '' }}">
				<label class="sr-only" for="m">Metode Pembayaran</label>
				{!! Form::select('m', $metode, Request::get('m'), ['class' => 'form-control']) !!}
			</div>
			<div class="form-group{{ $errors -> has('p') ? ' has-error' : '' }}">
				<label class="sr-only" for="p">Jumlah Data</label>
				{!! Form::select('p', [100 => 100, 200 => 200, 300 => 300], Request::get('p'), ['class' => 'form-control']) !!}
			</div>
			<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
		</form>
	</div>			
</div>

<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Riwayat Pembayaran</h3>
	</div>
	<div class="box-body">	
		<?php 
			$jumlah = 0;
			if($page)
			$c = ($pembayaran -> currentPage() - 1) * $pembayaran -> perPage(); 
			else
			$c = 0;
		?>
		<table class="table table-striped table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No.</th>
					<th>NIM</th>
					<th>Nama</th>
					<th>Jenis Pembayaran</th>
					<th>Semester</th>
					<th>Tanggal</th>
					<th>Jumlah</th>
					<th>Petugas</th>
					<th>Metode Pambayaran</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if($pembayaran -> count())
				@foreach($pembayaran as $b)
				<?php $c++; ?>
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $b -> NIM }}</td>
					<td>{{ $b -> mahasiswa }}</td>
					<td>{{ $b -> nama_tagihan ?? $b -> nama }}</td>
					<td>{{ $b -> tapel }}</td>
					<td>{{ formatTanggal(explode(' ', $b -> created_at)[0]) }}</td>
					<td align="right">{{ formatRupiah($b -> jumlah) }}</td>
					<td>{{ $b -> admin }}</td>
					<td>{{ $b -> metode }}</td>
					<td><a href="{{ route('biaya.mahasiswa.receipt', [$b -> id]) }}" class="btn btn-success btn-xs btn-flat" target="_blank"><i class="fa fa-print"></i></a></td>
				</tr>
				<?php $jumlah += $b -> jumlah; ?>
				@endforeach
				
				@if(!$page)
				<tr>
					<th colspan="5" style="text-align:right !important">Total</th>
					<th colspan="2" style="text-align:right !important">{{ formatRupiah($jumlah) }}</th>
					<td colspan="3" class="fl">{{ terbilang($jumlah) }} rupiah</td>
				</tr>
				@endif
				
				@else
				<tr>
					<td colspan="10" align="center">Belum ada data</td>
				</tr>
				@endif
			</tbody>
		</table>
		@if($page)
		{!! $pembayaran -> appends([
		'_token' => csrf_token(), 
		'q' => Request::get('q'),	
		't' => Request::get('t'),	
		'p' => Request::get('p')
		]) -> render() !!}
		@endif				
	</div>
</div>
@endsection																																																																																																										