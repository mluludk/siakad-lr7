@extends('app')

@section('title')
Hasil Kuesioner
@endsection

@section('header')
<section class="content-header">
	<h1>
		Kuesioner
		<small>Hasil Kuesioner</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Hasil Kuesioner</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Hasil Kuesioner</h3>
	</div>
	<div class="box-body">
		@if(!$tapel->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c = 1; ?>
		<div class="row">
			<div class="col-xs-12 col-sm-5">
				<table class="table table-bordered">
					<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
						<th>No.</th>
						<th>Tahun Akademik</th>
						<th></th>
					</tr>
					@foreach($tapel as $smt)
					<tr>
						<td>{{ $c }}</td>
						<td>{{ $smt -> nama }}</td>
						<td>
							<a href="{{ route('kuesioner.result', $smt->id) }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-table"></i> Tabel</a>
							<a href="{{ url('/kuesioner/result/' . $smt -> id .'/graph') }}" class="btn btn-danger btn-flat btn-xs"><i class="fa fa-bar-chart"></i> Grafik</a>
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