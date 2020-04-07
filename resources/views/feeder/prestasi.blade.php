@extends('app')

@section('title')
Ekspor Data Prestasi FEEDER
@endsection

@section('header')
<section class="content-header">
	<h1>
		Prestasi
		<small>Ekspor Data FEEDER</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Ekspor Data Prestasi FEEDER</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter Prestasi</h3>
	</div>
	<div class="box-body">
		{!! Form::open(['url' => url('/export/feeder/prestasi'), 'method' => 'get', 'class' => 'form-horizontal', 'autocomplete' => 'off']) !!}
		<div class="form-group">
			{!! Form::label('kode_dikti', 'Prodi:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-4">
				{!! Form::select('kode_dikti', $prodi_select, Request::get('kode_dikti'), ['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('angkatan', 'Angkatan:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-2">
				{!! Form::select('angkatan', $angkatan_select, Request::get('angkatan'), ['class' => 'form-control']) !!}
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

@if($mahasiswa != null)
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Prestasi</h3>
	</div>
	<div class="box-body">
		<?php 
			$c=1; 
			$jenis = config('custom.pilihan.dikti.jenis_prestasi');
			$tingkat = config('custom.pilihan.dikti.tingkat_prestasi');
		?>
		@if(count($mahasiswa))	
		<button type="button" class="btn btn-danger btn-flat btn-check">
			<input type="checkbox" class="check-all" value="ttd">
			Pilih Prestasi yang <strong>BELUM</strong> terdaftar di Feeder 
		</button>
		@endif		
		{!! Form::open(['url' => url('/export/feeder/prestasi'), 'method' => 'post']) !!}
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); 
				background-image: -moz-linear-gradient(#3A8341,#054a10);
				color: white;">
					<th>No.</th>
					<th>NIM</th>
					<th>Nama</th>
					<th>Bidang</th>
					<th>Tingkat</th>
					<th>Nama Prestasi</th>
					<th>Tahun</th>
					<th>Penyelenggara</th>
					<th>Peringkat</th>
					<th>
						Status Feeder 
						<i class="fa fa-question-circle-o text-default" data-toggle="popover" data-content="
						<i class='fa fa-check text-success'></i>: Prestasi Mahasiswa <strong>SUDAH</strong> terdaftar.<br/>
						<i class='fa fa-exclamation-triangle text-danger'></i>: Prestasi Mahasiswa belum terdaftar.<br/>
						<i class='fa fa-user text-danger'></i>: Mahasiswa belum terdaftar "></i> 
					</th>
				</tr>
			</thead>
			<tbody>
				@if(!count($mahasiswa))
				<tr>
					<td colspan="10">Data tidak ditemukan</td>
				</tr>
				@else
				@foreach($mahasiswa as $m)
				<?php
					$nim = trim($m -> NIM);
					$needle = str_slug($m -> nama) . '-' . str_slug($m -> tahun) . '-' . str_slug($m -> penyelenggara);
					$terdaftar = in_array($needle, $pre_terdaftar) ? true : false;
					$id_mhs = array_key_exists($nim, $mhs_terdaftar) ? explode(':', $mhs_terdaftar[$nim])[0] : '0';
				?>
				<tr class="@if($terdaftar == '0') warning @else success @endif">
					<td>{{ $c }}</td>
					<td>
						<label>
							@if(!$terdaftar)				
							<input type="checkbox" name="dt[]" class="mhs_ttd" value="{{ $id_mhs }}:{{ $nim }}:{{ $m -> id }}" />
							@endif
							{{ $nim }}
						</label>
					</td>
					<td>{{ $m -> nama_mahasiswa }}</td>
					<td>{{ $jenis[$m -> jenis] }}</td>
					<td>{{ $tingkat[$m -> tingkat] }}</td>
					<td>{{ $m -> nama }}</td>
					<td>{{ $m -> tahun }}</td>
					<td>{{ $m -> penyelenggara }}</td>
					<td>{{ $m -> peringkat }}</td>
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
		@if(count($mahasiswa))	
		<button type="button" class="btn btn-danger btn-flat btn-check">
			<input type="checkbox" class="check-all" value="ttd">
			Pilih Mahasiswa yang <strong>BELUM</strong> terdaftar di Feeder 
		</button>
		@endif
		<hr/>
		<button class="btn btn-primary btn-lg btn-flat"><i class="fa fa-upload"></i> Kirim data</button>
		{!! Form::hidden('kode_dikti', $kode_dikti) !!}
		{!! Form::hidden('angkatan', $angkatan) !!}
		{!! Form::close() !!}
	</div>
</div>
@endif
@endsection

@push('scripts')
<script>
	$(function () {
		
		function checkAll(me){
			$(".mhs_" + me.val()).prop('checked', me.prop('checked'));
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