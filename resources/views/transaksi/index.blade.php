@extends('app')

@section('title')
Transaksi Keuangan
@endsection

@section('header')
<section class="content-header">
	<h1>
		Transaksi
		<small>Daftar Transaksi</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Transaksi</li>
	</ol>
</section>
@endsection

@section('content')
@if(!$transaksi->count())
Belum ada data
@else
<?php 
	$c = 1; 
	$jenis_transaksi = [1 => 'Pemasukan', 'Pengeluaran'];
?>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Transaksi</h3>
		<div class="box-tools">
			<a href="{{ route('transaksi.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data"><i class="fa fa-plus"></i> Tambah Transaksi</a>
		</div>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<tr>
				<th width="20px">No.</th>
				<th>Tanggal</th>
				<th>Jenis</th>
				<th>Jumlah</th>
				<th>Keterangan</th>
				<th>Petugas</th>
				<!--th></th-->
			</tr>	
			@foreach($transaksi as $i)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ date('d M Y', strtotime($i -> created_at)) }}</td>
				<td>{{ $jenis_transaksi[$i -> jenis] }}</td>
				<td>Rp {{ number_format($i -> jumlah, 2, ',', '.') }}</td>
				<td>{{ $i -> keterangan }}</td>
				<td>{{ $i -> petugas -> authable -> nama }}</td>
				<!--td>
					<a href="" class="btn btn-xs btn-success"><i class="fa fa-edit"></i> Edit</a>
				</td-->
			</tr>	
			<?php $c ++; ?>
			@endforeach
		</table>
		{!! $transaksi -> render() !!}
	</div>
</div>
@endif
@endsection