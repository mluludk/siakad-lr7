@extends('app')

@section('title')
Mailbox
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mailbox
		@if(isset($new_mail) && $new_mail > 0)
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
						<a href="{{ route('mail.index') }}"><i class="fa fa-inbox"></i> Inbox
							@if(isset($new_mail) && $new_mail > 0)
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
				<h3 class="box-title">Read Mail</h3>
				
				<div class="box-tools pull-right">
				<a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
				<a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
	</div>
	</div>
	
	<!-- /.box-header -->
	<div class="box-body no-padding">
	<div class="mailbox-read-info">
	<h3>{{ $mail -> subject }}</h3>
	<h5>From: {{ $mail -> from -> authable -> nama }}
	<span class="mailbox-read-time pull-right">{{ formatTanggalWaktu($mail -> time) }}</span></h5>
	</div>
	<!-- /.mailbox-read-info -->
	<div class="mailbox-controls with-border text-center">
	<div class="btn-group">
	<button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Delete">
	<i class="fa fa-trash-o"></i></button>
	<button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Reply">
	<i class="fa fa-reply"></i></button>
	</div>
	<!-- /.btn-group -->
	<button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Print">
	<i class="fa fa-print"></i></button>
	</div>
	<!-- /.mailbox-controls -->
	<div class="mailbox-read-message">
	{!! $mail -> body !!}
	</div>
	
	</div>
	<!-- /.box-body -->
	<!-- Attachment
	<div class="box-footer">
	<ul class="mailbox-attachments clearfix">
	<li>
	<span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"></i></span>
	
	<div class="mailbox-attachment-info">
	<a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> Sep2014-report.pdf</a>
	<span class="mailbox-attachment-size">
	1,245 KB
	<a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
	</span>
	</div>
	</li>
	<li>
	<span class="mailbox-attachment-icon"><i class="fa fa-file-word-o"></i></span>
	
	<div class="mailbox-attachment-info">
	<a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> App Description.docx</a>
	<span class="mailbox-attachment-size">
	1,245 KB
	<a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
	</span>
	</div>
	</li>
	<li>
	<span class="mailbox-attachment-icon has-img"><img src="../../dist/img/photo1.png" alt="Attachment"></span>
	
	<div class="mailbox-attachment-info">
	<a href="#" class="mailbox-attachment-name"><i class="fa fa-camera"></i> photo1.png</a>
	<span class="mailbox-attachment-size">
	2.67 MB
	<a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
	</span>
	</div>
	</li>
	<li>
	<span class="mailbox-attachment-icon has-img"><img src="../../dist/img/photo2.png" alt="Attachment"></span>
	
	<div class="mailbox-attachment-info">
	<a href="#" class="mailbox-attachment-name"><i class="fa fa-camera"></i> photo2.png</a>
	<span class="mailbox-attachment-size">
	1.9 MB
	<a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
	</span>
	</div>
	</li>
	</ul>
	</div>
	-->
	<!-- /.box-footer -->
	<div class="box-footer">
	<div class="pull-right">
	<button type="button" class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
	</div>
	<button type="button" class="btn btn-default"><i class="fa fa-trash-o"></i> Delete</button>
	<button type="button" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
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