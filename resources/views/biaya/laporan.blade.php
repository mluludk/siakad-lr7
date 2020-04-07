@extends('app')

@section('title')
Laporan Keuangan
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Laporan Keuangan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Laporan Keuangan</li>
	</ol>
</section>
@endsection

@push('scripts')
<script>
	$(document).on('click', '.btn-filter', function(){
		window.location.href="{{ url('/biaya/report') }}/" + $('select[name=angkatan]') .val() + "/" + $('select[name=prodi_id]') .val() + "/" + $('select[name=program_id]') .val() + "/" + $('input[name=jenisPembayaran]:checked') .val();
	});
</script>
@endpush

@section('content')
<div class="row">
	<div class="col-md-4 col-xs-6">
		<div class="box box-warning">
			<div class="box-header with-border">
				<h3 class="box-title">Filter</h3>
			</div>
			<div class="box-body">
				<div role="form">
					<div class="form-group">
						<label for="angkatan">Angkatan</label>
						{!! Form::select('angkatan', $angkatan, $data['angkatan'], ['class' => 'form-control']) !!}
					</div>
					<div class="form-group">
						<label for="prodi_id">PRODI</label>
						{!! Form::select('prodi_id', $prodi, $data['prodi_id'], ['class' => 'form-control']) !!}
					</div>
					<div class="form-group">
						<label for="program_id">Program</label>
						{!! Form::select('program_id', $program, $data['program_id'], ['class' => 'form-control']) !!}
					</div>						
					<div class="form-group">
						<label for="jenisPembayaran">Jenis</label>
						<div>
							<?php
								foreach(config('custom.pilihan.jenisPembayaran') as $k => $v) 
								{
									echo '<label class="radio-inline">';
									echo '<input type="radio" name="jenisPembayaran" ';
									if($k == $data['jenisPembayaran']) echo 'checked="checked" ';
									echo 'value="'. $k .'"> '. $v .'</label>';
								}
							?>
						</div>
					</div>
					<button class="btn btn-warning btn-flat btn-filter"><i class="fa fa-filter"></i> Filter</button>
				</div>
			</div>
		</div>
	</div>
	<!--div class="col-md-3 col-xs-6">
		<div class="box box-danger">
			<div class="box-header with-border">
			<h3 class="box-title">Keterangan</h3>
			</div>
			<div class="box-body">
				<div role="form">
					<div class="form-group">
						<label for="">01: Biaya yang sudah dibayar</label>
					</div>
					<div class="form-group">
						<label for="">02: Tunggakan</label>
					</div>
					<div class="form-group">
						<label for="">03: Keterangan</label>
					</div>
					<button class="btn btn-danger btn-flat"><i class="fa fa-print"></i> Cetak Laporan</button>
				</div>
			</div>
		</div>		
	</div-->
	
</div>
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Laporan Keuangan</h3>
	</div>
	<div class="box-body">
		
	</div>
</div>
@endsection																																																																			