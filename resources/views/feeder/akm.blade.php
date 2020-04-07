@extends('app')

@section('title')
Ekspor Data AKM FEEDER
@endsection

@section('header')
<section class="content-header">
	<h1>
		AKM
		<small>Ekspor Data FEEDER</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Ekspor Data AKM FEEDER</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter AKM</h3>
	</div>
	<div class="box-body">
		{!! Form::open(['url' => url('/export/feeder/akm'), 'method' => 'get', 'class' => 'form-inline', 'autocomplete' => 'off']) !!}
		<div class="form-group">
			{!! Form::label('kode_dikti', 'Prodi:', array('class' => 'sr-only')) !!}
			{!! Form::select('kode_dikti', $prodi_select, Request::get('kode_dikti'), ['class' => 'form-control']) !!}
		</div>
		<div class="form-group">
			{!! Form::label('angkatan', 'Angkatan:', array('class' => 'sr-only')) !!}
			{!! Form::select('angkatan', $angkatan_select, Request::get('angkatan'), ['class' => 'form-control']) !!}
		</div>
		<div class="form-group">
			{!! Form::label('tapel_id', 'Tahun Akademik:', array('class' => 'sr-only')) !!}
			{!! Form::select('tapel_id', $tapel_select, Request::get('tapel_id'), ['class' => 'form-control']) !!}
		</div>
		<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
		{!! Form::close() !!}
	</div>
