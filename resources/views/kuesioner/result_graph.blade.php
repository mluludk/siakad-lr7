<?php
	if($dosen_id != null) $qs = '?dosen=' . $dosen_id;
	elseif($prodi_id != null) $qs = '?prodi=' . $prodi_id;
	else $qs = '';
?>

@extends('app')

@section('title')
{{ $title }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Kuesioner
		<small>Hasil Kuesioner {{ $tapel -> nama }}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kuesioner/results') }}"> Hasil Kuesioner</a></li>
		<li><a href="{{ url('/kuesioner/result/' . $tapel -> id) }}">{{ $tapel -> nama }}</a></li>
		<li class="active">Grafik</li>
	</ol>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/highcharts/highcharts.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('/js/highcharts/highcharts.js') }}"></script>
<script src="{{ asset('/js/highcharts/exporting.js') }}"></script>
<script src="{{ asset('/js/highcharts/offline-exporting.js') }}"></script>
<script>
	$(function () {
		$('#chart').highcharts({
			chart: {
				type: 'column'
			},
			title: {
				text: "{{ $title }}" 
			},
			xAxis: {
				categories: [
				@foreach($result as $id => $rs)
				@if($rs['kode'] != '')
				@if($dosen_id != null)
				"{!! $rs['matakuliah'] !!} ({{ $rs['prodi'] . ' ' . $rs['program']}})",
				@else
				"{!! $rs['matakuliah'] !!} ({{ $rs['kode'] }})",
				@endif
				@else
				"{!! $rs['matakuliah'] !!}",
				@endif
				@endforeach
				],
				crosshair: true
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Skala'
				}
			},
			tooltip: {
				headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} poin</b></td></tr>',
				footerFormat: '</table>',
				shared: true,
				useHTML: true
			},
			plotOptions: {
				column: {
					pointPadding: 0.2,
					borderWidth: 0
				}
			},
			series: [
			{
				name: 'Nilai Akhir',
				data: [
				@foreach($result as $id => $rs)
				{{ round($rs['NA'], 2) }},
				@endforeach
				]
			}
			]
		});
	});
</script>
@endpush

@section('content')
<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">{{ $title }}</h3>
		<div class="box-tools">
			<a href="{{ url('/kuesioner/result/' . $tapel -> id . '/graph_print') }}{{ $qs }}" class="btn btn-warning btn-xs btn-flat" title="Print"><i class="fa fa-print"></i></a>
			<a href="{{ url('/kuesioner/result/' . $tapel -> id . '/graph_pdf') }}{{ $qs }}" class="btn btn-danger btn-xs btn-flat" title="PDF"><i class="fa fa-file-pdf-o"></i></a>
			<a href="{{ url('/kuesioner/result/' . $tapel -> id) }}{{ $qs }}" class="btn btn-info btn-xs btn-flat" title="Tabel"><i class="fa fa-table"></i></a>
		</div>
	</div>
	<div class="box-body">
		@if(!count($result))
		<p class="text-muted">Belum ada data</p>
		@else
		<div class="row">
			<div class="col-sm-12">
				<div id="chart" style="width: 100%; height: 400px;"></div>
			</div>
		</div>
		@endif
	</div>
</div>
@endsection																							