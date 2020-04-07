@extends('app')

@section('title')
Input Data Bimbingan Skripsi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Skripsi
		<small>Input Data Bimbingan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		@if(\Auth::user() -> role_id == 128)
		<li><a href="{{ url('/skripsi/bimbingan') }}"> Bimbingan</a></li>
		@elseif(\Auth::user() -> role_id == 512)
		<li><a href="{{ url('/skripsi') }}"> Skripsi</a></li>
		@else
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li><a href="{{ url('/mahasiswa/' . $skripsi -> pengarang -> id) }}"> {{ $skripsi -> pengarang -> nama }}</a></li>
		<li><a href="{{ url('/skripsi/' . $skripsi -> id) }}"> Skripsi</a></li>
		@endif
		<li class="active">Input Data Bimbingan</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Data Bimbingan Skripsi</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\BimbinganSkripsi, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.skripsi.bimbingan.store', $skripsi -> id]]) !!}
		@include('mahasiswa/skripsi/bimbingan/partials/_form', ['btn_type' => 'btn-primary'])
		{!! Form::close() !!}
	</div>
</div>

<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Riwayat Bimbingan</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="20px">No.</th>
					<th>Tanggal</th>
					<th width="70%">Perihal</th>
					<th>Oleh</th>
					<th>Disetujui</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$skripsi -> bimbingan -> count())
				<tr>
					<td colspan="4" align="center">Belum ada data bimbingan</td>
				</tr>
				@else
				<?php $c=1; ?>
				@foreach($skripsi -> bimbingan as $b)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $b -> tglBimbingan }}</td>
					<td>{{ $b -> tentang }}</td>
					<td>{{ $b -> author -> authable -> gelar_depan ?? '' }} {{ $b -> author -> authable -> nama }} {{ $b -> author -> authable -> gelar_belakang ?? '' }}</td>
					<td>
						@if($b -> disetujui == 'y')
							<span class="text-success">Ya</span>
						@elseif($b -> disetujui == 'p')
							<span class="text-warning">Pending</span>
						@else
							<span class="text-danger">Tidak</span>
						@endif
					</td>
					<td>
					@if(\Auth::user() -> role_id < 512 || \Auth::user() -> id == $b -> user_id)
						<a class="btn btn-warning btn-xs btn-flat" href="{{ route('mahasiswa.skripsi.bimbingan.edit', [$skripsi -> id, $b -> id]) }}"><i class=" fa fa-edit"></i> Edit</a>
					@endif
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>	
</div>	
@endsection