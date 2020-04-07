@extends('app')

@section('title')
Import Database
@endsection

@section('header')
<section class="content-header">
	<h1>
		Backup
		<small>Import Database</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/backup') }}"> Backup</a></li>
		<li class="active">Import</li>
	</ol>
</section>
@endsection

@push('styles')
<style>
	ul{
	margin: 0px;
	padding-left: 17px;
	}
</style>
@endpush

@section('content')
<div class="callout callout-danger">
	<h4>Peringatan! </h4>
	<ul>
		<li>Proses ini akan menimpa <strong>DATABASE {{ config('custom.app.abbr') }} {{ config('custom.app.version') }}</strong> yang ada. </li>
		<li>Pastikan anda sudah mempunyai cadangan file <strong>DATABASE {{ config('custom.app.abbr') }} {{ config('custom.app.version') }}</strong>. </li>
		<li>Pastikan anda meng-upload cadangan file <strong>DATABASE {{ config('custom.app.abbr') }} {{ config('custom.app.version') }}</strong> yang benar. </li>
		<li>Kesalahan dalam melakukan operasi ini dapat mengakibatkan kerusakan Sistem. </li>
	</ul>
</div>
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Import Database</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\Backup, ['class' => 'form-horizontal', 'files' => true, 'role' => 'form', 'route' => ['backup.import']]) !!}
				<div class="form-group">
					{!! Form::label('dbfile', 'File:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-10">
						<input type="file" name="dbfile" class="form-control"/>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-9">
						<button class="btn btn-danger btn-flat btn-success" type="submit" id="post"><i class="fa fa-cloud-download"></i> Import</button>
					</div>		
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection