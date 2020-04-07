@extends('app')

@section('title')
Generate Tagihan per-Semester
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Generate Tagihan per-Semester</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('tagihan.index') }}"> Tagihan</a></li>
		<li class="active">Generate Tagihan per-Semester</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Generate Tagihan per-Semester</h3>
	</div>
	<div class="box-body">		
		{!! Form::model(new Siakad\Tagihan, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['tagihan.store']]) !!}

		<div class="form-group">
			{!! Form::label('tapel_id', 'Tahun Akademik:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-2">
				{!! Form::select('tapel_id', $tapel, null, array('class' => 'form-control')) !!}
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('prodi_id', 'Program Studi:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-4">
				{!! Form::select('prodi_id', $prodi, null, array('class' => 'form-control')) !!}
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('angkatan', 'Angkatan:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-2">
				{!! Form::select('angkatan', $angkatan, null, array('class' => 'form-control')) !!}
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button class="btn btn-success btn-flat" type="submit"><i class="fa fa-magic"></i> Generate</button>
			</div>		
		</div>		
		{!! Form::close() !!}
	</div>
</div>
@endsection