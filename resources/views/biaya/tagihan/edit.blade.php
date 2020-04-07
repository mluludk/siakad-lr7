@extends('app')
	
@section('title')
Privilege Pembayaran
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Privilege Pembayaran</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Privilege Pembayaran</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Privilege Pembayaran</h3>
	</div>
	<div class="box-body">		
		{!! Form::model($tagihan, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['tagihan.update', $tagihan -> id]]) !!}
		
		<div class="form-group">
			{!! Form::label('', 'Mahasiswa:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-5">
				<div class="form-control-static">{{ $tagihan -> nama }} ({{ $tagihan -> NIM }})</div>
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('', 'Tagihan:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-6">
				<div class="form-control-static">{{ $tagihan -> jenis }}</div>
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('', 'Periode:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-3">
				<div class="form-control-static">{{ $tagihan -> tapel }}</div>
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('', 'Nominal:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-5">
				<div class="form-control-static">Rp {{ number_format($tagihan -> jumlah - $tagihan -> bayar, 0, ',', '.') }}</div>
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('privilege_krs', 'Privilege KRS:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-8">
				<label class="radio-inline">
					{!! Form::radio('privilege_krs', 'y') !!} Ya
				</label>
				<label class="radio-inline">
					{!! Form::radio('privilege_krs', 'n') !!} Tidak
				</label>
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('privilege_uts', 'Privilege UTS:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-8">
				<label class="radio-inline">
					{!! Form::radio('privilege_uts', 'y') !!} Ya
				</label>
				<label class="radio-inline">
					{!! Form::radio('privilege_uts', 'n') !!} Tidak
				</label>
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('privilege_uas', 'Privilege UAS:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-8">
				<label class="radio-inline">
					{!! Form::radio('privilege_uas', 'y') !!} Ya
				</label>
				<label class="radio-inline">
					{!! Form::radio('privilege_uas', 'n') !!} Tidak
				</label>
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('privilege', 'Privilege Login:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-8">
				<label class="radio-inline">
					{!! Form::radio('privilege', 'y') !!} Ya
				</label>
				<label class="radio-inline">
					{!! Form::radio('privilege', 'n') !!} Tidak
				</label>
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
			</div>		
			</div>		
			{!! Form::close() !!}
		</div>
	</div>
@endsection