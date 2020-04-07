@extends('app')

@section('title')
Ekspor Data Nilai FEEDER V2
@endsection

@section('header')
<section class="content-header">
	<h1>
		Nilai Perkuliahan V2
		<small>Ekspor Data FEEDER</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/export/feeder/nilaikelas?tapel_id='. $kelas -> ta2 .'&prodi_id=' . $kelas -> kode_dikti) }}">Ekspor Data Nilai Perkuliahan FEEDER V2</a></li>
		<li class="active">{{ $kelas -> matkul }} {{ $kelas -> semester }}{{ $kelas -> kelas }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Kelas Kuliah</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-xs-12">
				<table width="100%">
					<tbody><tr>
						<th width="20%">Program Studi</th><th width="2%">:</th><td width="30%">{{ $kelas -> strata }} {{ $kelas -> prodi }}</td>
						<th width="20%">Semester</th><th width="2%">:</th><td width="30%">{{ $kelas -> ta }}</td>
					</tr>
					<tr>
						<th>Mata Kuliah</th><th>:</th><td>{{ $kelas -> matkul }} ({{ $kelas -> sks_total }} sks)</td>
						<th>Nama Kelas</th><th>:</th><td>{{ $kelas -> semester }}{{ $kelas -> kelas }}</td>
					</tr>
					</tbody></table>
			</div>
		</div>		
	</div>
</div>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Data Nilai</h3>
		<div class="box-tools">
			<a href="{{ url('/export/feeder/nilaiv1') }}" class="btn btn-warning btn-xs btn-flat" title="Ekspor Nilai V1"><i class="fa fa-cloud-upload"></i> Ekspor Nilai V1</a>
		</div>
	</div>
	<div class="box-body">	
		@if(!$nilai -> count())
		<p>Belum ada Data</p>
		@else
		<button type="button" class="btn btn-danger btn-flat btn-check">
			<input type="checkbox" class="check-all" value="ttd">
			Pilih Nilai yang <strong>BELUM</strong> terdaftar di Feeder 
		</button>		
		<button type="button" class="btn btn-success btn-flat btn-check">
			<input type="checkbox" class="check-all" value="td">
			Pilih Nilai yang <strong>SUDAH</strong> terdaftar di Feeder 
		</button>
		{!! Form::open(['url' => url('/export/feeder/nilai'), 'method' => 'post']) !!}
		<?php $c=1; ?>
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,3 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #850bf6;">
					<th rowspan="2">No</th>
					<th rowspan="2">NIM</th>
					<th rowspan="2">Nama</th>
					<th rowspan="2">PRODI</th>
					<th rowspan="2">Angkatan</th>
					<td colspan="2"><strong>Nilai</strong></td>
					<th rowspan="2">Status Feeder <i class="fa fa-question-circle-o text-info" data-toggle="popover" data-content="
						<i class='fa fa-check text-success'></i>: Nilai <strong>SUDAH</strong> terdaftar. <br/>
						<i class='fa fa-exclamation-triangle text-danger'></i>: Nilai belum terdaftar. <br/>
						<i class='fa fa-user text-danger'></i> : Mahasiswa belum terdaftar. <br/>
					"></i>
					</th>
				</tr>
				<tr>
					<th>Angka</th>
					<th>Huruf</th>
				</tr>
			</thead>
			<tbody>
				@foreach($nilai as $m)
				<?php
					$terdaftar = array_key_exists($m -> NIM . '-' . $m -> kode . '-' . $m -> kelas . $m -> kelas2, $krs_terdaftar) ? true : false;					
					$id_reg_mhs = array_key_exists($m -> NIM, $mhs_terdaftar) ? $mhs_terdaftar[$m -> NIM] : '0';
					
					$huruf = ($m -> nilai != '' and $m -> nilai != '-') ? $m -> nilai : '-';
					$indeks = ($m -> nilai != '' and $m -> nilai != '-') ? config('custom.konversi_nilai.base_4')[$m -> nilai] : '0';
					$angka = ($m -> nilai != '' and $m -> nilai != '-') ? config('custom.konversi_nilai.base_100')[$m -> nilai] : '0';
				?>
				<tr class="@if($terdaftar) success @else warning @endif">
					<td>{{ $c }}</td>
					<td>
						@if(!$terdaftar)						
						@if($id_reg_mhs != '0')
						<input type="checkbox" name="dt[]" class="nilai_ttd" 
						value="{{ $id_kelas }}:{{ $id_reg_mhs }}:{{ $huruf }}:{{ $indeks }}:{{ $angka }}:ttd" />
						@endif						
						@else
						<input type="checkbox" name="dt[]" class="nilai_td" 
						value="{{ $id_kelas }}:{{ $id_reg_mhs }}:{{ $huruf }}:{{ $indeks }}:{{ $angka }}:td" />
						@endif
						{{ $m -> NIM }}
						</td>
					<td>{{ $m -> nama_mahasiswa }}</td>
					<td>{{ $m -> strata . ' ' . $m -> nama_prodi }}</td>
					<td>{{ $m -> angkatan }}</td>
					<td>{{ $angka }}</td>
					<td>{{ $huruf }} ({{ $indeks }})</td>
					<td>
						@if($terdaftar) 
						<i class="fa fa-check text-success"></i> 
						@else
						<i class="fa fa-exclamation-triangle text-danger"></i> 
						@endif
						
						@if($id_reg_mhs == '0')
						<i class="fa fa-user text-danger"></i> 
						@endif
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>	
		<button type="button" class="btn btn-danger btn-flat btn-check">
			<input type="checkbox" class="check-all" value="ttd">
			Pilih Nilai yang <strong>BELUM</strong> terdaftar di Feeder 
		</button>
		<button type="button" class="btn btn-success btn-flat btn-check">
			<input type="checkbox" class="check-all" value="td">
			Pilih Nilai yang <strong>SUDAH</strong> terdaftar di Feeder 
		</button>
		<hr/>
		<button class="btn btn-primary btn-lg btn-flat"><i class="fa fa-upload"></i> Kirim data</button>
		{!! Form::hidden('mt_id', $kelas -> id) !!}
		{!! Form::hidden('id_kelas', $id_kelas) !!}
		{!! Form::close() !!}		
		@endif
	</div>
</div>
@endsection

@push('scripts')
<script>
	function checkAll(me){
		$(".nilai_" + me.val()).prop('checked', me.prop('checked'));
	}
	
	$(document).on('change', '.check-all', function(){
		checkAll($(this));
	});
	
	$(document).on('click', '.btn-check', function (){
		var cb = $(this).children('.check-all');
		var ck = cb.prop('checked');
		
		cb.prop('checked', !ck);
		checkAll(cb);
	});
	
	$(function () {
		$('[data-toggle="popover"]').popover({
			html: true,
			placement: 'auto top',
			trigger: 'hover'
		});
	});
</script>
@endpush