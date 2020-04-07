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
	table{
	font-size: 16px;
	}
	td img, p{
	max-width: 100%;
	}
</style>
@endpush

@section('content')
<div class="row">
	<div class="col-sm-8">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Detail Laporan Bug</h3>
			</div>
			<div class="box-body">
				<table>
					<tbody>
						<tr>
							<td width="200px">Tanggal</td><td>{{ formatTanggal(date('Y-m-d')) }}</td>
						</tr>
						<tr>
							<td>Pelapor</td><td>{{ $report -> user -> authable -> nama }}</td>
						</tr>
						<tr>
							<td>Judul Laporan</td><td>{{ $report -> title }}</td>
						</tr>
						<tr>
							<td>Prioritas Penanganan</td><td>{!! rating($report -> priority) !!}</td>
						</tr>
						<tr>
							<td>Dampak Bug pada Sistem</td><td>{!! rating($report -> severity) !!}</td>
						</tr>
						<tr>
							<td>Link Website</td><td>{{ $report -> url }}</td>
						</tr>
						<tr>
							<td valign="top">Keterangan</td><td>{!! $report -> description !!}</td>
						</tr>
						<tr>
							<td valign="top">Langkah terjadinya Bug</td>
							<td>{!! $report -> reproduce_step !!}</td>
						</tr>
						<tr>
							<td valign="top">User-Agent</td><td>{{ $report -> ua }}</td>
						</tr>
						<tr>
							<td valign="top">Attachment</td>
							<td>
								<?php
									$att = json_decode($report -> attachment, true);
								?>
								@if(isset($att) && count($att) > 0)
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
							</td>
						</tr>
						<tr>
							<td>Status</td>
							<td>						
								@if($report -> status == 0)
								<span class="label label-warning">{{ $status[$report -> status] }}</span>
								@elseif($report -> status == 1)
								<span class="label label-success">{{ $status[$report -> status] }}</span>
								@else
								<span class="label label-danger">{{ $status[$report -> status] }}</span>
								@endif
							</td>
						</tr>
						<tr>
							<td>Catatan</td><td>{{ $report -> notes ?? '-' }}</td>
						</tr>
					</tbody>
				</table>
				<br/>
				
				@if($user -> role_id == 1)
				<!--
					<a href="{{ route('report.resolve', $report->id) }}" class="btn btn-success btn-flat" title="Penyelesaian"><i class="fa fa-check"></i> Resolve</a>
				-->
				@endif
				
				@if($user -> role_id == $report -> reporter)
				<form method="POST" action="{{ route('report.resolved.user', $report->id) }}">
					{!! csrf_field() !!}
					<input type="checkbox" value="y" name="resolved"> Dengan ini saya menyatakan bahwa Laporan Bug tersebut sudah selesai dan ditangani dengan baik.<br/>
					<button type="submit" class="btn btn-success btn-flat" title="Penyelesaian"><i class="fa fa-check"></i> Selesai</button>
				</form>
				@endif
				
			</div>
		</div>
	</div>
	
	<div class="col-sm-4">
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">Komentar</h3>
			</div>
			<div class="box-header with-border">
				{!! Form::model(new Siakad\BugReportComment, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['report.comment.store']]) !!}
				<div class="form-group">
					<div class="col-sm-12">
						{!! Form::textarea('comment', null, array('rows' => '2', 'class' => 'form-control', 'placeholder' => 'Komentar anda', 'required' => 'required', 'autocomplete' => 'off')) !!}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						<button class="btn btn-primary btn-flat btn-primary" type="submit"><i class="fa fa-horn"></i> Kirim</button>
					</div>		
				</div>
				{!! Form::hidden('bug_report_id', $report -> id) !!}
				{!! Form::close() !!}
			</div>
			<div class="box-body">
				@if($comments -> count())
				<table id="comment_box">
					@foreach($comments as $c)
					<tr>
						<td class="comments">
							<div class="c_header">
								<span>{{ $c -> user -> username }}</span>
								<span class="time">{{ $c -> time }}</span> 
							</div>
							<div>{{ $c -> comment }}</div>
						</td>
					</tr>
					@endforeach
				</table>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection		

@push('styles')
<style type="text/css">
	#comment_box{
	width: 100%;
	}
	#comment_box tr{
	background: #fff;
	}
	#comment_box tr:nth-child(odd){ 
	background: #f9f9f9;
	}
	#comment_box tr:nth-child(even){
	background: #fff;
	} 
	.comments{
	padding: 5px 3px;
	}
	.c_header{
	font-style: italic;
	}
	.c_header span{
	display: inline-block;
	width: 49%;
	}
	span.time{
text-align: right;
}
</style>
@endpush												