@extends('app')

@section('title')
Ekspor Data Nilai Perkuliahan FEEDER V2
@endsection

@section('header')
<section class="content-header">
	<h1>
		Nilai Perkuliahan V2 
		<small>Ekspor Data FEEDER</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Ekspor Data Nilai Perkuliahan FEEDER V2</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter Kelas Perkuliahan</h3>
	</div>
	<div class="box-body">
		{!! Form::open(['url' => url('/export/feeder/nilaikelas'), 'method' => 'get', 'class' => 'form-inline', 'autocomplete' => 'off']) !!}
		<div class="form-group">
			{!! Form::label('prodi_id', 'Prodi:', array('class' => 'sr-only')) !!}
			{!! Form::select('prodi_id', $prodi, Request::get('prodi_id'), ['class' => 'form-control']) !!}
		</div>
		<div class="form-group">
			{!! Form::label('tapel_id', 'Tahun Akademik:', array('class' => 'sr-only')) !!}
			{!! Form::select('tapel_id', $tapel, Request::get('tapel_id'), ['class' => 'form-control']) !!}
		</div>
		<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> FIlter</button>
		{!! Form::close() !!}
	</div>
</div>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Kelas Perkuliahan FEEDER</h3>
		<div class="box-tools">
			<a href="{{ url('/export/feeder/nilaiv1') }}" class="btn btn-warning btn-xs btn-flat" title="Ekspor Nilai V1"><i class="fa fa-cloud-upload"></i> Ekspor Nilai V1</a>
		</div>
	</div>
	<div class="box-body">
		@if($nilai_kelas == null)
		<p>Data tidak ditemukan. Pilih Program Studi dan Tahun Akademik terlebih dahulu.</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #850bf6;">
					<th>No</th>
					<th>Kode MK</th>
					<th>Nama MK</th>
					<th>Nama Kelas</th>
					<th>Bobot MK (SKS)</th>
					<th>Peserta Kelas</th>
					<th>Peserta Sudah Dinilai</th>
				</tr>
			</thead>
			<tbody>
				@foreach($nilai_kelas as $m)
				<?php 
					$id_kls_kuliah = array_key_exists($m -> kode . '-' . $m -> kelas . $m -> kelas2 . '-' . $id_semester, $kls_terdaftar) 
					? $kls_terdaftar[$m -> kode . '-' . $m -> kelas . $m -> kelas2 . '-' . $id_semester] : '0';
					
					$id_kls = explode(':', $id_kls_kuliah)[0];
				?>
				<tr>
					<td>{{ $c }}</td>
					<td>
						@if($id_kls_kuliah == '0')
						{{ $m -> kode }} <i class='fa fa-exclamation-triangle text-danger' data-toggle="popover" data-content="Kelas Kuliah belum terdaftar."></i>
						@else
						<a href="{{ route('export.feeder.nilai.get', [$m -> id_mt, $id_kls]) }}">{{ $m -> kode }}</a>
						@endif
					</td>
					<td>{{ $m -> nama_matkul }}</td>
					<td>{{ $m -> kelas . $m -> kelas2 }}</td>
					<td>{{ $m -> sks }}</td>
					<td>{{ $m -> peserta }}</td>
					<td>{{ $m -> sudah }}</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>
@endsection

@push('scripts')
<script>
	$(function () {
		$('[data-toggle="popover"]').popover({
			html: true,
			placement: 'auto top',
			trigger: 'hover'
		});
	});
</script>
@endpush			