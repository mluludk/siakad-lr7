@extends('app')

@section('title')
Daftar Skripsi
@endsection

@push('styles')
<style>
	th{
	text-align: center;
	vertical-align: middle !important;
	}
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Skripsi
		<small>Daftar Skripsi</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Skripsi</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Pencarian Skripsi</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/skripsi/search') }}">
			{!! csrf_field() !!}
			<div class="row">
				<div class="col-xs-12">
					<div class="input-group{{ $errors -> has('q') ? ' has-error' : '' }}">
						<input type="text" class="form-control" name="q" placeholder="Nama / NIM / Judul Skripsi" value="{{ Request::get('q') }}">
						<span class="input-group-btn">
							<button class="btn btn-info btn-flat" type="submit"><i class="fa fa-search"></i> Cari</button>
						</span>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Skripsi</h3>
		<div class="box-tools">
			<a href="{{ route('skripsi.create') }}" class="btn btn-primary btn-xs btn-flat"><i class=" fa fa-plus"></i> Tambah Skripsi</a>
		</div>
	</div>
	<div class="box-body">
		@if(!$skripsi->count())
		<p class="text-muted">Data Skripsi tidak ditemukan</p>
		@else
		<p class="text-muted">{{ $message ?? '' }}</p>
		<?php $c=($skripsi -> currentPage() - 1) * $skripsi -> perPage();; ?>
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,3 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th rowspan="2">No</th>
					<th width="500px" rowspan="2">Judul</th>
					<th colspan="2">Oleh</th>
					<th rowspan="2">Pembimbing</th>
					<td colspan="3" style="text-align: center;vertical-align: middle !important;font-weight: bold;">Validasi</td>
					<th rowspan="2" width="70px"></th>
				</tr>
				<tr>
					<th>Mahasiswa</th>
					<th>NIM</th>
					<th>Proposal</th>
					<th>Kompre</th>
					<th>Skripsi</th>
				</tr>
			</thead>
			<tbody>
				@foreach($skripsi as $g)
				<?php $c++; ?>
				<tr>
					<td>{{ $c }}</td>
					<td><a href="{{ route('skripsi.show', $g -> skripsi_id) }}" title="Tampilkan Skripsi">{{ $g -> judul }}</a></td>
					<td>
						@if(isset($g -> nama))<a href="{{ route('mahasiswa.show', $g -> mahasiswa_id) }}" title="Data Mahasiswa">{{ $g ->  nama}}</a>@endif
					</td>
					<td>
						@if(isset($g -> nama))<a href="{{ route('mahasiswa.show', $g -> mahasiswa_id) }}" title="Data Mahasiswa">{{ $g -> NIM ?? '' }}</a>@endif
					</td>
					<td>
						@if(count($g -> pembimbing) > 1)
						<ol class="tim_dosen">
							@foreach($g -> pembimbing as $pb)
							@if($pb -> nama != '')
							<li><a href="{{ route('dosen.show', $pb -> id) }}" title="Data Dosen">{{ $pb -> gelar_depan }} {{ $pb -> nama }} {{ $pb -> gelar_belakang }}</a></li>
							@endif
							@endforeach
						</ol>
						@elseif(isset($g -> pembimbing[0]))
						<a href="{{ route('dosen.show', $g -> pembimbing[0] -> id) }}" title="Data Dosen">{{ $g -> pembimbing[0] -> gelar_depan }} {{ $g -> pembimbing[0] -> nama }} {{ $g -> pembimbing[0] -> gelar_belakang }}</a>
						@endif						
					</td>
					<td>
						@if($g -> validasi_proposal == 'y')
						<i class="fa fa-check-square fa-lg text-success"></i>
						@else
						<i class="fa fa-square-o fa-lg text-danger"></i>
						@endif
					</td>
					<td>
						@if($g -> validasi_kompre == 'y')
						<i class="fa fa-check-square fa-lg text-success"></i>
						@else
						<i class="fa fa-square-o fa-lg text-danger"></i>
						@endif
					</td>
					<td>
						@if($g -> validasi_kompre == 'y')
						<i class="fa fa-check-square fa-lg text-success"></i>
						@else
						<i class="fa fa-square-o fa-lg text-danger"></i>
						@endif
					</td>
					<td>
						<a href="{{ route('skripsi.edit', $g -> skripsi_id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data Skripsi"><i class="fa fa-pencil-square-o"></i></a>
						<a href="{{ route('skripsi.delete', $g -> skripsi_id) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Hapus Skripsi"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{!! $skripsi -> render() !!}
	</div>
</div>
@endif
@endsection																				