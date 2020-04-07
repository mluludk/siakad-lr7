@extends('app')

@section('title')
Daftar Tagihan Pembayaran
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Tagihan Pembayaran</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Tagihan Pembayaran</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter data Tagihan pembayaran mahasiswa</h3>
	</div>
	<div class="box-body">
		<form method="get" action="/tagihan">
			{!! csrf_field() !!}
			<div class="row">
				<div class="col-xs-12">
					<div class="input-group">
						<input type="text" class="form-control" name="q" placeholder="NIM / Nama / Jenis pembayaran">
						<span class="input-group-btn">
							<button class="btn btn-primary" type="submit">Cari</button>
						</span>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Tagihan Pembayaran</h3>
		<div class="box-tools">
			<a href="{{ route('tagihan.create') }}" class="btn btn-success btn-xs btn-flat"> <i class="fa fa-magic"></i> Generate Tagihan per-Semester</a>
		</div>
	</div>
	<div class="box-body">
		<?php 
			$c = ($tagihan -> currentPage() - 1) * $tagihan -> perPage(); 
			$skrg = time();
		?>
		<table class="table table-bordered table-striped">
			<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
				<th width="20px">No.</th>
				<th>NIM</th>
				<th>Nama</th>
				<th>Semester</th>
				<th>Pembayaran</th>
				<th>Tagihan</th>
				<th>Sisa</th>
				<th>Jenis Pemb.</th>
				<th>Pembayaran</th>
				<th></th>
			</tr>	
			@if(!$tagihan -> count())
			<tr>
				<td colspan="8">Belum ada data</td>
			</tr>
			@else
			@foreach($tagihan as $i)
			<?php 
				$c ++; 			
				$sisa = $i -> jumlah - $i -> bayar;
				
				$allowed = '';
				if($i -> tgl_cicilan_awal != null && $i -> tgl_cicilan_akhir != null && $sisa > 0)
				{
					$allowed = 'n';
					if($skrg >= strtotime($i -> tgl_cicilan_awal . ' 00:00:00') && $skrg <= strtotime($i -> tgl_cicilan_akhir . ' 23:59:59')) $allowed = 'y';
					if($i -> override == 'y') $allowed = 'y';
				}
				if($i -> bank_id < 1) $allowed = 'y';
			?>
			<tr>
				<td>{{ $c }}</td>
				<td>{{ $i -> NIM }}</td>
				<td>{{ $i -> nama }}</td>
				<td>{{ $i -> tapel }}</td>
				<td>@if($i -> nama_tagihan == ''){{ $i -> nama_jenis }} @else {{ $i -> nama_tagihan }} @endif</td>
				<td>Rp {{ number_format($i -> jumlah, 2, ',', '.') }}</td>
				<td>Rp {{ number_format($sisa, 2, ',', '.') }}</td>
				<td>{{ $i -> metode_pembayaran }}</td>
				<td>
					@if($allowed == 'n') 
					<i class="fa fa-lock text-danger"></i>
					@endif
				</td>
				<td>
					@if($allowed == 'n') 
					<a href="{{ route('tagihan.unlock', $i -> id) }}" class="btn btn-xs btn-success btn-flat has-confirmation" title="Buka pembayaran" 
				data-message="Buka Pembayaran Tagihan @if($i -> nama_tagihan == ''){{ $i -> nama_jenis }} @else {{ $i -> nama_tagihan }} @endif untuk {{ $i-> nama }} ({{ $i-> NIM }})"><i class="fa fa-unlock"></i></a>
					@endif
					@if($sisa > 0)
					<a href="{{ route('tagihan.edit', $i -> id) }}" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-edit"></i></a>
					@else
					<button class="btn btn-success btn-xs btn-flat"><i class="fa fa-check"></i></button>
					@endif
					<a href="{{ route('tagihan.delete', $i -> id) }}" class="btn btn-xs btn-danger btn-flat has-confirmation"><i class="fa fa-trash"></i></a>
				</td>
			</tr>	
			@endforeach
			@endif
		</table>
		{!! $tagihan -> appends([
		'_token' => csrf_token(), 
		'q' => Request::get('q')
		])-> render() !!}
	</div>
</div>
@endsection																	