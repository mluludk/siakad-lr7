@extends('app')

@section('title')
Profil Prodi {{ $prodi -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Prodi {{ $prodi -> nama }}
		<small> Profil</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Profil Prodi {{ $prodi -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Profil Prodi {{ $prodi -> nama }}</h3>
	</div>
	<div class="box-body">
		<form class="form-horizontal" role="form">
			<div class="form-group">
				<label class="col-md-2 control-label">Kode</label>
				<div class="col-md-4">
					<p class="form-control-static">{{ $prodi -> kode_dikti }}</p>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label">Jenjang</label>
				<div class="col-md-4">
					<p class="form-control-static">{{ $prodi -> strata }}</p>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label">Nama Prodi</label>
				<div class="col-md-4">
					<p class="form-control-static">{{ $prodi -> nama }} ({{ $prodi -> singkatan }})</p>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label">Wilayah</label>
				<div class="col-md-4">
					<p class="form-control-static">{{ $prodi -> wilayah }}</p>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label">No. SK</label>
				<div class="col-md-4">
					<p class="form-control-static">{{ $prodi -> no_sk }}</p>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label">Tanggal SK</label>
				<div class="col-md-4">
					<p class="form-control-static">{{ $prodi -> tgl_sk }}</p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label">Peringkat</label>
				<div class="col-md-4">
					<p class="form-control-static">{{ $prodi -> peringkat }}</p>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label">Tanggal Daluarsa</label>
				<div class="col-md-4">
					<p class="form-control-static">{{ $prodi -> tgl_sk }}</p>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label">Status Daluarsa</label>
				<div class="col-md-4">
					<p class="form-control-static">
						@if(strtotime($prodi -> tgl_daluarsa) < time())
						<button class="btn btn-danger btn-xs btn-flat">Kadaluarsa</button>
						@else
						<button class="btn btn-success btn-xs btn-flat">Berlaku</button>
						@endif
					</p>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label">Kaprodi</label>
				<div class="col-md-4">
					<p class="form-control-static">{{ $prodi -> kaprodi }}</p>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection	