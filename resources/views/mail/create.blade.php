@extends('app')

@section('title')
Mailbox
@endsection

@push('styles')
<link href="{{ asset('/summernote/summernote.css') }}" rel="stylesheet">
@endpush

@section('header')
<section class="content-header">
	<h1>
		Mailbox
		<small>1 Pesan Baru</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mail') }}"> Mailbox</a></li>
		<li class="active">Compose</li>
	</ol>
</section>
@endsection

@push('scripts')
<script src="{{ asset('/summernote/summernote.min.js') }}"></script>
<script>
	$(document).ready(function() {
		$('#summernote').summernote({
			minHeight: 300, 
			maxHeight: null, 
			focus: true,
			toolbar: [
			['style', ['bold', 'italic', 'underline', 'clear']],
			['font', ['strikethrough', 'superscript', 'subscript']],
			['fontsize', ['fontname', 'fontsize']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['height', ['height']]
			]
		});
	});
	$(document).on('click', '#post', function(){
		var content = $('#summernote').summernote('code');
		$('#mail-body').val(content);
		$('#post-form').submit();
	});
</script>
@endpush

@section('content')
<div class="row">
	<div class="col-md-3">
		<a href="{{ url('/mail') }}" class="btn btn-primary btn-block margin-bottom">Back to Inbox</a>
		
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
					<li><a href="mailbox.html"><i class="fa fa-inbox"></i> Inbox
					<span class="label label-primary pull-right">12</span></a></li>
					<li><a href="#"><i class="fa fa-envelope-o"></i> Sent</a></li>
					<li><a href="#"><i class="fa fa-file-text-o"></i> Drafts</a></li>
					<li><a href="#"><i class="fa fa-filter"></i> Junk <span class="label label-warning pull-right">65</span></a>
					</li>
					<li><a href="#"><i class="fa fa-trash-o"></i> Trash</a></li>
				</ul>
			</div>
            <!-- /.box-body -->
		</div>
		<!-- /. box -->
		<div class="box box-solid">
            <div class="box-header with-border">
				<h3 class="box-title">Labels</h3>
				
				<div class="box-tools">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				</div>
			</div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
				<ul class="nav nav-pills nav-stacked">
					<li><a href="#"><i class="fa fa-circle-o text-red"></i> Important</a></li>
					<li><a href="#"><i class="fa fa-circle-o text-yellow"></i> Promotions</a></li>
					<li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Social</a></li>
				</ul>
			</div>
            <!-- /.box-body -->
		</div>
		<!-- /.box -->
	</div>
	<!-- /.col -->
	{!! Form::model(new Siakad\Mail, ['id' => 'post-form', 'role' => 'form', 'route' => ['mail.store']]) !!}
	<div class="col-md-9">
		<div class="box box-primary">
            <div class="box-header with-border">
				<h3 class="box-title">Compose New Message</h3>
			</div>
            <!-- /.box-header -->
            <div class="box-body">
				<div class="form-group">
					<input class="form-control" placeholder="To:">
				</div>
				<div class="form-group">
					<input class="form-control" placeholder="Subject:">
				</div>
				<div class="form-group">
					<div id="summernote"></div>
				</div>
				<div class="form-group">
					<div class="btn btn-default btn-file">
						<i class="fa fa-paperclip"></i> Attachment
						<input type="file" name="attachment">
					</div>
					<p class="help-block">Max. 32MB</p>
					<input type="hidden" name="mail-body" id="mail-body" >
				</div>
			</div>
            <!-- /.box-body -->
            <div class="box-footer">
				<div class="pull-right">
					<button type="button" class="btn btn-default"><i class="fa fa-pencil"></i> Draft</button>
					<button type="submit" id="post" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
				</div>
				<button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Discard</button>
			</div>
            <!-- /.box-footer -->
		</div>
		<!-- /. box -->
	</div>
	{!! Form::close() !!}
	<!-- /.col -->
</div>
@endsection	