@extends('app')

@section('title')
Hasil Kuesioner {{ $tapel -> nama }}
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
		<li class="active">{{ $tapel -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Hasil Kuesioner {{ $tapel -> nama }}</h3>
		<div class="box-tools">
			@if(isset($prodi_list))
			@foreach($prodi_list as $k => $v)
			<a href="{{ url('/kuesioner/result/' . $tapel -> id .'/graph') }}?prodi={{ $k }}" class="btn btn-success btn-xs btn-flat" title="Grafik"><i class="fa fa-bar-chart"></i> PRODI {{ $v }}</a>
			@endforeach
			@endif
			<a href="{{ url('/kuesioner/result/' . $tapel -> id .'/graph') }}" class="btn btn-danger btn-xs btn-flat" title="Grafik"><i class="fa fa-bar-chart"></i> Semua PRODI</a>
		</div>
	</div>
	<div class="box-body">
		@if(!count($result))
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c = 1; ?>
		<div class="row">
			<div class="col-xs-12 col-sm-8">
				<table class="table table-bordered">
					<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
						<th>No.</th>
						<th>Dosen</th>
						<th>Kode</th>
						<th>Mata Kuliah</th>
						<th>PRODI</th>
						<th>Program</th>
						<th>NA</th>
						<th></th>
					</tr>
					@foreach($result as $id => $rs)
					<tr>
						<td>{{ $c }}</td>
						<td><a href="{{ url('/kuesioner/result/' . $tapel -> id .'/graph') }}?dosen={{ $rs['dosen_id'] }}" >{{ $rs['dosen'] }}</a></td>
						<td>{{ $rs['kode'] }}</td>
						<td>{{ $rs['matakuliah'] }}</td>
						<td>{{ $rs['prodi'] }}</td>
						<td>{{ $rs['program'] }}</td>
						<td>{{ round($rs['NA'], 2) }}</td>
						<td>
							<!--a href="{{ route('kuesioner.result.detail', $id) }}" class="btn btn-info btn-xs"><i class=" fa fa-newspaper-o"></i> Detail</a-->
							<a href="{{ route('kuesioner.result.detail2', $id) }}" class="btn btn-info btn-xs btn-flat"><i class=" fa fa-newspaper-o"></i> Detail</a>
						</td>
					</tr>
					<?php $c++; ?>
					@endforeach
				</table>
			</div>
		</div>
		@endif
	</div>
</div>
@endsection																				