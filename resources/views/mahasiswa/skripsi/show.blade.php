@extends('app')

@section('title')
Skripsi - {{ $skripsi -> judul }} - {{ $skripsi -> pengarang -> NIM }}
@endsection

@push('styles')
<style>
	#preview{
	width: 166px;
	height: 220px;
	padding: 5px;
	margin: 15px auto;
	border: 1px solid #999;
	position: relative;
	overflow: hidden;
	}
	
	#preview img {
	max-width: 100%;
	max-height: 100%;
	width: auto;
	height: auto;
	position: absolute;
	left: 50%;
	top: 50%;
	-webkit-transform: translate(-50%, -50%);
	-moz-transform: translate(-50%, -50%);
	-ms-transform: translate(-50%, -50%);
	transform: translate(-50%, -50%);
	}
	
	.status{
	width: 100%;
	text-align: center;
	margin-bottom: 10px;
	}
	ol{
	padding-left: 15px;
	margin: 0px;
	}
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Skripsi
		<small>Detail</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li><a href="{{ url('/mahasiswa/' . $skripsi -> pengarang -> id) }}"> {{ ucwords(strtolower($skripsi -> pengarang -> nama)) }}</a></li>
		<li class="active">Detail Skripsi</li>
	</ol>
</section>
@endsection

@section('content')
<div class="row">
	<div class="col-sm-3">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Nama : {{ $skripsi -> pengarang -> NIM }} / {{ ucwords(strtolower($skripsi -> pengarang -> nama)) }}</h3>
			</div>
			<div class="box-body" style="padding-left: 0px;">
				<div id="preview">
					<img src="@if(isset($skripsi -> pengarang->foto) and $skripsi -> pengarang->foto != '')/getimage/{{ $skripsi -> pengarang->foto }} @else/images/b.png @endif"></img>
				</div>
				<div class="status">
					@if($skripsi -> pengarang -> statusMhs == 1)
					<span class="label label-success">{{ config('custom.pilihan.statusMhs')[$skripsi -> pengarang -> statusMhs] }}</span>
					@else
					<span class="label label-default">{{ config('custom.pilihan.statusMhs')[$skripsi -> pengarang -> statusMhs] }}</span>
					@endif
				</div>
				
				<ul class="sidebar-menu-small">
					<li><h5>AKSI CEPAT</h5></li>
					@include('mahasiswa.partials._menu2', ['role_id' => \Auth::user() -> role_id, 'mahasiswa' => $skripsi -> pengarang])
				</ul>
			</div>
		</div>
	</div>
	
	<div class="col-sm-9">
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">Skripsi</h3>
				<div class="box-tools">
					<a href="{{ route('skripsi.edit', $skripsi -> id) }}" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-pencil-square-o"></i> Edit</a>
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-sm-3">
						<img id="preview" src="@if(isset($skripsi -> cover) and $skripsi -> cover != '')/getimage/{{ $skripsi -> cover }} @else/images/s.png @endif"></img>
					</div>
					<div class="col-sm-9">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-2 control-label">Nama:</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{ $skripsi -> pengarang -> nama }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">NIM:</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{ $skripsi -> pengarang -> NIM }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">PRODI:</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{ $skripsi -> pengarang -> prodi -> strata }} {{ $skripsi -> pengarang -> prodi -> nama }} {{ $skripsi -> pengarang -> kelas -> nama }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Pembimbing:</label>
								<div class="col-sm-9">
									<div class="form-control-static">
										@if(count($skripsi -> pembimbing) > 1)
										<ol>
											@foreach($skripsi -> pembimbing as $pb)
											<li>{{ $pb -> gelar_depan }} {{ $pb -> nama }} {{ $pb -> gelar_belakang }}</li>
											@endforeach
										</ol>
										@elseif(isset($skripsi -> pembimbing[0]))
										{{ $skripsi -> pembimbing[0] -> gelar_depan }} {{ $skripsi -> pembimbing[0] -> nama }} {{ $skripsi -> pembimbing[0] -> gelar_belakang }}
										@endif
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Judul:</label>
								<div class="col-sm-10">
									<p class="form-control-static">{{ $skripsi -> judul }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Abstrak:</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{ $skripsi -> abstrak }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">File:</label>
								<div class="col-sm-9">
									<p class="form-control-static">
										@if(isset($skripsi -> file))
										<a href="{{ route('skripsi.file', $skripsi -> id) }}?token={{ csrf_token() }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-download"></i> Download</a>
										@endif
									</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Ujian Proposal:</label>
								<div class="col-sm-9">
									<p class="form-control-static">
										@if($skripsi -> validasi_proposal == 'y')
										<!--
											<button class="btn btn-success btn-flat btn-xs"><i class="fa fa-check"></i> Validasi</button>
											<a href="{{ route('skripsi.ujian.validasi.print', [$skripsi -> id, 'proposal']) }}" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i> Form Validasi</a>
										-->
										<a href="{{ route('skripsi.ujian.pendaftaran.print', [$skripsi -> id, 'proposal']) }}" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i> Form Pendaftaran</a>
										@else
										<span class="text-danger">Belum Validasi</span>
										@endif
									</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Ujian Kompre & Skripsi:</label>
								<div class="col-sm-9">
									<p class="form-control-static">
										@if($skripsi -> validasi_kompre == 'y')
										
										<!--
											<button class="btn btn-success btn-flat btn-xs"><i class="fa fa-check"></i> Validasi</button>
											<a href="{{ route('skripsi.ujian.validasi.print', [$skripsi -> id, 'komprehensif']) }}" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i> Form Validasi</a>
										-->
										<a href="{{ route('skripsi.ujian.pendaftaran.print', [$skripsi -> id, 'komprehensif']) }}" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i> Form Pendaftaran</a>
										@else
										<span class="text-danger">Belum Validasi</span>
										@endif
									</p>
								</div>
							</div>
						</div>				
					</div>	
				</div>			
			</div>	
		</div>	
		
		
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">Bimbingan</h3>
				<div class="box-tools">
					<a href="{{ route('mahasiswa.skripsi.bimbingan.create', $skripsi -> id) }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i> Tambah Bimbingan</a>
					@if($skripsi -> validasi_proposal == 'y')					
					@if($skripsi -> validasi_kompre != 'y')
					<a href="{{ route('skripsi.validasi', [$skripsi -> id, 'komprehensif']) }}" class="btn btn-warning btn-xs btn-flat has-confirmation" data-message="Validasi Mahasiswa {{ $skripsi -> pengarang ->  nama }} untuk Ujian Komprehensif?"><i class="fa fa-check"></i> Validasi Ujian Kompre</a>
					@endif
					
					@else
					<a href="{{ route('skripsi.validasi', [$skripsi -> id, 'proposal']) }}" class="btn btn-warning btn-xs btn-flat has-confirmation" data-message="Validasi Mahasiswa {{ $skripsi -> pengarang -> nama }} untuk Ujian Proposal?"><i class="fa fa-check"></i> Validasi Ujian Proposal</a>
					@endif
				</div>
			</div>
			<div class="box-body">
				<table class="table table-bordered">
					<thead>
						<tr>
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
								<a class="btn btn-warning btn-xs btn-flat" href="{{ route('mahasiswa.skripsi.bimbingan.edit', [$skripsi -> id, $b -> id]) }}"><i class=" fa fa-edit"></i> Edit</a>
							</td>
						</tr>
						<?php $c++; ?>
						@endforeach
						@endif
					</tbody>
				</table>
			</div>	
		</div>	
	</div>	
@endsection																																																																																																									