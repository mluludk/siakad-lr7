@extends('app')

@section('title')
Update Data Riwayat Pendidikan FEEDER
@endsection

@section('header')
<section class="content-header">
	<h1>
		Riwayat Pendidikan
		<small>Update Data FEEDER</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Update Data Riwayat Pendidikan FEEDER</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter Riwayat Pendidikan</h3>
	</div>
	<div class="box-body">
		{!! Form::open(['url' => url('/update/feeder/riwayat'), 'method' => 'get', 'class' => 'form-inline', 'autocomplete' => 'off']) !!}
		<div class="form-group">
			{!! Form::label('kode_dikti', 'Prodi:', array('class' => 'sr-only')) !!}
			{!! Form::select('kode_dikti', $prodi_select, Request::get('kode_dikti'), ['class' => 'form-control']) !!}
		</div>
		<div class="form-group">
			{!! Form::label('angkatan', 'Angkatan:', array('class' => 'sr-only')) !!}
			{!! Form::select('angkatan', $angkatan_select, Request::get('angkatan'), ['class' => 'form-control']) !!}
		</div>
		<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Go</button>
		{!! Form::close() !!}
	</div>
</div>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Riwayat Pendidikan</h3>
	</div>
	<div class="box-body">
		@if(!count($riwayat))
		<p>Data Riwayat tidak ditemukan. Pilih Program Studi dan Angkatan terlebih dahulu.</p>				
		@else
		<?php 
			$c=1;
		?>	
		<div id="div_button">
			<button type="button" class="btn btn-info btn-flat btn-check">
				<input type="checkbox" class="check-all" value="cb">
				Pilih semua Mahasiswa 
			</button>
		</div>
		{!! Form::open(['url' => url('/update/feeder/riwayat'), 'method' => 'post']) !!}
		<table class="table table-bordered" id="tbl-data">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); 
				background-image: -moz-linear-gradient(#3A8341,#054a10);
				color: white;">
					<th rowspan="2">No.</th>
					<th rowspan="2">NIM</th>
					<th rowspan="2">Nama</th>
					<th colspan="2">Jenis<br/>Pendaftaran</th>
					<th colspan="2">Jalur<br/>Pendaftaran</th>
					<th rowspan="2">Periode</th>
					<th colspan="2">Pembiayaan<br/>Awal</th>
					<th colspan="2">Jumlah<br/>Biaya</th>
				</tr>
				<tr>
					<th>Lokal</th>
					<th>Feeder</th>
					<th>Lokal</th>
					<th>Feeder</th>
					<th>Lokal</th>
					<th>Feeder</th>
					<th>Lokal</th>
					<th>Feeder</th>
				</tr>
			</thead>
			<tbody>
				@foreach($riwayat as $m)
				<tr>
					<td>{{ $c }}</td>
					<td>
						<label>			
							<input type="checkbox" name="dt[]" class="data_cb" 
							value="{{ $m -> id_registrasi_mahasiswa }}:{{ $lokal[$m -> nim]['id_jenis_daftar'] }}:{{ $lokal[$m -> nim]['id_jalur_daftar'] }}:{{ $lokal[$m -> nim]['id_pembiayaan'] }}:{{ $lokal[$m -> nim]['biaya_masuk'] }}" />
							{{ $m -> nim }}
						</label>
					</td>
					<td>{{ $m -> nama_mahasiswa }}</td>
					
					<td>{{ $jenis[$lokal[$m -> nim]['id_jenis_daftar']] ?? '-' }}</td>
					<td>{{ $jenis[$m -> id_jenis_daftar] ?? '-' }}</td>
					
					<td>{{ $jalur[$lokal[$m -> nim]['id_jalur_daftar']] ?? '-' }}</td>
					<td>{{ $jalur[$m -> id_jalur_daftar] ?? '-' }}</td>
					
					<td>{{ $m -> nama_periode_masuk }}</td>
					
					<td>{{ $biaya[$lokal[$m -> nim]['id_pembiayaan']] ?? '-' }}</td>
					<td>{{ $biaya[$m -> id_pembiayaan] ?? '-' }}</td>
					
					<td>{{ formatRupiah($lokal[$m -> nim]['biaya_masuk']) }}</td>
					<td>{{ formatRupiah($m -> biaya_masuk) }}</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
		<button type="button" class="btn btn-info btn-flat btn-check">
			<input type="checkbox" class="check-all" value="cb">
			Pilih semua Mahasiswa 
		</button>
		<hr/>
		<button class="btn btn-primary btn-lg btn-flat"><i class="fa fa-send"></i> Kirim data</button>
		{!! Form::hidden('kode_dikti', $kode_dikti) !!}
		{!! Form::hidden('angkatan', $angkatan) !!}
		{!! Form::close() !!}
		@endif
	</div>
</div>
@endsection		

@include('feeder.lib')									