@extends('app')

@section('title')
Skripsi Saya
@endsection

@push('styles')
<style>
	#preview{
	width: 200px;
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
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Skripsi Saya
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Skripsi Saya</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Skripsi Saya</h3>
		<div class="box-tools">
			<a href="{{ route('skripsi.edit', $mahasiswa -> skripsi -> id) }}" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-pencil-square-o"></i> Edit</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-3">
				<img id="preview" src="@if(isset($mahasiswa -> skripsi ->  cover) and $mahasiswa -> skripsi ->  cover != '')/getimage/{{ $mahasiswa -> skripsi ->  cover }} @else/images/s.png @endif"></img>
			</div>
			<div class="col-sm-9">
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label">Nama:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> nama }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">NIM:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> NIM }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">PRODI:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }} {{ $mahasiswa -> kelas -> nama }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Pembimbing:</label>
						<div class="col-sm-9">
							<div class="form-control-static">
								@if(count($mahasiswa -> skripsi ->  pembimbing) > 1)
								<ol>
									@foreach($mahasiswa -> skripsi ->  pembimbing as $pb)
									<li>{{ $pb -> gelar_depan }} {{ $pb -> nama }} {{ $pb -> gelar_belakang }}</li>
									@endforeach
								</ol>
								@elseif(isset($mahasiswa -> skripsi ->  pembimbing[0]))
								{{ $mahasiswa -> skripsi ->  pembimbing[0] -> gelar_depan }} {{ $mahasiswa -> skripsi ->  pembimbing[0] -> nama }} {{ $mahasiswa -> skripsi ->  pembimbing[0] -> gelar_belakang }}
								@endif
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Judul:</label>
						<div class="col-sm-10">
							<p class="form-control-static">{{ $mahasiswa -> skripsi ->  judul }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Abstrak:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> skripsi ->  abstrak }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">File:</label>
						<div class="col-sm-9">
							<p class="form-control-static">
								@if(isset($mahasiswa -> skripsi ->  file))
								<a href="{{ route('skripsi.file', $mahasiswa -> skripsi ->  id) }}?token={{ csrf_token() }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-download"></i> Download</a>
								@endif
							</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Ujian Proposal:</label>
						<div class="col-sm-9">
							<p class="form-control-static">
								@if($mahasiswa -> skripsi ->  validasi_proposal == 'y')
								<a href="{{ route('skripsi.ujian.pendaftaran.print', [$mahasiswa -> skripsi ->  id, 'proposal']) }}" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i> Form Pendaftaran</a>
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
								@if($mahasiswa -> skripsi ->  validasi_kompre == 'y')
								<a href="{{ route('skripsi.ujian.pendaftaran.print', [$mahasiswa -> skripsi ->  id, 'komprehensif']) }}" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i> Form Pendaftaran</a>
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
			<a href="{{ route('mahasiswa.skripsi.bimbingan.create', $mahasiswa -> skripsi ->  id) }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i> Tambah Bimbingan</a>
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
				@if(!$mahasiswa -> skripsi -> bimbingan -> count())
				<tr>
					<td colspan="6" align="center">Belum ada data bimbingan</td>
				</tr>
				@else
				<?php $c=1; ?>
				@foreach($mahasiswa -> skripsi ->  bimbingan as $b)
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
						@if(\Auth::user() -> id == $b -> user_id)
						<a class="btn btn-warning btn-xs btn-flat" href="{{ route('mahasiswa.skripsi.bimbingan.edit', [$mahasiswa -> skripsi ->  id, $b -> id]) }}"><i class=" fa fa-edit"></i> Edit</a>
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