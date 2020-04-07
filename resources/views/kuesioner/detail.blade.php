@extends('app')

@section('title')
Detail Hasil Kuesioner
@endsection

@section('header')
<section class="content-header">
	<h1>
		Kuesioner
		<small>Detail Hasil Kuesioner {{ $matkul_tapel -> tapel -> nama }}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kuesioner/results') }}"> Hasil Kuesioner</a></li>
		<li><a href="{{ url('/kuesioner/result/' . $matkul_tapel -> tapel -> id) }}"> {{ $matkul_tapel -> tapel -> nama }}</a></li>
		<li class="active">Detail</li>
	</ol>
</section>
@endsection

@push('styles')
<style>
	th{
	padding: 0px !important;
	text-align: center;
	vertical-align: middle !important;
	}
</style>
@endpush

@section('content')

<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Data Dosen</h3>
	</div>
	<div class="box-body">
		<strong>Nama:</strong> {{ $matkul_tapel -> dosen -> nama }}<br/>
		<strong>Mata Kuliah:</strong> {{ $matkul_tapel -> matkul -> nama }}<br/>
		<strong>Tahun Akademik:</strong> {{ $matkul_tapel -> tapel -> nama }}
	</div>
</div>

<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Hasil Kuesioner {{ $matkul_tapel -> tapel -> nama }}</h3>
	</div>
	<div class="box-body">
		<div class="row" style="border: 1px dotted black; margin: 0px; padding: 10px 5px;">
			@foreach(config('custom.kuesioner.skor') as $skor => $keterangan)
			<div class="col-xs-6">
				{{ $skor }} = {{ $keterangan }}
			</div>
			@endforeach
		</div>
		<br/>
		<?php $c = 1; ?>
		<table class=" table table-bordered table-hover">
			<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
				<th>No</th>
				<th>Aspek yang dinilai</th>
				<th>Skor</th>
			</tr>
			@foreach($poin as $kompetensi => $pertanyaan)
			<tr><td colspan="3"><strong>{{ config('custom.kuesioner.kompetensi')[$kompetensi] }}</strong></td></tr>
			@foreach($pertanyaan as $p)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ $p['pertanyaan'] }}</td>
				<td>{{ $p['skor'] }}</td>
			</tr>
			<?php $c++; ?>
			@endforeach
			@endforeach
		</table>
	</div>
</div>
@endsection																	