</div>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">AKM</h3>
		@if($kode_dikti !== null)
		<div class="box-tools">
			<a href="{{ route('mahasiswa.akm.recount2', [$kode_dikti, $angkatan, $tapel_id]) }}" class="btn btn-warning btn-xs btn-flat has-confirmation" 
			title="Hitung Ulang Data AKM Mahasiswa" data-message="Apakah anda yakin akan menghitung ulang AKM Mahasiswa berikut?" 
			data-toggle="popover" data-content="Hitung Ulang AKM <strong>HANYA</strong> jika data lokal <strong>BELUM</strong> sesuai">
			<i class="fa fa-refresh"></i> Hitung Ulang AKM</a>
		</div>
		@endif
	</div>
	<div class="box-body">
		@if($mahasiswa == null)
		<p>Data AKM tidak ditemukan. Pilih Program Studi dan Angkatan terlebih dahulu.</p>				
		@else
		<?php $c=1; ?>
		<div id="div_button">
			<button type="button" class="btn btn-danger btn-flat btn-check">
				<input type="checkbox" class="check-all" value="ttd">
				Pilih AKM yang <strong>BELUM</strong> terdaftar di Feeder 
			</button>
			<button type="button" class="btn btn-success btn-flat btn-check">
				<input type="checkbox" class="check-all" value="td">
				Pilih AKM yang <strong>SUDAH</strong> terdaftar di Feeder 
			</button>
		</div>	
		{!! Form::open(['url' => url('/export/feeder/akm'), 'method' => 'post']) !!}
		<table class="table table-bordered" id="tbl-data" style="font-size: 13px;">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); 
				background-image: -moz-linear-gradient(#3A8341,#054a10);
				color: white;">
					<th rowspan="2">No</th>
					<th rowspan="2">NIM</th>
					<th rowspan="2">Nama</th>
					<th colspan="2">Status</th>
					<th colspan="2">IPS</th>
					<th colspan="2">SKS</th>
					<th colspan="2">IPK</th>
					<th colspan="2">SKS Total</th>
					<th colspan="2">SPP</th>
				</tr>
				<tr>
					<th class="ctr" style="background-color: #6ac239;">Lokal</th>
					<th class="ctr" style="background-color: #6ac239;">Feeder</th>
					<th class="ctr" style="background-color: #2c9ce4;">Lokal</th>
					<th class="ctr" style="background-color: #2c9ce4;">Feeder</th>
					<th class="ctr" style="background-color: #2cf0e4;">Lokal</th>
					<th class="ctr" style="background-color: #2cf0e4;">Feeder</th>
					<th class="ctr" style="background-color: #aba3f0;">Lokal</th>
					<th class="ctr" style="background-color: #aba3f0;">Feeder</th>
					<th class="ctr" style="background-color: #efdd0b;">Lokal</th>
					<th class="ctr" style="background-color: #efdd0b;">Feeder</th>
					<th class="ctr" style="background-color: #0befc2;">Lokal</th>
					<th class="ctr" style="background-color: #0befc2;">Feeder</th>
				</tr>
			</thead>
			<tbody>
				@foreach($mahasiswa as $m)
				<?php
					$f = isset($feeder[$m -> NIM]) ? $feeder[$m -> NIM] : '0';
					
					$id_reg_pd = isset($mahasiswa_feeder[$m -> NIM]) ? explode(':', $mahasiswa_feeder[$m -> NIM])[1] : '0';
					
					$status_lokal_array = [1 => 'AKTIF', 9 => 'NON AKTIF', 11 => 'CUTI', 12 => 'CUTI'];
					$status_lokal = isset($status_lokal_array[$m -> status]) ? $status_lokal_array[$m -> status] : '-';
					
					$status_feeder_array = ['A' => 'AKTIF', 'N' => 'NON AKTIF', 'C' => 'CUTI', 'G' => 'DOUBLE-DEGREE'];
					$status_feeder = $f != '0' && isset($status_feeder_array[$f['id_stat_mhs']]) ? $status_feeder_array[$f['id_stat_mhs']] : '-';
					
					switch($m -> status)
					{
						case 1:
						$status = 'A';
						break;
						
						case 11:
						case 12:
						$status = 'C';
						break;
						
						case 9:
						$status = 'N';
						break;
						
						default:
						$status = '-';
						$id_reg_pd = 0;
					}
				?>
				<tr class="@if($f == '0') warning @else success @endif">
					<td>{{ $c }}</td>
					<td>
						<label>
							@if($id_reg_pd != '0')
							@if($f == '0')
							<input type="checkbox" name="dtt[]" class="data_ttd" 
							value="{{ $id_reg_pd }}:{{ $m -> nama2 }}:{{ $m -> ips }}:{{ $m -> jsks }}:{{ $m ->ipk }}:{{ $m -> total_sks }}:{{ $status }}:{{ $m -> spp }}:{{ $m -> NIM }}" />
							@else
							<input type="checkbox" name="dt[]" class="data_td" 
							value="{{ $id_reg_pd }}:{{ $m -> nama2 }}:{{ $m -> ips }}:{{ $m -> jsks }}:{{ $m ->ipk }}:{{ $m -> total_sks }}:{{ $status }}:{{ $m -> spp }}:{{ $m -> NIM }}" />
							@endif
							@else
							@if($status == '-')
							<i class="fa fa-times text-danger" data-toggle="popover" data-content="Data aktivitas perkuliahan hanya di perbolehkan untuk status Aktif (A), Non Aktif (N), Cuti (C) dan sedang Double Degree (G) di FEEDER"></i>
							@endif
							<i class="fa fa-exclamation-triangle text-danger" data-toggle="popover" data-content="Mahasiswa <strong>BELUM</strong> terdaftar"></i>
							@endif
							{{ $m -> NIM }}
						</label>
					</td>
					<td>{{ $m -> nama }}</td>
					<td>{{ $status_lokal }}</td>
					<td>{{ $status_feeder }}</td>
					<td>{{ $m -> ips }}</td>
					<td>{{ $f['ips'] ?? '-' }}</td>
					<td>{{ $m -> jsks }}</td>
					<td>{{ $f['sks_smt'] ?? '-' }}</td>
					<td>{{ $m -> ipk ?? '' }}</td>
					<td>{{ $f['ipk'] ?? '-' }}</td>
					<td>{{ $m -> total_sks }}</td>
					<td>{{ $f['sks_total'] ?? '-' }}</td>
					<td>{{ formatRupiah($m -> spp) }}</td>
					<td>@if($f != '0') {{ formatRupiah($f['biaya_smt']) }} @else - @endif</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
		<button type="button" class="btn btn-danger btn-flat btn-check">
			<input type="checkbox" class="check-all" value="ttd">
			Pilih AKM yang <strong>BELUM</strong> terdaftar di Feeder 
		</button>
		<button type="button" class="btn btn-success btn-flat btn-check">
			<input type="checkbox" class="check-all" value="td">
			Pilih AKM yang <strong>SUDAH</strong> terdaftar di Feeder 
		</button>
		<hr/>
		<button class="btn btn-primary btn-lg btn-flat"><i class="fa fa-upload"></i> Kirim data</button>
		{!! Form::hidden('kode_dikti', $kode_dikti) !!}
		{!! Form::hidden('angkatan', $angkatan) !!}
		{!! Form::hidden('tapel_id', $tapel_id) !!}
		{!! Form::close() !!}
		@endif
	</div>
</div>
@endsection

@include('feeder.lib')										