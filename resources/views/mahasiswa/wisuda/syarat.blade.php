@extends('app')

@section('title')
Syarat Pendaftaran Wisuda
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Pendaftaran Wisuda</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Syarat Pendaftaran Wisuda</li>
	</ol>
</section>
@endsection

@section('content')
<style>
	th{
	vertical-align: middle !important;
	}
</style>
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Syarat Pendaftaran Wisuda</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr style="background-color: #70bbb0;">
					<th rowspan="2">NO</th>
					<th rowspan="2">PERSYARATAN YANG HARUS DIPENUHI</th>
					<th colspan="2" td class="ctr"> STATUS</th>
				</tr>
				<tr style="background-color: #70bbb0;">
					<th class="ctr">BELUM TERVALIDASI</th>
					<th class="ctr">SUDAH TERVALIDASI</th>
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
						<i class="fa fa-times text-danger"></i>
						@if($k == 'sks' and $invalid['sks'] == 1)
						<a href="{{ route('mahasiswa.kemajuan', $data -> id) }}">Cek Tanggungan SKS</a>
						@endif
					</td>
					<td></td>
					@else
					<td></td>
					<td><i class="fa fa-check text-success"></i></td>
					@endif
				</tr>
				<?php $n++; ?>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection																																																																																														