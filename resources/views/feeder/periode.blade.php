@extends('app')

@section('title')
Ekspor Data Periode FEEDER
@endsection

@section('header')
<section class="content-header">
	<h1>
		Periode
		<small>Ekspor Data FEEDER</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Ekspor Data Periode FEEDER</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter Periode</h3>
	</div>
	<div class="box-body">
		{!! Form::open(['url' => url('/export/feeder/periode'), 'method' => 'get', 'class' => 'form-horizontal', 'autocomplete' => 'off']) !!}
		<div class="form-group">
			{!! Form::label('kode_dikti', 'Prodi:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-4">
				{!! Form::select('kode_dikti', $prodi_select, Request::get('kode_dikti'), ['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Go</button>
			</div>		
		</div>	
		{!! Form::close() !!}
	</div>
</div>

@if($periode != null)
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Periode</h3>
	</div>
	<div class="box-body">
		<?php 
			$c=1; 
		?>
		@if(count($periode))	
		<button type="button" class="btn btn-danger btn-flat btn-check">
			<input type="checkbox" class="check-all" value="ttd">
			Pilih Periode yang <strong>BELUM</strong> terdaftar di Feeder 
		</button>
		<button type="button" class="btn btn-success btn-flat btn-check">
			<input type="checkbox" class="check-all" value="td">
			Pilih Periode yang <strong>SUDAH</strong> terdaftar di Feeder 
		</button>
		@endif		
		{!! Form::open(['url' => url('/export/feeder/periode'), 'method' => 'post']) !!}
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); 
				background-image: -moz-linear-gradient(#3A8341,#054a10);
				color: white;">
					<th rowspan="2">No.</th>
					<th rowspan="2">Semester</th>
					<th rowspan="2">Program Studi</th>
					<th rowspan="2">Target Mahasiswa Baru</th>
					<th colspan="4">Calon Mahasiswa Baru</th>
					<th rowspan="2">Tanggal Awal Perkuliahan</th>
					<th rowspan="2">Tanggal Akhir Perkuliahan</th>
					<th rowspan="2">Jumlah Minggu Pertemuan</th>
					<th rowspan="2">
						Status Feeder 
						<i class="fa fa-question-circle-o text-default" data-toggle="popover" data-content="
						<i class='fa fa-check text-success'></i>: Setting Periode Perkuliahan<strong>SUDAH</strong> terdaftar.<br/>
						<i class='fa fa-exclamation-triangle text-danger'></i>: Setting Periode Perkuliahan belum terdaftar."></i> 
					</th>
				</tr>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); 
				background-image: -moz-linear-gradient(#3A8341,#054a10);
				color: white;">
					<th>Ikut Seleksi</th>
					<th>Lulus Seleksi</th>
					<th>Daftar Ulang</th>
					<th>Mengundurkan Diri</th>
				</tr>
			</thead>
			<tbody>
				@if(!count($periode))
				<tr>
					<td colspan="10">Data tidak ditemukan</td>
				</tr>
				@else
				@foreach($periode as $m)
				<?php
					$needle = $m -> nama2 . '-' . $id_prodi_feeder;
					$terdaftar = array_key_exists($needle, $per_terdaftar) ? true : false;
					$value = $m -> nama2 . ':' . $id_prodi_feeder . ':' . intval($m -> target_mhs_baru) . ':' . intval($m -> calon_ikut_seleksi) . ':' . 
					intval($m -> calon_lulus_seleksi) . ':' . intval($m -> daftar_sbg_mhs) . ':' . intval($m -> pst_undur_diri) . ':' . $m -> mulai . ':' . $m -> selesai . ':' . 
					intval($m -> jml_mgu_kul);
				?>
				<tr class="@if(!$terdaftar) warning @else success @endif">
					<td>{{ $c }}</td>
					<td>
						<label>				  
							@if(!$terdaftar)				
							<input type="checkbox" name="ptt[]" class="per_ttd" value="{{ $value }}" />
							@else
							<input type="checkbox" name="pt[]" class="per_td" value="{{ $value }}" />
							@endif
							{{ $m -> nama2 }}
						</label>
					</td>
					<td>{{ $m -> strata }} {{ $m -> singkatan }}</td>
					<td>{{ $m -> target_mhs_baru ?? '?' }}</td>
					<td>{{ $m -> calon_ikut_seleksi ?? '?'  }}</td>
					<td>{{ $m -> calon_lulus_seleksi ?? '?'  }}</td>
					<td>{{ $m -> daftar_sbg_mhs ?? '?'  }}</td>
					<td>{{ $m -> pst_undur_diri ?? '?'  }}</td>
					<td>{{ $m -> mulai }}</td>
					<td>{{ $m -> selesai }}</td>
					<td>{{ $m -> jml_mgu_kul ?? '?'  }}</td>
					<td>
						@if($terdaftar) 
						<i class="fa fa-check text-success"></i> 
						@else
						<i class="fa fa-exclamation-triangle text-danger"></i> 
						@endif
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
			</tbody>
		</table>
		@if(count($periode))	
		<button type="button" class="btn btn-danger btn-flat btn-check">
			<input type="checkbox" class="check-all" value="ttd">
			Pilih Periode yang <strong>BELUM</strong> terdaftar di Feeder 
		</button>
		<button type="button" class="btn btn-success btn-flat btn-check">
			<input type="checkbox" class="check-all" value="td">
			Pilih Periode yang <strong>SUDAH</strong> terdaftar di Feeder 
		</button>
		@endif
		<hr/>
		<button class="btn btn-primary btn-lg btn-flat"><i class="fa fa-upload"></i> Kirim data</button>
		{!! Form::hidden('kode_dikti', $kode_dikti) !!}
		{!! Form::close() !!}
	</div>
</div>
@endif
@endsection

@push('styles')
<style>
	th{
	text-align: center !important;
	vertical-align: middle !important;
	}
</style>
@endpush

@push('scripts')
<script>
	$(function () {
		
		function checkAll(me){
			$(".per_" + me.val()).prop('checked', me.prop('checked'));
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
			
			$('[data-toggle="popover"]').popover({
				html: true,
				placement: 'auto top',
				trigger: 'hover'
			});
	});
</script>
@endpush																	