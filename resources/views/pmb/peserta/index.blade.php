@extends('app')

@section('title')
Pendaftar PMB {{ $pmb -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		PMB
		<small>{{ $pmb -> nama }}</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pmb') }}">PMB</a></li>
		<li class="active">{{ $pmb -> nama }}</li>
	</ol>
</section>
@endsection

@push('styles')
<link href="{{ url('/lightbox2/css/lightbox.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ url('/lightbox2/js/lightbox.min.js') }}"></script>
@endpush

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Pendaftar PMB {{ $pmb -> nama }}</h3>
		<div class="box-tools">
			<a href="{{ route('pmb.export', [$pmb -> id, 'excel']) }}" class="btn btn-success btn-flat btn-xs" title="Ekspor Peserta PMB"><i class="fa fa-file-excel-o"></i> Ekspor Peserta</a>
		</div>
	</div>
	<div class="box-body">
		@if(!$peserta -> count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th rowspan="2" style="width: 30px;">No.</th>
					<th rowspan="2">Kode</th>
					<th colspan="2">Print</th>
					<th colspan="1">Cek</th>
					<th rowspan="2">Nama</th>
					<th rowspan="2">Prodi</th>
					<th rowspan="2">Alamat</th>
					<th rowspan="2" >Telp</th>
					<th rowspan="2">Tanggal Daftar</th>
					<th rowspan="2" width="5%"></th>
				</tr>
				<tr>
					<th class="ctr" style="background-color: #ddd;">Formulir</th>
					<th class="ctr" style="background-color: #a2f5a6;">Kartu</th>
					<th class="ctr" style="background-color: #f0e5e5;">Slip</th>
				</tr>
			</thead>
			<tbody>
				@foreach($peserta as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> kode }}</td>
					<td>
						<a href="{{ route('pmb.peserta.print', ['formulir', $g -> kode]) }}" title="Formulir" target="_blank">Formulir</a>
					</td>
					<td>
						<a href="{{ route('pmb.peserta.print', ['kartu', $g -> kode]) }}" title="Kartu" target="_blank">Kartu</a>
					</td>
					<td>
						@if($g -> slip != '')
						<a href="{{ url('/getimage/' . $g -> slip) }}" class="btn btn-success btn-xs btn-flat" data-lightbox="slip-{{ $g -> id }}" data-title="Slip pembayaran {{ $g -> nama }}"><i class="fa fa-image"></i></a>
						@endif
					</td>
					<td>{{ $g -> nama }}</td>
					<td>{{ $g -> prodi -> singkatan }}</td>
					<td>{{ $g -> alamatMhs }}</td>
					<td>{{ $g -> telpMhs }}</td>
					<td>{{ formatTanggalWaktu($g -> created_at) }}</td>
					<td>
						<a href="{{ route('pmb.peserta.edit', [$pmb -> id, $g -> kode]) }}" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>
						<a href="{{ route('pmb.peserta.delete', [$pmb -> id, $g -> kode]) }}" class="btn btn-danger btn-xs btn-flat has-confirmation"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif
@endsection																																			