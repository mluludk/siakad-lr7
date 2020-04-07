@extends('app')

@section('title')
Ubah Data Mahasiswa - {{ $mahasiswa -> nama ?? ''}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Ubah Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li><a href="{{ route('mahasiswa.show', $mahasiswa -> id) }}"> {{ ucwords(strtolower($mahasiswa -> nama)) }}</a></li>
		<li class="active">Ubah Data</li>
	</ol>
</section>
@endsection

@section('content')
<style>
	.status{
	width: 100%;
	text-align: center;
	margin-bottom: 10px;
	}
	
	.sidebar-menu-small h5{
	text-align: center;
	background-color: #023355;;
	color: white;
	padding: 5px;
	}
	.sidebar-menu-small {
    list-style: none;
    margin: 0;
    padding: 0
	}
	.sidebar-menu-small > li {
    position: relative;
    margin: 0;
    padding: 0
	}
	.sidebar-menu-small > li > a {
    padding: 5px 2px 5px 12px;
    display: block
	}
	.sidebar-menu-small > li > a > .fa{
    width: 20px
	}
	
	.sidebar-menu-small > li > a {
    border-left: 3px solid transparent;
	color: #bbb;
	border-bottom: 1px solid #bbb;
	}
	.sidebar-menu-small > li:hover > a,
	.sidebar-menu-small > li.active > a {
    color: #3c8dbc;
    background: #f5f9fc;
    border-left-color: #3c8dbc
	}
</style>
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Update Profil</h3>
	</div>
	<div class="box-body" style="padding-left: 0px;">
			<div class="col-sm-3 image_upload">
				<div id="validation-errors"></div>
				{!! Form::open(['url' => url('upload/image'), 'class' => 'form-inline', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload']) !!}
				@include('_partials/_foto', ['foto' => $mahasiswa -> foto, 'default_image' => 'b.png'])
				{!! Form::close() !!}
				<div class="status">
					@if($mahasiswa -> statusMhs == 1)
					<span class="label label-success">{{ config('custom.pilihan.statusMhs')[$mahasiswa -> statusMhs] }}</span>
					@else
					<span class="label label-default">{{ config('custom.pilihan.statusMhs')[$mahasiswa -> statusMhs] }}</span>
					@endif
				</div>
				<ul class="sidebar-menu-small">
				<li><h5>AKSI CEPAT</h5></li>
				<li><a href="{{ route('mahasiswa.show', $mahasiswa -> id) }}"> Detail Mahasiswa</a></li>
					@include('mahasiswa.partials._menu2', ['role_id' => \Auth::user() -> role_id, 'mahasiswa' => $mahasiswa])
				</ul>
			</div>
		<div class="col-sm-9">
			{!! Form::model($mahasiswa, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.update', $mahasiswa->id]]) !!}
			@if(!$hasAccount)
			<div class="form-group">
				{!! Form::label('username', 'Username:', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-3 col-xs-6">
					{!! Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'Username')) !!}
				</div>
				</div>
				<div class="form-group">
					{!! Form::label('password', 'Password:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-3">
						<input class="form-control" placeholder="Password" name="password" type="password" id="password">
					</div>
				</div>
				<hr/>
				@endif
				@include('mahasiswa/partials/_form')
				{!! Form::hidden('foto', null, array('id' => 'foto')) !!}
				<hr/>
				<div class="form-group">
					{!! Form::label('statusMhs', 'Status Mahasiswa:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-10">
						<?php
							$disabled = '';
							if(isset($mahasiswa) and ($mahasiswa -> statusMhs == 11 ??  $mahasiswa -> statusMhs == 12)) $disabled = 'disabled="disabled" ';
							foreach(config('custom.pilihan.statusMhs') as $k => $v) 
							{
								if($disabled == '' and $k > 10) break;
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="statusMhs" ' . $disabled;
								if(isset($mahasiswa) and $k == $mahasiswa -> statusMhs) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
						@if($disabled != '')<span class="help-block" style="color: #a94442;">Untuk mengubah status, aktifkan mahasiswa terlebih dahulu pada modul Cuti Mahasiswa</span>@endif
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('noIjazah', 'No. Ijazah:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-4">
						{!! Form::text('noIjazah', null, array('class' => 'form-control', 'placeholder' => 'Nomor Ijazah')) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('tglIjazah', 'Tanggal Ijazah:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-3">
						{!! Form::text('tglIjazah', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Ijazah')) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('tglKeluar', 'Tanggal Keluar:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-3">
						{!! Form::text('tglKeluar', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Keluar')) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('ketKeluar', 'Ket. Kelulusan:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-9">
						{!! Form::textarea('ketKeluar', null, array('class' => 'form-control', 'placeholder' => 'Keterangan Kelulusan', 'rows'=> '3' )) !!}
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button class="btn btn-warning btn-flat btn-lg" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
					</div>		
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection																			