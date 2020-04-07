@extends('app')

@section('title')
Data Pengguna {{ $user -> authable -> nama ?? '' }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pengguna
		<small> Data Pengguna</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pengguna') }}">Pengguna</a></li>
		<li class="active">{{ $user -> authable -> nama }}</li>
	</ol>
</section>
@endsection

@push('styles')
<style>
	.control-label{
	text-align:left !important;
	}
	.form-group{
	margin-bottom:0px;
	}
	#preview{
	display:block;
	width: 200px;
	padding: 5px;
	margin-bottom: 15px;
	border: 1px solid #999;
	}
</style>
@endpush

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Data Pengguna</h3>
		<div class="box-tools">
			<a href="{{ route('user.profile.edit') }}" class="btn btn-warning btn-xs btn-flat" title="Edit Profil"><i class="fa fa-edit"></i> Update Profil</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-3">
				<img id="preview" src="@if(isset($user -> authable -> foto) and $user -> authable -> foto != '') {{ url('getimage/' . $user -> authable -> foto) }} @else {{ url('images/a.png') }} @endif"></img>
			</div>
			<div class="col-sm-9">
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-md-2 control-label">Nama</label>
						<div class="col-md-4">
							<p class="form-control-static">{{ $user -> authable -> nama }}</p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Telp/HP</label>
						<div class="col-md-4">
							<p class="form-control-static">{{ $user -> authable -> telp }}</p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">E-Mail</label>
						<div class="col-md-4">
							<p class="form-control-static">{{ $user -> authable -> email }}</p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Username</label>
						<div class="col-md-4">
							<p class="form-control-static">{{ $user->username }}</p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Bidang Tugas</label>
						<div class="col-md-4">
							<p class="form-control-static">{{ $user->role->name }}</p>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection	