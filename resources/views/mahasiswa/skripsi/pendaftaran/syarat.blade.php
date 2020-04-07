@extends('app')

@section('title')
Persyaratan Pendaftaran Ujian {{ $jenis }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Persyaratan Pendaftaran Ujian {{ $jenis }}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Persyaratan Pendaftaran Ujian {{ $jenis }}</li>
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
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Persyaratan Pendaftaran Ujian {{ $jenis }}</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th rowspan="2" width="20px">NO</th>
					<th rowspan="2">PERSYARATAN YANG HARUS DIPENUHI</th>
					<td colspan="2" style="font-weight: bold; text-align: center;">STATUS</td>
				</tr>
				<tr>
					<th>BELUM TERVALIDASI</th>
					<th>SUDAH TERVALIDASI</th>
				</tr>
			</thead>
			<tbody>
				<?php $n = 1; ?>
				@foreach($tanggungan as $k => $v)
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $v }}</td>
					@if(isset($invalid[$k]))
					<td>
						<i class="fa fa-square-o fa-lg text-danger"></i>
					</td>
					<td></td>
					@else
					<td></td>
					<td>
						@if($k == 'cet' && $k == 'val')
						<i class="fa fa-check-square fa-lg text-success"></i>
						@elseif($k == 'frm' && $k == 'val')
						<i class="fa fa-check-square fa-lg text-success"></i>
						@else
						<i class="fa fa-check-square fa-lg text-success"></i>
						@endif
					</td>
					@endif
				</tr>
				<?php $n++; ?>
				@endforeach
			</tbody>
		</table>
		<br/>
		@if(count($invalid) <= 0) 
		<div style="text-align: center">
			<a href="{{ route('skripsi.ujian.pendaftaran.print', [$skripsi -> id, $jenis]) }}" target="_blank" class="btn btn-success btn-lg btn-flat"><i class="fa fa-print"></i> Daftar Ujian</a>
		</div>
		@endif
	</div>
</div>
@endsection																																																																																																										