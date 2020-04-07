@extends('app')

@section('title')
Mailbox
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mailbox
		@if(isset($new_mail) and $new_mail > 0)
		<small>{{ $new_mail }} Pesan Baru</small>
		@endif
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Mailbox</li>
	</ol>
</section>
@endsection

@section('content')
<div class="row">
	<div class="col-md-3">
		<a href="{{ url('/mail/compose') }}" class="btn btn-primary btn-block margin-bottom">Compose</a>
		
		<div class="box box-solid">
            <div class="box-header with-border">
				<h3 class="box-title">Folders</h3>
				
				<div class="box-tools">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				</div>
			</div>
            <div class="box-body no-padding">
				<ul class="nav nav-pills nav-stacked">
					<li class="active">
						<a href="#"><i class="fa fa-inbox"></i> Inbox
							@if(isset($new_mail) and $new_mail > 0)
							<span class="label label-info pull-right">{{ $new_mail }}</span>
							@endif
						</a>
					</li>
					<li><a href="#"><i class="fa fa-envelope-o"></i> Sent</a></li>
					<!--
						<li><a href="#"><i class="fa fa-file-text-o"></i> Drafts</a></li>
						<li><a href="#"><i class="fa fa-filter"></i> Junk <span class="label label-warning pull-right">65</span></a></li>
					-->
					<li><a href="#"><i class="fa fa-trash-o"></i> Trash</a></li>
				</ul>
			</div>
            <!-- /.box-body -->
		</div>
		
		<!-- /.box -->
	</div>
	<!-- /.col -->
	<div class="col-md-9">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Inbox</h3>
				
				<div class="box-tools pull-right">
					<div class="has-feedback">
						<input type="text" class="form-control input-sm" placeholder="Search Mail">
						<span class="glyphicon glyphicon-search form-control-feedback"></span>
					</div>
				</div>
				<!-- /.box-tools -->
			</div>
			<!-- /.box-header -->
			<div class="box-body no-padding">
				<div class="mailbox-controls">
					<!-- Check all button -->
					<button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
					<button type="button" class="btn btn-danger btn-sm btn-delete"><i class="fa fa-trash-o"></i></button>
					<a href="{{ url('/mail') }}" type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></a>
					<?php
						$per_page = $inbox -> perPage();
						$total = $inbox -> total();
						$n = ($inbox -> currentPage() - 1) * $per_page;
						$last = $n + $per_page > $total ? $total : $n + $per_page;
					?>
					<div class="pull-right">
						{{ $n + 1 }}-{{ $last }}/{{ $total }}
						<div class="btn-group">
							@if($inbox -> currentPage() == 1)
							<button type="button" class="btn btn-default btn-sm" disabled="disabled"><i class="fa fa-chevron-left"></i></button>
							@else
							<a href="{{ $inbox -> previousPageUrl() }}" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
							@endif
							
							@if($inbox -> hasMorePages())
							<a href="{{ $inbox -> nextPageUrl() }}" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
							@else
							<button type="button" class="btn btn-default btn-sm" disabled="disabled"><i class="fa fa-chevron-right"></i></button>
							@endif
						</div>
					</div>
					
				</div>
				
				<div class="table-responsive mailbox-messages">
					<table class="table table-hover table-striped">
						<tbody>
							@if($inbox -> count())
							@foreach($inbox as $m)
							<?php
								$b = $c = '';
								if($m -> read == 'n'){$b = '<strong>'; $c = '</strong>';}
							?>
							<tr>
								<td><input type="checkbox"></td>
								<td class="mailbox-star">
									@if($m -> start == 'y')
									<a href="#"><i class="fa fa-star text-yellow"></i></a>
									@else
									<a href="#"><i class="fa fa-star-o text-yellow"></i></a>
									@endif
								</td>
								<td class="mailbox-name"><a href="{{ route('mail.read', [str_slug($m -> subject), $m -> id]) }}">{!! $b !!}{{ $m -> from -> authable -> nama }}{!! $c !!}</a></td>
								<td class="mailbox-subject">
									{!! $b !!}
									{{ cutStr($m -> subject, 50) }}
									{!! $c !!}
								</td>
								<td class="mailbox-attachment">&nbsp;<!--i class="fa fa-paperclip"></i--></td>
								<td class="mailbox-date">
									{!! $b !!}
									<time class="timeago" datetime="{{ $m -> time ?? '-' }}"></time>
									{!! $c !!}
								</td>
							</tr>
							@endforeach
							@else
							<tr>
								<td align="center">no mail yet :(</td>
							</tr>
							@endif
						</tbody>
					</table>
					
					<!-- /.table -->
				</div>
				
				<!-- /.mail-box-messages -->
			</div>
			<!-- /.box-body -->
			<div class="box-footer no-padding">
				<div class="mailbox-controls">
					<!-- Check all button -->
					<button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
					<button type="button" class="btn btn-danger btn-sm btn-delete"><i class="fa fa-trash-o"></i></button>
					<!-- /.btn-group -->
					<a href="{{ url('/mail') }}" type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></a>
					<div class="pull-right">
						{{ $n + 1 }}-{{ $last }}/{{ $total }}
						<div class="btn-group">
							@if($inbox -> currentPage() == 1)
							<button type="button" class="btn btn-default btn-sm" disabled="disabled"><i class="fa fa-chevron-left"></i></button>
							@else
							<a href="{{ $inbox -> previousPageUrl() }}" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
							@endif
							
							@if($inbox -> hasMorePages())
							<a href="{{ $inbox -> nextPageUrl() }}" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
							@else
							<button type="button" class="btn btn-default btn-sm" disabled="disabled"><i class="fa fa-chevron-right"></i></button>
							@endif
						</div>
					</div>
					<!-- /.pull-right -->
				</div>
			</div>
		</div>
		<!-- /. box -->
	</div>
	<!-- /.col -->
</div>
@endsection	


@push('styles')
<link rel="stylesheet" href="{{ asset('css/blue.css') }}">
<style>
	.mailbox-subject{
	width: 50%;
	}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/icheck.min.js') }}"></script>
<script>
	$(function () {
	//Enable iCheck plugin for checkboxes
	//iCheck for checkbox and radio inputs
	$('.mailbox-messages input[type="checkbox"]').iCheck({
	checkboxClass: 'icheckbox_flat-blue',
	radioClass: 'iradio_flat-blue'
	});
	
	//Enable check and uncheck all functionality
	$(".checkbox-toggle").click(function () {
	var clicks = $(this).data('clicks');
	if (clicks) {
	//Uncheck all checkboxes
	$(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
	$(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
	} else {
	//Check all checkboxes
	$(".mailbox-messages input[type='checkbox']").iCheck("check");
	$(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
	}
	$(this).data("clicks", !clicks);
	});
	
	//Handle starring for glyphicon and font awesome
	$(".mailbox-star").click(function (e) {
	e.preventDefault();
	//detect type
	var $this = $(this).find("a > i");
	var fa = $this.hasClass("fa");			
	if (fa) {
	$this.toggleClass("fa-star");
	$this.toggleClass("fa-star-o");
	}
	});
	});
</script>

<script src="{{ asset('/js/jquery.timeago.js') }}" type="text/javascript"></script>
<script>
	jQuery.timeago.settings.strings = {
	prefixAgo: null,
	prefixFromNow: null,
	suffixAgo: "yang lalu",
	suffixFromNow: "dari sekarang",
	seconds: "kurang dari semenit",
	minute: "sekitar satu menit",
	minutes: "%d menit",
	hour: "sekitar sejam",
	hours: "sekitar %d jam",
	day: "sehari",
	days: "%d hari",
	month: "sekitar sebulan",
	months: "%d bulan",
	year: "sekitar setahun",
	years: "%d tahun"
	};
	jQuery(document).ready(function() {
	jQuery("time.timeago").timeago();
	});
</script>
@endpush