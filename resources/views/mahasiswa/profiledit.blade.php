@extends('app')

@section('title')
Ubah Data Mahasiswa - {{ $mahasiswa -> nama ?? ''}}
@endsection

@push('scripts')
<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script>
	$(function(){
		$(".date").datepicker({
			format:"dd-mm-yyyy", 
			autoHide:true,
			daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
			daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
			monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
		});
		$(".year").inputmask("y",{"placeholder":"yyyy"});
	});
</script>
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({no_results_text: "Tidak ditemukan hasil pencarian untuk: "});
	});
</script>
@endpush
@push('styles')
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
<style>
	.form-horizontal .control-label {
	text-align: left;
	}
	
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

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>{{ ucwords(strtolower($mahasiswa -> nama)) }}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/profil') }}"> {{ ucwords(strtolower($mahasiswa -> nama)) }}</a></li>
		<li class="active">Update</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Update Profil</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-3 image_upload">
				<div id="validation-errors"></div>
				{!! Form::open(['url' => url('upload/image'), 'class' => 'form-inline', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload']) !!}
				@include('_partials/_foto', ['foto' => $mahasiswa -> foto, 'default_image' => 'b.png'])
				{!! Form::close() !!}
				{!! config('custom.ketentuan.foto') !!}
			</div>
			<div class="col-sm-9">
				{!! Form::model($mahasiswa, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['user.profile.update']]) !!}					
				<div class="form-group">
					{!! Form::label('nama', 'Nama:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-8">
						<p class="form-control-static">{{ $mahasiswa -> nama }}</p>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('jenisKelamin', 'Jenis Kelamin:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-8">
						<?php
							foreach(config('custom.pilihan.jenisKelamin') as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="jenisKelamin" ';
								if(isset($mahasiswa) and $k == $mahasiswa -> jenisKelamin) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>
				<div class="form-group has-feedback{{ ($errors->has('tmpLahir') ?? $errors->has('tglLahir')) ? ' has-error' : '' }}">
					{!! Form::label('tmpLahir', 'TTL:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-10">
						<div style="display:inline-block;">
							{!! Form::text('tmpLahir', null, array('class' => 'form-control', 'placeholder' => 'Tempat Lahir', 'required' => 'required')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::text('tglLahir', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Lahir')) !!}
						</div>
					</div>
				</div>
				<div class="form-group has-feedback{{ $errors->has('NIK') ? ' has-error' : '' }}">
					{!! Form::label('NIK', 'NIK:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-5">
						{!! Form::text('NIK', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Kependudukan (No. KTP)', 'required' => 'required')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('NPWP', 'NPWP:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-5">
						{!! Form::text('NPWP', null, array('class' => 'form-control', 'placeholder' => 'Nomor Pokok Wajib Pajak')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('statusWrgNgr', 'Status Kw:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-8">
						<?php
							foreach(config('custom.pilihan.statusWrgNgr') as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="statusWrgNgr" ';
								if(isset($mahasiswa) and $k == $mahasiswa -> statusWrgNgr) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('wargaNegara', 'Kewarganegaraan:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-5">
						{!! Form::select('wargaNegara', $negara, null, array('class' => 'form-control chosen-select', 'data-placeholder' => 'Pilih Negara')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('agama', 'Agama:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-10">
						<?php
							foreach(config('custom.pilihan.agama') as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="agama" ';
								if(isset($mahasiswa) and $k == $mahasiswa -> agama) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('statusSipil', 'Status Sipil:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-8">
						<?php
							foreach(config('custom.pilihan.statusSipil') as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="statusSipil" ';
								if(isset($mahasiswa) and $k == $mahasiswa -> statusSipil) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>
				
				<div class="form-group has-feedback{{ $errors->has('kelurahan') ? ' has-error' : '' }}">
					{!! Form::label('jalan', 'Alamat:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-10">
						<div style="display:inline-block;">
							{!! Form::text('jalan', null, array('class' => 'form-control', 'placeholder' => 'Jalan', 'style' => 'width: 150px')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::text('rt', null, array('class' => 'form-control', 'placeholder' => 'RT', 'style' => 'width: 80px')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::text('rw', null, array('class' => 'form-control', 'placeholder' => 'RW', 'style' => 'width: 80px')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::text('dusun', null, array('class' => 'form-control', 'placeholder' => 'Dusun / Lingkungan', 'style' => 'width: 150px')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::text('kelurahan', null, array('class' => 'form-control', 'placeholder' => 'Desa / Kelurahan', 'style' => 'width: 150px', 'required' => 'required')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::select('id_wil', $wilayah, null, array('class' => 'form-control chosen-select', 'data-placeholder' => 'Kecamatan')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::text('kodePos', null, array('class' => 'form-control', 'placeholder' => 'Kode Pos', 'style' => 'width: 150px')) !!}
						</div>
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('mukim', 'Jenis Tinggal:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-10 col-xs-10">
						<?php
							foreach(config('custom.pilihan.mukim') as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="mukim" ';
								if(isset($mahasiswa) and $k == $mahasiswa -> mukim) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('transportasi', 'Alat Transportasi:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-3">
						{!! Form::select('transportasi', config('custom.pilihan.transportasi'), null, array('class' => 'form-control')) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('telp', 'Telp. Rumah:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-5">
						{!! Form::text('telp', null, array('class' => 'form-control', 'placeholder' => 'Telepon Rumah')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('hp', 'No. HP:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-5">
						{!! Form::text('hp', null, array('class' => 'form-control', 'placeholder' => 'Nomor HP', 'required' => 'required')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('email', 'Email:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-5">
						<input class="form-control" placeholder="Email" name="email" type="email" id="email">
					</div>
				</div>
				<hr/>
				<div class="form-group has-feedback{{ $errors->has('kps') ? ' has-error' : '' }}">
					{!! Form::label('kps', 'Penerima KPS ?:', array('class' => 'col-sm-2 control-label required')) !!}
					<div class="col-sm-8">
						<div style="display:inline-block; padding-right: 30px; width: 250px;">
							<?php
								foreach(['N' => 'Tidak', 'Y' => 'Ya'] as $k => $v) 
								{
									echo '<label class="radio-inline">';
									echo '<input type="radio" name="kps" ';
									if(isset($mahasiswa) and $k == $mahasiswa -> kps) echo 'checked="checked" ';
									echo 'value="'. $k .'"> '. $v .'</label>';
								}
							?>				</div>
							<div style="display:inline-block;">
								<strong>No KPS: </strong>
							</div>
							<div style="display:inline-block; width: 200px;">
								{!! Form::text('noKps', null, array('class' => 'form-control', 'placeholder' => 'Nomor KPS')) !!}
							</div>
					</div>
				</div>
				<hr/>
				
				<div class="form-group">
					{!! Form::label('NIKAyah', 'NIK Ayah:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-4">
						{!! Form::text('NIKAyah', null, array('class' => 'form-control', 'placeholder' => 'NIK Ayah')) !!}
					</div>
				</div>
				<div class="form-group">
				{!! Form::label('namaAyah', 'Nama Ayah:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-4">
					{!! Form::text('namaAyah', null, array('class' => 'form-control', 'placeholder' => 'Nama Ayah', 'required' => 'required')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('tglLahirAyah', 'Tgl. Lahir Ayah:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-3">
					{!! Form::text('tglLahirAyah', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Lahir Ayah')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('pendidikanAyah', 'Pend. Ayah:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-10">
					<?php
						foreach(config('custom.pilihan.pendidikanOrtu') as $k => $v) 
						{
							echo '<label class="radio-inline">';
							echo '<input type="radio" name="pendidikanAyah" ';
							if(isset($mahasiswa) and $k == $mahasiswa -> pendidikanAyah) echo 'checked="checked" ';
							echo 'value="'. $k .'"> '. $v .'</label>';
						}
					?>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('pekerjaanAyah', 'Pekerjaan Ayah:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-10">
					<?php
						foreach(config('custom.pilihan.pekerjaanOrtu') as $k => $v) 
						{
							echo '<label class="radio-inline">';
							echo '<input type="radio" name="pekerjaanAyah" ';
							if(isset($mahasiswa) and $k == $mahasiswa -> pekerjaanAyah) echo 'checked="checked" ';
							echo 'value="'. $k .'"> '. $v .'</label>';
						}
					?>
				</div>
			</div>	
			<div class="form-group">
				{!! Form::label('penghasilanAyah', 'Penghasilan Ayah:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-10">
					<?php
						foreach(config('custom.pilihan.penghasilanOrtu') as $k => $v) 
						{
							echo '<label class="radio-inline">';
							echo '<input type="radio" name="penghasilanAyah" ';
							if(isset($mahasiswa) and $k == $mahasiswa -> penghasilanAyah) echo 'checked="checked" ';
							echo 'value="'. $k .'"> '. $v .'</label>';
						}
					?>
				</div>
			</div>	
			<hr/>
			<div class="form-group">
				{!! Form::label('NIKIbu', 'NIK Ibu:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-4">
					{!! Form::text('NIKIbu', null, array('class' => 'form-control', 'placeholder' => 'NIK Ibu')) !!}
				</div>
			</div>
			<div class="form-group has-feedback{{ $errors->has('namaIbu') ? ' has-error' : '' }}">
				{!! Form::label('namaIbu', 'Nama Ibu:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-4">
					{!! Form::text('namaIbu', null, array('class' => 'form-control', 'placeholder' => 'Nama Ibu', 'required' => 'required')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('tglLahirIbu', 'Tgl. Lahir Ibu:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-3">
					{!! Form::text('tglLahirIbu', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Lahir Ibu')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('pendidikanIbu', 'Pendidikan Ibu:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-10">
					<?php
						foreach(config('custom.pilihan.pendidikanOrtu') as $k => $v) 
						{
							echo '<label class="radio-inline">';
							echo '<input type="radio" name="pendidikanIbu" ';
							if(isset($mahasiswa) and $k == $mahasiswa -> pendidikanIbu) echo 'checked="checked" ';
							echo 'value="'. $k .'"> '. $v .'</label>';
						}
					?>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('pekerjaanIbu', 'Pekerjaan Ibu:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-10">
					<?php
						foreach(config('custom.pilihan.pekerjaanOrtu') as $k => $v) 
						{
							echo '<label class="radio-inline">';
							echo '<input type="radio" name="pekerjaanIbu" ';
							if(isset($mahasiswa) and $k == $mahasiswa -> pekerjaanIbu) echo 'checked="checked" ';
							echo 'value="'. $k .'"> '. $v .'</label>';
						}
					?>
				</div>
			</div>			
			<div class="form-group">
				{!! Form::label('penghasilanIbu', 'Penghasilan Ibu:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-10">
					<?php
						foreach(config('custom.pilihan.penghasilanOrtu') as $k => $v) 
						{
							echo '<label class="radio-inline">';
							echo '<input type="radio" name="penghasilanIbu" ';
							if(isset($mahasiswa) and $k == $mahasiswa -> penghasilanIbu) echo 'checked="checked" ';
							echo 'value="'. $k .'"> '. $v .'</label>';
						}
					?>
				</div>
			</div>		
			<hr/>
			<div class="form-group">
				{!! Form::label('namaWali', 'Nama Wali:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-4">
					{!! Form::text('namaWali', null, array('class' => 'form-control', 'placeholder' => 'Nama Wali')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('tglLahirWali', 'Tgl. Lahir Wali:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-3">
					{!! Form::text('tglLahirWali', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Lahir Wali')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('pendidikanWali', 'Pendidikan Wali:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-10">
					<?php
						foreach(config('custom.pilihan.pendidikanOrtu') as $k => $v) 
						{
							echo '<label class="radio-inline">';
							echo '<input type="radio" name="pendidikanWali" ';
							if(isset($mahasiswa) and $k == $mahasiswa -> pendidikanWali) echo 'checked="checked" ';
							echo 'value="'. $k .'"> '. $v .'</label>';
						}
					?>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('pekerjaanWali', 'Pekerjaan Wali:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-10">
					<?php
						foreach(config('custom.pilihan.pekerjaanOrtu') as $k => $v) 
						{
							echo '<label class="radio-inline">';
							echo '<input type="radio" name="pekerjaanWali" ';
							if(isset($mahasiswa) and $k == $mahasiswa -> pekerjaanWali) echo 'checked="checked" ';
							echo 'value="'. $k .'"> '. $v .'</label>';
						}
					?>
				</div>
			</div>			
			<div class="form-group">
				{!! Form::label('penghasilanWali', 'Penghasilan Wali:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-10">
					<?php
						foreach(config('custom.pilihan.penghasilanOrtu') as $k => $v) 
						{
							echo '<label class="radio-inline">';
							echo '<input type="radio" name="penghasilanWali" ';
							if(isset($mahasiswa) and $k == $mahasiswa -> penghasilanWali) echo 'checked="checked" ';
							echo 'value="'. $k .'"> '. $v .'</label>';
						}
					?>
				</div>
			</div>					
			<hr/>
			<div class="form-group">
				{!! Form::label('asal_pendidikan', 'Asal Pendidikan:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-3">
					{!! Form::select('asal_pendidikan', config('custom.pilihan.sekolahAsal'), null, array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('thSMTA', 'Lulus SLTA:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-10">
					<div style="display:inline-block;">
						{!! Form::text('thSMTA', null, array('class' => 'form-control year', 'placeholder' => 'Tahun', 'style' => 'width: 80px')) !!}
					</div>
					<div style="display:inline-block;">
						{!! Form::text('jurSMTA', null, array('class' => 'form-control', 'placeholder' => 'Jurusan', 'style' => 'width: 380px')) !!}
					</div>
				</div>
			</div>
			
			<div class="form-group">
				{!! Form::label('jalurMasuk', 'Jalur Masuk:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-7">
					{!! Form::select('jalurMasuk', config('custom.pilihan.jalurMasuk'), null, array('class' => 'form-control')) !!}
				</div>
			</div>
			{!! Form::hidden('foto', null, array('id' => 'foto')) !!}			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
				</div>		
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
</div>
@endsection																																																											