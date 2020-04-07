@extends('app')

@section('title')
Peserta PKM {{ $pkm -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		PKM
		<small>Peserta PKM</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="/pkm"> Data PKM</a></li>
		<li class="active">Peserta PKM</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Peserta PKM {{ $pkm -> nama }} ({{ formatTanggal(date('Y-m-d', strtotime($pkm -> tanggal_mulai))) }} - {{ formatTanggal(date('Y-m-d', strtotime($pkm -> tanggal_selesai))) }})</h3>
	</div>
	<div class="box-body">
		@if(!$peserta -> count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c = ($peserta -> currentPage() - 1) * $peserta -> perPage(); ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No</th>
					<th>NIM</th>
					<th>Nama</th>
					<th>PRODI</th>
					<th>Program</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($peserta as $g)
				<?php $c++; ?>
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> NIM }}</td>
					<td>{{ $g -> nama }}</td>
					<td>{{ $g -> prodi -> strata }} {{ $g -> prodi -> nama }}</td>
					<td>{{ $g -> kelas -> nama }}</td>
					<td>
						<a href="{{ route('mahasiswa.pkm.peserta.show', [$pkm -> id, $g -> id]) }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-search"></i> Detail</a>						
						<a href="{{ route('mahasiswa.pkm.peserta.delete', [$pkm -> id, $g -> id]) }}" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i> Hapus</a>						
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{!! $peserta -> render() !!}
		@endif
	</div>
</div>
@endsection												