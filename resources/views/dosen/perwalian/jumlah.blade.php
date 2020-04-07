@extends('app')

@section('title')
Jumlah Mahasiswa Perwalian
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Jumlah Mahasiswa Perwalian</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen') }}"> Daftar Dosen</a></li>
		<li class="active">Jumlah Mahasiswa Perwalian</li>
	</ol>
</section>
@endsection

@section('content')
<style>
	th{
	text-align: center;
	vertical-align: middle !important;
	}
</style>
<?php 
	$n = 0;
?>
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Jumlah Mahasiswa Perwalian</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="20px" rowspan="2">No.</th>
					<th rowspan="2">NIDN</th>
					<th rowspan="2">NIY</th>
					<th rowspan="2">Nama Dosen</th>
					<th colspan="3">Jumlah Mahasiswa Perwalian</th>
					<th rowspan="2">Total</th>
				</tr>
				<tr>
					<th>Aktif</th>
					<th>Non-Aktif</th>
					<th>Lulus</th>
				</tr>
			</thead>
			<tbody>
				@foreach($dosen as $d)
				<?php 
					$n ++;
				?>
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $d -> NIDN }}</td>
					<td>{{ $d -> NIY }}</td>
					<td>
						<a href="{{ route('dosen.show', $d->id) }}" title="Tampilkan detail data d">
							{{ $d -> gelar_depan }} {{ trim($d -> nama) }}@if(isset($d -> gelar_belakang)), {{ $d -> gelar_belakang }} @endif
						</a>
					</td>
					<td>{{ $d -> aktif }}</td>
					<td>{{ $d -> non_aktif }}</td>
					<td>{{ $d -> lulus }}</td>
					<td>{{ intval($d -> aktif) + intval($d -> non_aktif) + intval($d -> lulus) }}</td>
				</tr>
				@endforeach	
				@foreach($dosen2 as $d)
				<?php 
					$n ++;
				?>
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $d -> NIDN }}</td>
					<td>{{ $d -> NIY }}</td>
					<td>
						<a href="{{ route('dosen.show', $d->id) }}" title="Tampilkan detail data d">
							{{ $d -> gelar_depan }} {{ trim($d -> nama) }}@if(isset($d -> gelar_belakang)), {{ $d -> gelar_belakang }} @endif
						</a>
					</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
				</tr>
				@endforeach				
			</tbody>
		</table>
	</div>
</div>
@endsection						