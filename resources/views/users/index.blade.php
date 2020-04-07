@extends('app')

@section('title')
Daftar Pengguna
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pengguna
		<small>Daftar Pengguna</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Pengguna</li>
	</ol>
</section>
@endsection

@section('content')
<?php 
	$filter = Request::get('filter', 'all'); 
?>

@if($filter !== 'struktural')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Cari data pengguna</h3>
	</div>
	<div class="box-body">
		<form method="get" action="">
			<div class="row">
				<div class="col-xs-12">
					<div class="input-group{{ $errors -> has('q') ? ' has-error' : '' }}">
						<input type="text" class="form-control" name="q" placeholder="Pencarian ...." value="{{ Request::get('q', '') }}">
						<input type="hidden" name="filter" value="{{ $filter }}">
						<span class="input-group-btn">
							<button class="btn btn-info btn-flat" type="submit"><i class="fa fa-search"></i></button>
						</span>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
@endif

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Pengguna</h3>
		<div class="box-tools">
			@if($filter == 'struktural')<a href="{{ route('pengguna.create') }}" class="btn btn-info btn-xs btn-flat" title="Pendaftaran Pengguna Baru"><i class="fa fa-plus"></i></a>@endif
			<div class="btn-group">
				<a href="{{ url('/pengguna/?filter=struktural') }}" class="btn btn-success btn-xs btn-flat" title="Pengguna Struktural"><i class="fa fa-institution"></i></a>
				<a href="{{ url('/pengguna/?filter=dosen') }}" class="btn btn-primary btn-xs btn-flat" title="Pengguna Dosen"><i class="fa fa-briefcase"></i></a>
				<a href="{{ url('/pengguna/?filter=mahasiswa') }}" class="btn btn-danger btn-xs btn-flat" title="Pengguna Mahasiswa"><i class="fa fa-user"></i></a>
			</div>
		</div>
	</div>
	<div class="box-body">
		@if($subtitle !== '')<h3 style="text-align: center; margin-top: 0;">{{ $subtitle }}</h3>@endif
		@if(!$users->count())
		@if(isset($message))
		<p class="text-muted">{{ $message }}</p>
		@else
		<p class="text-muted">Tidak ditemukan hasil</p>
		@endif
		@else
		<p class="text-muted">{{ $message ?? '' }}</p>
		<div class="row">
			<div class="col-sm-12">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Username</th>
							<th>Nama</th>
							@if($filter == 'struktural')
							<th>Telp/HP</th>
							<th>Bidang Tugas</th>
							@endif
							@if($filter == 'dosen')
							<th>Telp/HP</th>
							<th>Pendidikan</th>
							@endif
							@if($filter == 'mahasiswa' ?? $filter == 'all')
							<th>NIM</th>
							<th>Prodi</th>
							<th>Kelas</th>
							@endif
							<th> </th>
						</tr>
					</thead>
					<tbody>
						@foreach($users as $user)
						<?php 
							if(strtolower(Auth::user() -> role -> name) != 'root' and strtolower($user -> role_name) == 'root') continue; 
						?>
						<tr>
							<td>{{ $user -> username }}</td>
							@if($filter == 'dosen' || $filter == 'struktural')
							<td>{{ $user -> gelar_depan }} {{ $user -> nama }} {{ $user -> gelar_belakang }}</td>
							@else
							<td>{{ $user -> nama }}</td>
							@endif
							@if($filter == 'struktural')
							<td>{{ $user -> telp }}</td>
							<td>{{ $user -> role_name }} {{ $user -> sub }}</td>
							@endif
							@if($filter == 'dosen')
							<td>{{ $user -> telp }}</td>
							<td>
								@if(isset($user -> jurS3) and $user -> jurS3 != '')
								S3 {{ $user -> jurS3 }} @if($user -> fakS3 != '') Fakultas {{ $user -> fakS3 }}@endif {{ $user -> univS3 }}
								@elseif(isset($user -> jurS2) and $user -> jurS2 != '')
								S2 {{ $user -> jurS2 }} @if($user -> fakS2 != '') Fakultas {{ $user -> fakS2 }}@endif {{ $user -> univS2 }}
								@elseif(isset($user -> jurS1) and $user -> jurS1 != '')
								S1 {{ $user -> jurS1 }} @if($user -> fakS1 != '') Fakultas {{ $user -> fakS1 }}@endif {{ $user -> univS1 }}
								@endif
							</td>
							@endif
							@if($filter == 'mahasiswa' ?? $filter == 'all')
							<td>{{ $user -> NIM }}</td>
							<td>{{ $user -> strata }} {{ $user -> prodi }}</td>
							<td>{{ $user -> kelas }}</td>
							@endif
							<td>
							{!! Form::open(array('class' => 'form-inline', 'method' => 'DELETE', 'route' => array('pengguna.destroy', $user -> user_id))) !!}
							<a href="{{ route('pengguna.show', $user -> user_id) }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-newspaper-o"></i> Detail</a>
							<a href="{{ route('pengguna.edit', $user -> user_id) }}" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-pencil-square-o"></i> Edit</a>
							
							@if(Auth::user() -> role_id <= 2)
							<a href="{{ route('users.impersonate', $user -> user_id) }}" class="btn btn-info btn-xs btn-flat" title="Login As {{ $user -> username }}"><i class="fa fa-sign-in"></i> Login</a>
							@endif
							
							<button type="submit" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i> Delete</button>
							{!! Form::close() !!}
							</td>
							</tr>
							@endforeach
							</tbody>
							</table>
							{!! $users  -> appends([
							'filter' => Request::get('filter')
							])-> render() !!}
							</div>
							</div>
							@endif
							</div>
							</div>
							@endsection																																																