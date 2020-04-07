@extends('app')

@section('title')
SKS Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Total SKS</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen') }}"> Daftar Dosen</a></li>
		<li class="active">Total SKS</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Total SKS Dosen</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="40px">No.</th>
					<th>NAMA</th>
					<th>NIDN</th>
					<th>NIY</th>
					<th>MATAKULIAH</th>
					<th>KET</th>
					<th>PRODI</th>
					<th>KELAS</th>
					<th>SMT</th>
					<th>SKS</th>
					<th>JADWAL</th>
					<th>TOTAL SKS</th>
				</tr>
			</thead>
			@if(!count($ar))
			<tr>
				<td colspan="11" align="center">Data tidak ditemukan</td>
			</tr>
			@else
			<?php $n=0; ?>
			@foreach($ar as $d)
			<?php 
				$row = count($d); 
				$r = $total_sks = 0;
				foreach($d as $t) $total_sks += $t['sks'];
			?>
			<tbody>
				@foreach($d as $j)
				<?php $n++; $r++; ?>
				<tr>
					<td>{{ $n }}</td>
					@if($r == 1)
					<td rowspan="{{ $row }}" style="vertical-align: middle !important;">{{ $j['nama'] }}</td>
					@endif
					@if($r == 1)
					<td rowspan="{{ $row }}" style="vertical-align: middle !important;">{{ $j['nidn'] }}</td>
					@endif
					@if($r == 1)
					<td rowspan="{{ $row }}" style="vertical-align: middle !important;">{{ $j['niy'] }}</td>
					@endif
					<td>{{ $j['matkul'] }}</td>
					<td>{{ $j['ket'] }}</td>
					<td>{{ $j['prodi'] }}</td>
					<td>{{ $j['kelas'] }}</td>
					<td>{{ $j['semester'] }}</td>
					<td>{{ $j['sks'] }}</td>
					<td>{{ $j['jadwal'] }}</td>
					@if($r == 1)
					<td rowspan="{{ $row }}" style="vertical-align: middle !important;
					text-align: center;font-size: 35px;">{{ $total_sks }}</td>
					@endif
				</tr>
				@endforeach
			</tbody>				
			@endforeach				
			@endif
		</table>
	</div>
</div>
@endsection		

@push('styles')
<style>
	// tbody:nth-child(odd) {
	// background-color: #e8f5e8;
	// }
	
	tbody:hover,
	tr.hover,
	th.hover,
	td.hover,
	tr.hoverable:hover {
	background-color: #e8f5e8;
	}
</style>
@endpush