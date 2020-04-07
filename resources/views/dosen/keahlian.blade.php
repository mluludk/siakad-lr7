@extends('app')

@section('title')
Keahlian Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Keahlian Dosen</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen') }}"> Daftar Dosen</a></li>
		<li class="active">Keahlian</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Keahlian Dosen</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="40px">No.</th>
					<th>NIDN</th>
					<th>NAMA</th>
					<th>HOMEBASE</th>
					<th>Matakuliah Keahliah</th>
				</tr>
			</thead>
			<tbody>
				@if(!$dosen -> count())
				<tr>
					<td colspan="5" align="center">Data Dosen tidak ditemukan</td>
				</tr>
				@else
				<?php $n=0; ?>
				@foreach($dosen as $d)
				<?php $n++; ?>
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $d -> NIDN }}</td>
					<td>
						{{ $d -> gelar_depan }} {{ trim($d -> nama) }} {{ $d -> gelar_belakang }} 
					</td>
					<td>{{ $prodi[$d -> homebase] ?? 'Non-homebase' }}</td>
					<td>
						@if($d -> matkul -> count())
						<ol class="lokasi">
							@foreach($d -> matkul as $m)
							<li>{{ $m -> kode }} - {{ $m -> nama }}</li>
							@endforeach
							@endif
						</ol>
					</td>
				</tr>
				@endforeach				
				@endif
				</tbody>
					</table>
				</div>
	</div>
@endsection												