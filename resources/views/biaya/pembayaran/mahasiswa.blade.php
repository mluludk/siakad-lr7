@extends('app')

@section('title')
Daftar Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Daftar Mahasiswa</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		@if(isset($message))
		<li><a href="{{ url('/mahasiswa') }}"> Daftar Mahasiswa</a></li>
		<li class="active">Pencarian</li>
		@else
		<li class="active">Daftar Mahasiswa</li>
	@endif
	</ol>
</section>
@endsection

@section('content')
<!--h2>Daftar Mahasiswa <a href="{{ route('mahasiswa.create') }}" class="btn btn-info" title="Pendaftaran Mahasiswa Baru"><i class="fa fa-plus"></i></a></h2-->

<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Cari data mahasiswa</h3>
	</div>
	<div class="box-body">
		<form method="post" action="{{ url('/biaya/mahasiswa/search') }}">
			{!! csrf_field() !!}
			<div class="row">
				<div class="col-xs-12">
					<div class="input-group{{ $errors -> has('q') ? ' has-error' : '' }}">
						<input type="text" class="form-control" name="q" placeholder="Pencarian ....">
						<span class="input-group-btn">
							<button class="btn btn-info btn-flat" type="submit">Cari</button>
						</span>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

@if(!$mahasiswa -> count())
<div class="alert alert-info alert-dismissible">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	<h4><i class="icon fa fa-info"></i> Informasi</h4>
	Data mahasiswa tidak ditemukan
</div>
@else
<?php 
	$role_id = \Auth::user() -> role_id; 
	$n = ($mahasiswa -> currentPage() - 1) * $mahasiswa -> perPage();
?>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Mahasiswa</h3>
		<div class="box-tools">
			<a href="{{ route('mahasiswa.create') }}" class="btn btn-info btn-xs btn-flat" title="Input Data"><i class="fa fa-plus"></i></a>
		</div>
	</div>
	<div class="box-body">
		<p class="text-muted">{{ $message ?? '' }}</p>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No</th>
					<th>NIRM</th>
					<th>Nama</th>
					<th>Semester</th>
					<th>Prodi</th>
					<th>Program</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($mahasiswa as $g)
				<?php $n++; ?>
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $g -> NIM }}</td>
					<td>{{ $g -> nama }}</td>
					<td>{{ $g -> semesterMhs }}</td>
					<td>{{ $g -> prodi -> singkatan }}</td>
					<td>{{ $g -> kelas -> nama }}</td>
					<td>
						<a href="{{ route('mahasiswa.show', $g -> id) }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-newspaper-o"></i> Detail</a>
						@if($role_id <= 4)
						<a href="{{ route('biaya.create', $g -> id) }}" class="btn btn-success btn-xs btn-flat"><i class="fa fa-money"></i> Pembayaran</a>
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{!! $mahasiswa -> render() !!}
	</div>
</div>
@endif
@endsection																					