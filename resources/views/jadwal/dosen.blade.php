@extends('app')

@section('title')
Jadwal Kuliah
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Jadwal Kuliah</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Jadwal Kuliah</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Jadwal Kuliah</h3>
	</div>
	<div class="box-body">
		<?php $c=1; $total_sks=0;?>
		<table class="table table-bordered table-hover">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No.</th>
					<th>Mata Kuliah</th>
					<th>Ket</th>
					<th>PRODI</th>
					<th>Program</th>
					<th>Dosen</th>
					<th>Semester</th>
					<th>Kelas</th>
					<th>Jadwal</th>
					<th>Ruang</th>
					<th>RPS</th>
					<th>Silabus</th>
				</tr>
			</thead>
			<tbody>
				@if(!$data -> count())
				<td colspan="12" align="center">Belum ada data</td>
				@else
				@foreach($data as $mk)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $mk -> matkul }} ({{ $mk -> kd }})</td>
					<td>{{ $mk -> keterangan }}</td>
					<td>{{ $mk -> strata }} {{ $mk -> nama_prodi }}</td>
					<td>{{ $mk -> program }}</td>
					<td>{{ formatTimDosen($mk -> matkul_tapel -> tim_dosen) }}</td>
					<td>{{ $mk -> semester }}</td>
					<td>{{ $mk -> kelas }}</td>
					<td>
						@if($mk -> hari != '' ){{ config('custom.hari')[$mk -> hari] }}, {{ $mk -> jam_mulai }} - {{ $mk -> jam_selesai }}@else<span>-</span>@endif
					</td>
					<td>{{ $mk -> ruang }}</td>
					<td>@if(isset( $mk -> rpp))<a href="{{ url('/download/' . $mk -> rpp . '/' . csrf_token()) }}" class="btn btn-primary btn-xs" title="Download"><i class="fa fa-download"></i></a>&nbsp;@endif<a href="{{ url('/kelaskuliah/' . $mk -> matkul_tapel_id . '/upload/rpp') }}" class="btn-xs btn btn-danger" title="Upload"><i class="fa fa-upload"></i></a></td>
					<td>@if(isset( $mk -> silabus))<a href="{{ url('/download/' . $mk -> silabus . '/' . csrf_token()) }}" class="btn btn-primary btn-xs" title="Download"><i class="fa fa-download"></i></a>&nbsp;@endif<a href="{{ url('/kelaskuliah/' . $mk -> matkul_tapel_id . '/upload/silabus') }}" class="btn-xs btn btn-danger" title="Upload"><i class="fa fa-upload"></i></a></td>
				</tr>
				<?php $c++; $total_sks += $mk -> sks; ?>
				@endforeach
				<tr>
					<td colspan="10" align="right"><strong>TOTAL SKS</strong></td>
					<td colspan="2"><strong>{{ $total_sks }}</strong></td>
				</tr>
				@endif
			</tbody>
		</table>
	</div>
</div>
@endsection																			