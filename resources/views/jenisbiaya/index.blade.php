@extends('app')

@section('title')
Jenis Pembayaran
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Jenis Pembayaran</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Jenis Pembayaran</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Jenis Pembayaran</h3>
		<div class="box-tools">
			<a href="{{ route('jenisbiaya.create') }}" class="btn btn-primary btn-xs btn-flat" title="Tambah Jenis Pembayaran"><i class="fa fa-plus"></i> Tambah Jenis Biaya</a>
		</div>
	</div>
	<div class="box-body">
		@if(!count($jenis))
		<p class="text-muted">Belum ada data</p>
		@else
		<?php 
			$c = 1; 
		?>
		<table class="table table-bordered">
			<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
				<th width="20px">No.</th>
				<th>Nama</th>
				<th>Periode</th>
				<th>Keterangan</th>
				<th></th>
			</tr>	
			@foreach($jenis as $k => $v)
			<tr>
				<th colspan="5" class="info">{{ $k }}</th>
			</tr>
			@foreach($v as $i)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ $i -> nama }}</td>
				<td>{{ $periode[$i -> periode] }}</td>
				<td>{{ $i -> keterangan }}</td>
				<td>
					<a href="{{ route('jenisbiaya.edit', $i -> id) }}" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-edit"></i> Edit</a>
					<a href="{{ route('jenisbiaya.delete', $i -> id) }}" class="btn btn-xs btn-danger btn-flat has-confirmation"><i class="fa fa-trash"></i> Delete</a>
				</td>
			</tr>	
			<?php $c ++; ?>
			@endforeach
			@endforeach
		</table>
		@endif
	</div>
	</div>
	@endsection	