@extends('app')

@section('title')
Pengajuan Judul Skripsi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pengajuan Judul Skripsi
		<small>Detail</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('jadwal.pengajuan.skripsi.gelombang.peserta', $pengajuan -> gelombang -> id) }}"> Pengajuan Judul Skripsi</a></li>
		<li class="active">Detail</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Detail Pengajuan Judul Skripsi</h3>
		<div class="box-tools">
			<a href="{{ route('mahasiswa.skripsi.pengajuan.recount', $pengajuan -> id) }}" class="btn btn-info btn-xs btn-flat" title="Hitung ulang kesamaan Judul"><i class="fa fa-refresh"></i> Hitung ulang kesamaan Judul</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-12">
				{!! Form::model($pengajuan, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.skripsi.pengajuan.update', $pengajuan -> id]]) !!}
				<div class="form-group">
					{!! Form::label('', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-8">
						<p class="form-control-static"><strong>{{ $pengajuan -> mahasiswa -> nama }} ({{ $pengajuan -> mahasiswa -> NIM }})</strong></p>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('', 'PRODI:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-8">
						<p class="form-control-static">{{ $pengajuan -> mahasiswa -> prodi -> strata }} {{ $pengajuan -> mahasiswa -> prodi -> nama }}</p>
					</div>
				</div>
				<?php
					if($pengajuan -> similarity > $pengajuan -> gelombang  -> jadwal -> max_similarity) $percent = 'danger';
					else $percent = 'success';
				?>
				<div class="form-group">
					{!! Form::label('', 'Judul:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-8">
						<p class="form-control-static">{{ $pengajuan -> judul }}</p>
					</div>
				</div>
				
				@if($pengajuan -> judul_revisi != '')
				<div class="form-group">
					{!! Form::label('', 'Revisi:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-8">
						<p class="form-control-static">
							<span class="text-danger">{{ $pengajuan -> judul_revisi }}</span>
						</p>
					</div>
				</div>
				@endif
				
				<div class="form-group">
					{!! Form::label('', 'Kemiripan Judul:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-8">
						@if(is_array($pengajuan -> similarity_array) && count($pengajuan -> similarity_array))
						<ol class="tim_dosen">
							@foreach($pengajuan -> similarity_array as $sim)
							<?php
								$percent = ($sim['similarity'] > $pengajuan -> gelombang  -> jadwal -> max_similarity) ? 'danger' : 'success';
							?>
							<li>{{ $sim['judul'] }} (<span class="text-{{ $percent }}" style="font-weight:bold">{{ number_format($sim['similarity'], 2, ',', ',') }}%</span>)</li>
							@endforeach
						</ol>
						@else
						<p class="form-control-static">
							{{ $pengajuan -> similar -> judul ?? '' }} (<span class="text-{{ $percent }}" style="font-weight:bold">{{ $pengajuan -> similarity }}%</span>)
						</p>
						@endif
					</div>
				</div>
				
				@if($pengajuan -> judul_revisi != '')
				<div class="form-group">
					{!! Form::label('', 'Kemiripan Judul Revisi:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-8">
						@if(is_array($pengajuan -> similarity2_array) && count($pengajuan -> similarity2_array))
						<ol class="tim_dosen">
							@foreach($pengajuan -> similarity2_array as $sim)
							<?php
								$percent = ($sim['similarity'] > $pengajuan -> gelombang  -> jadwal -> max_similarity) ? 'danger' : 'success';
							?>
							<li>{{ $sim['judul'] }} (<span class="text-{{ $percent }}" style="font-weight:bold">{{ number_format($sim['similarity'], 2, ',', ',') }}%</span>)</li>
							@endforeach
						</ol>
						@endif
					</div>
				</div>
						@endif
				
				<div class="form-group">
					{!! Form::label('', 'Latar Belakang:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-8">
						<p class="form-control-static">{{ $pengajuan -> latar_belakang }}</p>
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('', 'Rumusan masalah:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-8">
						<ol class="tim_dosen">
							@foreach($pengajuan -> rumusan_masalah as $r)
							<li>{{ $r }}</li>
							@endforeach
						</ol>
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('diterima', 'Diterima:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-8">
						<label class="radio-inline">
							{!! Form::radio('diterima', 'y') !!} Terima
						</label>
						<label class="radio-inline">
							{!! Form::radio('diterima', 'n') !!} Tolak
						</label>
						<label class="radio-inline">
							{!! Form::radio('diterima', 'p') !!} Pending
						</label>
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('dosen_id', 'Pembimbing:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-4">
						{!! Form::select('dosen_id', $dosen, null, array('class' => 'form-control chosen-select', 'placeholder' => 'Dosen Pembimbing')) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('keterangan', 'Keterangan:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-8">
						{!! Form::textarea('keterangan', null, array('class' => 'form-control', 'placeholder' => 'Keterangan', 'rows' => '5')) !!}
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-8">
						<button class="btn btn-primary btn-flat btn-warning" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
					</div>		
				</div>	
			</form>
		</div>
	</div>
</div>
</div>
@endsection		

@push('scripts')
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
	$(".chosen-select").chosen({no_results_text: "Tidak ditemukan hasil pencarian untuk: "});
	});  
	var inputs = document.querySelectorAll( '.upload' );
	Array.prototype.forEach.call( inputs, function( input )
	{
	var label	 = input.nextElementSibling,
	labelVal = label.innerHTML;
	
	input.addEventListener( 'change', function( e )
	{
	var fileName = '';
	if( this.files && this.files.length > 1 )
	fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
	else
	fileName = e.target.value.split( '\\' ).pop();
	
	if( fileName )
	label.querySelector( 'span' ).innerHTML = fileName;
	else
	label.innerHTML = labelVal;
	});
	});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<style>
	.chosen-container{
	font-size: inherit;
	}
	.chosen-single{
	padding: 6px 10px !important;
	box-shadow: none !important;
	border-color: #d2d6de !important;
	background: white !important;
	height: 34px !important;
	border-radius: 0px !important;
	}
	.chosen-drop{
	border-color: #d2d6de !important;	
	box-shadow: none;
	}
</style>
@endpush														