@extends('app')

@section('title')
Daftar Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Daftar Dosen</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		@if(isset($message))
		<li><a href="{{ url('/dosen') }}"> Daftar Dosen</a></li>
		<li class="active">Pencarian</li>
		@else
		<li class="active">Daftar Dosen</li>
		@endif
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Cari data dosen</h3>
	</div>
	<div class="box-body">
		<form method="post" action="{{ url('/dosen/search') }}">
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

@if(!$dosen->count())
<div class="alert alert-info alert-dismissible">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	<h4><i class="icon fa fa-info"></i> Informasi</h4>
	Data dosen tidak ditemukan
</div>
@else
<?php $role_id = \Auth::user() -> role_id; ?>
<div class="row">
	<div class="col-sm-6">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Daftar Dosen</h3>
			</div>
			<div class="box-body">
				<p class="text-muted">{{ $message ?? '' }}</p>
				<table class="table table-bordered table-striped">
					<thead>
						<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
							<th width="5%">Kode</th>
							<th width="20%">Nama</th>
							<th width="10%"></th>
						</tr>
					</thead>
					<tbody>
						@foreach($dosen as $g)
						<tr>
							<td>{{ $g -> kode }}</td>
							<td>{{ $g -> nama }}</td>
							<td>
								<a href="{{ route('dosen.show', $g->id) }}" class="btn btn-primary btn-xs btn-flat" title="Tampilkan data detail"><i class="fa fa-newspaper-o"></i> Detail</a>
								<a href="{{ route('gaji.create', $g -> id) }}" class="btn btn-success btn-xs btn-flat"><i class="fa fa-envelope-o"></i> Gaji</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				{!! $dosen -> render() !!}
			</div>
		</div>
	</div>
</div>
@endif
@endsection																						