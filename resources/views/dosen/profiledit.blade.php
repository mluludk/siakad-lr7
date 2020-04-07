@extends('app')

@section('title')
Ubah data Dosen - {{ $dosen -> nama ?? 'Invalid'}}
@endsection

@push('styles')
<link href="{{ asset('css/jtsage-datebox.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<style>
	.radio-inline+.radio-inline, .checkbox-inline+.checkbox-inline {
	margin-top: 0;
	margin-left: 0;
	margin-right: 10px;
	}
	.radio-inline:not(first-child){
	margin-right: 10px;
	}	
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
@push('scripts')
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jtsage-datebox.min.js') }}"></script>
<script src="{{ asset('js/jtsage-datebox.i18n.id.utf8.min.js') }}"></script>
<script src="{{ asset('js/jquery.mousewheel.min.js') }}"></script>
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({no_results_text: "Tidak ditemukan hasil pencarian untuk: "});
		
		$('.btn-rem').on('click', function(){
			var vl = $( "#bid_matkul" ).val();
			
			if(vl < 1) return;
			
			$('#h_' + vl).remove();
			$('#' + vl).remove();
		});
		
		$('.btn-add').on('click', function(){
			var tx = $( "#bid_matkul option:selected" ).text();
			var vl = $( "#bid_matkul" ).val();
			
			if(vl < 1) return;
			if($('li#' + vl).length) return;
			if($('.bid_matkul li').length == 4) { alert('Bidang Matkul yang dapat dipilih maksimal 4'); return;}
			
			$( "#bid_matkul" ).after('<input type="hidden" name="bid_matkul[]"  id="h_'+ vl +'"value="'+ vl +'">');
			
			if($('ol.bid_matkul').length)
			{
				$('ol.bid_matkul').append('<li id="'+ vl +'">' + tx + '</li>');
			}
			else
			{
				$( "#bid_matkul" ).before('<ol class="bid_matkul tim_dosen"><li id="'+ vl +'">' + tx + '</li></ol>');
			}
		});
	});
