@extends('app')

@section('title')
Edit Laporan Bug
@endsection

@section('header')
<section class="content-header">
	<h1>
		Laporan Bug
		<small>Edit Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/report') }}"> Laporan Bug</a></li>
		<li class="active">Edit Laporan Bug</li>
	</ol>
</section>
@endsection

@push('styles')
<style>
	.preview{
	max-width: 100px;
	}
</style>
@endpush

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Laporan Bug</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($bug, ['id' => 'frm', 'method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true, 'route' => ['report.update', $bug->id]]) !!}
				@include('bug/partials/_form')
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<button class="btn btn-primary btn-flat btn-warning" type="submit" id="post"><i class="fa fa-bug"></i> Simpan</button>
					</div>		
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection