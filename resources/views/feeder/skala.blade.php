@extends('app')

@section('title')
Ekspor Data Skala Nilai FEEDER
@endsection

@section('header')
<section class="content-header">
	<h1>
		Skala Nilai
		<small>Ekspor Data FEEDER</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Ekspor Data Skala Nilai FEEDER</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter Skala Nilai</h3>
	</div>
	<div class="box-body">
		{!! Form::open(['url' => url('/export/feeder/skala'), 'method' => 'get', 'class' => 'form-horizontal', 'autocomplete' => 'off']) !!}
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

@if($skala != null)
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Skala Nilai</h3>
	</div>
	<div class="box-body">
		<?php 
			$c=1; 
		?>
		@if(count($skala))	
		<button type="button" class="btn btn-danger btn-flat btn-check">
			<input type="checkbox" class="check-all" value="ttd">
			Pilih Skala Nilai yang <strong>BELUM</strong> terdaftar di Feeder 
		</button>
		<button type="button" class="btn btn-success btn-flat btn-check">
			<input type="checkbox" class="check-all" value="td">
			Pilih Skala Nilai yang <strong>SUDAH</strong> terdaftar di Feeder 
		</button>
		@endif		
		{!! Form::open(['url' => url('/export/feeder/skala'), 'method' => 'post']) !!}
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); 
				background-image: -moz-linear-gradient(#3A8341,#054a10);
				color: white;">
					<th>No.</th>
					<th>Program Studi</th>
					<th>Nilai Huruf</th>
					<th>Nilai Indeks</th>
					<th>Bobot Minimum</th>
					<th>Bobot Maksimum</th>
					<th>Tanggal Mulai Efektif</th>
					<th>Tanggal Akhir Efektif</th>
					<th>
						Status Feeder 
						<i class="fa fa-question-circle-o text-default" data-toggle="popover" data-content="
						<i class='fa fa-check text-success'></i>: Skala Nilai Perkuliahan<strong>SUDAH</strong> terdaftar.<br/>
						<i class='fa fa-exclamation-triangle text-danger'></i>: Skala Nilai Perkuliahan belum terdaftar."></i> 
					</th>
				</tr>
			</thead>
			<tbody>
				@if(!count($skala))
				<tr>
					<td colspan="9">Data tidak ditemukan</td>
				</tr>
				@else
				@foreach($skala as $m)
				<?php 
					$needle =$id_prodi_feeder . '-' . trim($m -> huruf);
					$terdaftar = array_key_exists($needle, $ska_terdaftar) ? $ska_terdaftar[$needle] : '0';
					$value = $id_prodi_feeder . ':' . $m -> huruf . ':' . $m -> bobot_min . ':' . $m -> bobot_max . ':' . $m -> angka . ':' . $m -> mulai_efektif . ':' . 
					$m -> akhir_efektif;
				?>
				<tr class="@if(!$terdaftar) warning @else success @endif">
					<td>{{ $c }}</td>
					<td>
						<label>				  
							@if($terdaftar == '0')				
							<input type="checkbox" name="stt[]" class="ska_ttd" value="0:{{ $value }}" />
							@else
							<input type="checkbox" name="st[]" class="ska_td" value="{{$terdaftar}}:{{ $value }}" />
							@endif
							{{ $m -> strata }} {{ $m -> singkatan }}
						</label>
					</td>
					<td>{{ $m -> huruf }}</td>
					<td>{{ $m -> angka }}</td>
					<td>{{ $m -> bobot_min }}</td>
					<td>{{ $m -> bobot_max }}</td>
					<td>{{ $m -> mulai_efektif }}</td>
					<td>{{ $m -> akhir_efektif }}</td>
					<td>
						@if($terdaftar != '0') 
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
		@if(count($skala))	
		<button type="button" class="btn btn-danger btn-flat btn-check">
			<input type="checkbox" class="check-all" value="ttd">
			Pilih Skala Nilai yang <strong>BELUM</strong> terdaftar di Feeder 
		</button>
		<button type="button" class="btn btn-success btn-flat btn-check">
			<input type="checkbox" class="check-all" value="td">
			Pilih Skala Nilai yang <strong>SUDAH</strong> terdaftar di Feeder 
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
			$(".ska_" + me.val()).prop('checked', me.prop('checked'));
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