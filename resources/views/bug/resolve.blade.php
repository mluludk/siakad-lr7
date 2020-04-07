@extends('app')

@section('title')
Detail Laporan Bug
@endsection

@section('header')
<section class="content-header">
	<h1>
		Laporan Bug
		<small>Detail</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/report') }}"> Laporan Bug</a></li>
		<li class="active">{{ $report -> title }} @ {{ formatTanggal($report -> date) }}</li>
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
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Detail Laporan Bug</h3>
	</div>
	<div class="box-body">
		{!! Form::model($report, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true, 'route' => ['report.resolved', $report->id]]) !!}
		<div class="form-group">
			{!! Form::label('', 'Tanggal:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-9">
				<p class="form-control-static">{{ formatTanggal(date('Y-m-d')) }}</p>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('', 'Pelapor:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-9">
				<p class="form-control-static">{{ $report -> user -> authable -> nama }}</p>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('', 'Prioritas:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-9">
				<p class="form-control-static">{!! rating($report -> priority) !!}</p>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('', 'Dampak Bug pada Sistem:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-9">
				<p class="form-control-static">{!! rating($report -> severity) !!}</p>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('', 'Judul Laporan:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-9">
				<p class="form-control-static">{{ $report -> title }}</p>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('', 'Link Website:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-9">
				<p class="form-control-static">{{ $report -> url }}</p>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('', 'Keterangan:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-9">
				<p class="form-control-static">{!! $report -> description !!}</p>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('', 'Langkah terjadinya Bug:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-9">
				<p class="form-control-static">{!! $report -> reproduce_step !!}</p>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('', 'User-Agent:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-9">
				<p class="form-control-static">{{ $report -> ua }}</p>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('', 'Attachment:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-9">
				<?php
					$att = json_decode($report -> attachment, true);
				?>
				@if(count($att) > 0)
				<ol>
					@foreach($att as $a)
					<li>
						@if(substr($a['type'], 0, 5) == 'image')
						<a href="{{ url('/getimage/' . $a['name']) }}" target="_blank">
							<img class="preview" src="{{ url('/getimage/' . $a['name']) }}"></img>
						</a>
						@else
						<a href="{{ url('/file/' . $a['name']) }}">{{ substr($a['name'], 8) }}</a>
						@endif
					</li>
					@endforeach
				</ol>
				@endif
			</div>
		</div>	
		
		<hr/>
		<div class="form-group">
			{!! Form::label('', 'Status:', array('class' => 'col-sm-3 control-label')) !!}
			<div class="col-sm-3">
				{!! Form::select('status', $status, null, array('class' => 'form-control')) !!}
			</div>
		</div>
	<div class="form-group">
	{!! Form::label('', 'Catatan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
	{!! Form::textarea('notes', null, array('class' => 'form-control', 'placeholder' => 'Catatan penyelesaian', 'rows' => '3', 'required' => 'required')) !!}
	</div>
	</div>
	<div class="form-group">
	{!! Form::label('', '', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
	<button class="btn btn-primary btn-flat btn-success" type="submit" id="post"><i class="fa fa-bug"></i> Resolve</button>
	</div>		
	</div>
	</form>
	</div>
	</div>
	@endsection							