</script>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>{{ $dosen -> nama }}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/profil') }}"> {{ $dosen -> nama }}</a></li>
		<li class="active">Update</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Update Profil</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-3 image_upload">
				<div id="validation-errors"></div>
				{!! Form::open(['url' => url('upload/image'), 'class' => 'form-inline', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload']) !!}
				@include('_partials/_foto', ['foto' => $dosen -> foto, 'default_image' => 'teacher.png'])
				{!! Form::close() !!}
			</div>
			<div class="col-sm-9">
				{!! Form::model($dosen, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['user.profile.update']]) !!}				
				
				<div class="form-group">
					{!! Form::label('kode', 'Kode Dosen:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-2">
						{!! Form::text('kode', null, array('class' => 'form-control', 'placeholder' => 'Kode Dosen')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('nama', 'Nama Lengkap:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-9">
						<div style="display:inline-block;">
							{!! Form::text('gelar_depan', null, array('class' => 'form-control', 'placeholder' => 'Gelar Depan')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama lengkap tanpa Gelar', 'required' => 'required')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::text('gelar_belakang', null, array('class' => 'form-control', 'placeholder' => 'Gelar Belakang')) !!}
						</div>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('NIDN', 'NIDN:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-4 col-xs-9">
						{!! Form::text('NIDN', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Dosen Nasional')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('pns', 'PNS:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-9">
						<?php
							foreach([1 => 'PNS', 2 => 'Non PNS'] as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="pns" ';
								if(isset($dosen) and $k == $dosen -> pns) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>	
				<div class="form-group">
					{!! Form::label('NIP', 'NIP:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-4">
						{!! Form::text('NIP', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Pegawai')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('NIY', 'NIY:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-3">
						{!! Form::text('NIY', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Yayasan')) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('bid_keahlian', 'Bidang Keahlian:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-5">
						{!! Form::select('bid_keahlian', $prodi, null, array('class' => 'form-control')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('bid_matkul', 'Bidang Matkul:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-9">
						<div style="display:inline-block; width: 500px;">
							@if(isset($dosen) and null !== $dosen -> bid_matkul)
							<ol class="bid_matkul tim_dosen">
								@foreach($dosen -> bid_matkul as $b)
								<li id="{{ $b -> id }}">{{ $b -> kode }} - {{ $b -> nama }}</li>
								@endforeach
							</ol>
							@endif
							
							{!! Form::select('bid_matkul', $bid_matkul, null, array('class' => 'form-control chosen-select')) !!}
							
							@if(isset($dosen) and null !== $dosen -> bid_matkul)
							@foreach($dosen -> bid_matkul as $b)
							<input type="hidden" name="bid_matkul[]"  id="h_{{ $b -> id }}"value="{{ $b -> id }}">
							@endforeach
							@endif
						</div>
						<button class="btn btn-success btn-flat btn-add" type="button"><i class="fa fa-plus"></i></button>
						<button class="btn btn-danger btn-flat btn-rem" type="button"><i class="fa fa-minus"></i></button>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('NIK', 'No. KTP:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('NIK', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Kependudukan (NIK) / No. KTP')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('npwp', 'NPWP:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('npwp', null, array('class' => 'form-control', 'placeholder' => 'Nomor Pokok Wajib Pajak')) !!}
					</div>
				</div>				
				<div class="form-group">
					{!! Form::label('no_sk_awal', 'SK Awal Dosen:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-9">
						<div style="display:block; float: left; margin-right: 2px;">
							{!! Form::text('no_sk_awal', null, array('class' => 'form-control', 'placeholder' => 'SK Awal Dosen')) !!}
						</div>
						<div style="display:block; float: left; margin-right: 2px; width: 200px;">
							{!! Form::text('tmt_sk_awal', null, array('class' => 'form-control', 'placeholder' => 'TMT SK Awal Dosen', 'data-role' => "datebox", 'data-options' => '{"mode":"datebox", "useTodayButton":"true"}')) !!}
						</div>
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('no_sk_terbaru', 'SK Terbaru Dosen:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-6">
						<div style="display:block; float: left; margin-right: 2px;">
							{!! Form::text('no_sk_terbaru', null, array('class' => 'form-control', 'placeholder' => 'SK Terbaru Dosen')) !!}
						</div>
						<div style="display:block; float: left; margin-right: 2px; width: 200px;">
							{!! Form::text('tmt_sk_terbaru', null, array('class' => 'form-control', 'placeholder' => 'TMT SK Terbaru Dosen', 'data-role' => "datebox", 'data-options' => '{"mode":"datebox", "useTodayButton":"true"}')) !!}
						</div>
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('instansi', 'Instansi yang mengangkat:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-3">
						{!! Form::select('instansi', config('custom.pilihan.emis.instansi'), null, array('class' => 'form-control')) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('statusDosen', 'Status Tugas:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-9">
						<?php
							foreach(config('custom.pilihan.statusDosen') as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="statusDosen" ';
								if(isset($dosen) and $k == $dosen -> statusDosen) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>	
				<div class="form-group">
					{!! Form::label('status_keaktifan', 'Status Keaktifan (EMIS):', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-9">
						<?php
							foreach(config('custom.pilihan.emis.status_keaktifan') as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="status_keaktifan" ';
								if(isset($dosen) and $k == $dosen -> status_keaktifan) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>		
				<div class="form-group">
					{!! Form::label('jabatan_tambahan', 'Jabatan Tambahan:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-5">
						{!! Form::select('jabatan_tambahan', config('custom.pilihan.emis.jabatan_tambahan'), null, array('class' => 'form-control')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('statusKepegawaian', 'Status Kepeg.:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-9">
						<?php
							foreach(config('custom.pilihan.statusKepegawaian') as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="statusKepegawaian" ';
								if(isset($dosen) and $k == $dosen -> statusKepegawaian) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('tunjangan_profesi', 'Tunjangan Profesi:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-9">
						<?php
							foreach(['Belum menerima', 'Sudah menerima'] as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="tunjangan_profesi" ';
								if(isset($dosen) and $k == $dosen -> tunjangan_profesi) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>	
				<div class="form-group">
					{!! Form::label('besar_tunjangan_profesi', 'Besar Tunjangan Profesi:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-4">
						<div class="input-group">
							<span class="input-group-addon">Rp</span>
							<input class="form-control" placeholder="Besar Tunjangan Profesi" name="besar_tunjangan_profesi" type="number" min="0" max="9999999999" step="100" id="besar_tunjangan_profesi">
						</div>
					</div>
				</div>	
				<hr/>
				<div class="form-group">
					{!! Form::label('tmpLahir', 'TTL:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-9">
						<div style="display:block; float: left; margin-right: 2px;">
							{!! Form::text('tmpLahir', null, array('class' => 'form-control', 'placeholder' => 'Tempat Lahir')) !!}
						</div>
						<div style="display:block; float: left; width: 200px;">
							{!! Form::text('tglLahir', null, array('class' => 'form-control', 'placeholder' => 'Tanggal Lahir', 'data-role' => "datebox", 'data-options' => '{"mode":"datebox", "useTodayButton":"true"}')) !!}
						</div>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('jenisKelamin', 'Jenis Kelamin:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-8">
						<?php
							foreach(config('custom.pilihan.jenisKelamin') as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="jenisKelamin" ';
								if(isset($dosen) and $k == $dosen -> jenisKelamin) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('nama_ibu', 'Nama Ibu Kandung:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-4">
						{!! Form::text('nama_ibu', null, array('class' => 'form-control', 'placeholder' => 'Nama Ibu Kandung')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('agama', 'Agama:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-9">
						<?php
							foreach(config('custom.pilihan.agama') as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="agama" ';
								if(isset($dosen) and $k == $dosen -> agama) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('statusSipil', 'Status Sipil:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-8">
						<?php
							foreach(config('custom.pilihan.statusSipil') as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="statusSipil" ';
								if(isset($dosen) and $k == $dosen -> statusSipil) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('nama_p', 'Nama Suami / Istri:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-4">
						{!! Form::text('nama_p', null, array('class' => 'form-control', 'placeholder' => 'Nama Suami / Istri')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('nip_p', 'NIP Suami / Istri:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-4">
						{!! Form::text('nip_p', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Pegawai Suami / Istri')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('tmt_pns_p', 'TMT PNS Suami / Istri:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-3">
						{!! Form::text('tmt_pns_p', null, array('class' => 'form-control', 'placeholder' => 'TMT PNS Suami / Istri', 'data-role' => "datebox", 'data-options' => '{"mode":"datebox", "useTodayButton":"true"}')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('pekerjaan_p', 'Pekerjaan Suami / Istri:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-9">
						<?php
							foreach(config('custom.pilihan.pekerjaanOrtu') as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="pekerjaan_p" ';
								if(isset($dosen) and $k == $dosen -> pekerjaan_p) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>	
				<div class="form-group">
					{!! Form::label('alamat', 'Alamat:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-6">
						{!! Form::textarea('alamat', null, array('class' => 'form-control', 'rows' => '5', 'placeholder' => 'Alamat Lengkap')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('kab', 'Kabupaten:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-9">
						<div style="display:block; float: left; margin-right: 2px;">
							{!! Form::text('kab', null, array('class' => 'form-control', 'placeholder' => 'Kabupaten')) !!}
						</div>
						<div style="display:block; float: left; margin-right: 2px;">
							{!! Form::select('provinsi', config('custom.pilihan.emis.provinsi'), null, array('class' => 'form-control chosen-select')) !!}
						</div>
					</div>
				</div>
			<div class="form-group">
			{!! Form::label('telp', 'Telepon:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-4">
			{!! Form::text('telp', null, array('class' => 'form-control', 'placeholder' => 'Nomor Telepon/HP')) !!}
			</div>
			</div>
			<div class="form-group">
			{!! Form::label('email', 'Email:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-4">
			{!! Form::email('email', null, array('class' => 'form-control', 'placeholder' => 'Email')) !!}
			</div>
			</div>	
			
			
			{!! Form::hidden('foto', null, array('id' => 'foto')) !!}
			<div class="form-group">
			<div class="col-sm-offset-3 col-sm-10">
			<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
			</div>		
			</div>	
			{!! Form::close() !!}
			</div>
			</div>
			</div>
			</div>
			@endsection																																										