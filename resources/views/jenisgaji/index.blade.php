@extends('app')

@section('title')
Jenis Gaji
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Jenis Gaji</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Jenis Gaji</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Jenis Gaji</h3>
		<div class="box-tools">
			<a href="{{ route('jenisgaji.create') }}" class="btn btn-info btn-xs btn-flat" title="Tambah Jenis Gaji"><i class="fa fa-plus"></i></a>
		</div>
	</div>
	<div class="box-body">
		@if(!$jbiaya->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php 
			$c = 1; 
		?>
		<table class="table table-bordered">
			<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
				<th width="20px">No.</th>
				<th>Nama</th>
				<th>Keterangan</th>
				<th></th>
			</tr>	
			@foreach($jbiaya as $i)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ $i -> nama }}</td>
				<td>{{ $i -> keterangan }}</td>
				<td>
					<a href="{{ route('jenisgaji.edit', $i -> id) }}" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-edit"></i> Edit</a>
					<a href="{{ route('jenisgaji.delete', $i -> id) }}" class="btn btn-xs btn-danger has-confirmation btn-flat"><i class="fa fa-trash"></i> Delete</a>
				</td>
			</tr>	
			<?php $c ++; ?>
			@endforeach
		</table>
	</div>
</div>
@endif
@endsection