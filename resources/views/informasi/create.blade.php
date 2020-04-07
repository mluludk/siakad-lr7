@extends('app')

@section('title')
Posting Pengumuman
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pengumuman
		<small>Posting Pengumuman</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/informasi') }}"> Daftar Pengumuman</a></li>
		<li class="active">Upload File</li>
	</ol>
</section>
@endsection

@push('styles')
<link href="{{ asset('/summernote/summernote.css') }}" rel="stylesheet">
<style>	
	.checkbox-inline+.checkbox-inline {
	margin-top: 0;
	margin-left: 0;
	margin-right: 10px;
	}
	.checkbox-inline:not(first-child){
	margin-right: 10px;
	}
</style>
@endpush

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
			['height', ['height']],
			['insert', ['link', 'picture']]
			]
		});
	});
	$(document).on('click', '#post', function(){
		var content = $('#summernote').summernote('code');
		$('#isi').val(content);
		$('#post-form').submit();
	});
</script>
@endpush

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Posting Pengumuman</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\Informasi, ['id' => 'post-form', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['informasi.store']]) !!}
		<div class="form-group">
			<label for="akses" class="col-sm-2 control-label">Kepada:</label>
			<div class="col-sm-10">
				<?php
					foreach($akses as $k => $v) 
					{
						echo '<label class="checkbox-inline">';
						echo '<input type="checkbox" name="akses[]" ';
						echo 'value="'. $k .'"> '. $v .'</label>';
					}
				?>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('judul', 'Judul:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-5">
				{!! Form::text('judul', null, array('class' => 'form-control')) !!}
				</div>
		</div>
		<div class="form-group">
			<label for="summernote" class="col-sm-2 control-label">Informasi:</label>
			<div class="col-sm-10">
				<div id="summernote"></div>
			</div>
		</div>
		<input type="hidden" name="isi" id="isi" >
		{!! csrf_field() !!}
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button class="btn btn-primary btn-flat" type="submit" id="post"><i class="fa fa-paper-plane"></i> Post</button>
			</div>		
		</div>	
		{!! Form::close() !!}
	</div>
</div>
@endsection	