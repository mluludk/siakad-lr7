@extends('app')

@section('title')
Edit Program Kerja
@endsection

@push('styles')
<link href="/summernote/summernote.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="/summernote/summernote.min.js"></script>
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
	$(document).on('click', '#simpan', function(){
		var content = $('#summernote').summernote('code');
		$('#program').val(content);
		$('#program-form').submit();
	});
</script>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Program Kerja
		<small>Edit Program Kerja</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/program') }}">Program Kerja</a></li>
		<li class="active">Edit Program Kerja</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Program Kerja</h3>
	</div>
	<div class="box-body">
		<form method="POST" action="{{ route('program.edit') }}" accept-charset="UTF-8" class="form-horizontal" id="program-form" role="form">
			<div id="summernote">{!! $program -> program !!}</div>
			<input type="hidden" name="program" id="program" >
			{!! csrf_field() !!}
			<button class="btn btn-primary" id="simpan"><i class="fa fa-floppy-o"></i> Simpan</button>
			{!! Form::close() !!}
		</div>
	</div>
@endsection