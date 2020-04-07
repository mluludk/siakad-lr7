@extends('app')

@section('title')
Riwayat Pendidikan Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Riwayat Pendidikan</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen') }}">Dosen</a></li>
		<li class="active">Riwayat Pendidikan</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Riwayat Pendidikan Dosen</h3>
	</div>
	<div class="box-body">
		@if(!$pendidikan->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php 
			$c=1; 
			$jenjang = config('custom.pilihan.pendidikanDosen');
		?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No</th>
					<th>Nama</th>
					<th>Bidang Studi</th>
					<th>Jenjang</th>
					<th>Gelar</th>
					<th>Perguruan Tinggi</th>
					<th>Fakultas</th>
					<th>Tahun Lulus</th>
					<th>SKS</th>
					<th>IPK</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($pendidikan as $b)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $b -> dosen }}</td>
					<td>{{ $b -> bidangStudi }}</td>
					<td>{{ $jenjang[$b -> jenjang] }}</td>
					<td>{{ $b -> gelar }}</td>
					<td>{{ $b -> perguruanTinggi }}</td>
					<td>{{ $b -> fakultas }}</td>
					<td>{{ $b -> tahunLulus }}</td>
					<td>{{ $b -> sks }}</td>
					<td>{{ $b -> ipk }}</td>
					<td>
						<a href="{{ route('dosen.pendidikan.edit', [$b -> dosen_id, $b -> id]) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data pendidikan"><i class="fa fa-pencil-square-o"></i> Edit</a>
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>
@endsection																