@extends('app')

@section('title')
Daftar Laporan Bug
@endsection

@section('header')
<section class="content-header">
	<h1>
		Laporan Bug
		<small>Daftar Laporan Bug</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Laporan Bug</li>
	</ol>
</section>
@endsection

@section('content')
<div class="row">
	<div class="col-sm-8" id="index">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Daftar Laporan Bug</h3>
			</div>
			<div class="box-body">
				<table class="table table-bordered">
					<thead>
						<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
							<th>No</th>
							<th width="120px">Tanggal</th>
							<th>User</th>
							<th>Judul</th>
							<th>Prioritas</th>
							<th>Dampak</th>
							<th>Status</th>
							<th colspan="4"></th>
						</tr>
					</thead>
					<tbody>
						@if(!$bug -> count())
						<tr>
							<td colspan="7">Belum ada data</td>
						</tr>
						@else
						<?php 					
							$per_page = $bug -> perPage();
							$total = $bug -> total();
							$n = ($bug -> currentPage() - 1) * $per_page;
							$last = $n + $per_page > $total ? $total : $n + $per_page;
						?>
						@foreach($bug as $g)
						<?php $n++; ?>
						@if(isset($report) and $g -> id == $report -> id)
						<tr class="info" id="bug-{{ $g -> id }}">
							@else
							<tr id="bug-{{ $g -> id }}">
								@endif
								<td>{{ $n }}</td>
								<td>{{ formatTanggal($g -> date) }}</td>
								<td>{{ $g -> user -> username }}</td>
								<td>{{ $g -> title }}</td>
								<td>{!! rating($g -> priority) !!}</td>
								<td>{!! rating($g -> severity) !!}</td>
								<td>
									@if($g -> status == 0)
									<span class="label label-warning">{{ $status[$g -> status] }}</span>
								
									@elseif($g -> status == 1)
									<span class="label label-success">{{ $status[$g -> status] }}</span>
								
									@else
									<span class="label label-danger">{{ $status[$g -> status] }}</span>
									@endif
								</td>
								<td width="100px">
									<a href="{{ route('report.show', $g -> id) }}" class="btn btn-info btn-flat btn-xs" title="Detail Laporan"><i class="fa fa-search"></i></a>
								
									@if(($g -> status == 0 and $g -> reporter == $user -> id) ?? $user -> role_id == 1)
									<a href="{{ route('report.edit', $g->id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit Laporan"><i class="fa fa-pencil-square-o"></i></a>
								
									<a href="{{ route('report.index') }}?report_id={{ $g -> id }}#bug-{{ $g -> id }}"  class="btn btn-danger btn-flat btn-xs" title="Komentar"><i class="fa fa-commenting-o"></i></a>
									@endif
									@if($user -> role_id == 1)
									<a href="{{ route('report.resolve', $g->id) }}" class="btn btn-success btn-flat btn-xs" title="Penyelesaian"><i class="fa fa-check"></i></a>
									@endif
								</td>
							</tr>
							
						@endforeach
						@endif
					</tbody>
				</table>
				{!! $bug -> render() !!}
			</div>
		</div>
	</div>
	
	<div class="col-sm-4" id="comment">
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">Komentar</h3>
			</div>
			@if($comments !== null)
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
			@else
			<div class="box-body">
				<p>Klik pada tombol 
					<button class="btn btn-danger btn-flat btn-xs" title="Komentar"><i class="fa fa-commenting-o"></i></button> 
					untuk menampilkan komentar
				</p>
			</div>
			@endif
		</div>
	</div>
</div>
@endsection	

@push('scripts')
<script>
	(function($) {
    var element = $('#comment'),
        originalY = element.offset().top;

    // Space between element and top of screen (when scrolling)
    var topMargin = 20;

    // Should probably be set in CSS; but here just for emphasis
    element.css('position', 'relative');

    $(window).on('scroll', function(event) {
        var scrollTop = $(window).scrollTop();

        element.stop(false, false).animate({
            top: scrollTop < originalY
                    ? 0
                    : scrollTop - originalY + topMargin
        }, 300);
    });
})(jQuery);
</script>
@endpush

@push('styles')
<style type="text/css">
	.text-xs{
	font-size: 11px;
	}
	#comment .box-body{
	max-height: 600px;
	overflow: auto;
	}
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