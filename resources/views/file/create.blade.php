@extends('app')

@section('title')
Upload Dokumen
@endsection

@section('header')
<section class="content-header">
	<h1>
		File
		<small>Upload File</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/file') }}"> File</a></li>
		<li class="active">Upload File</li>
	</ol>
</section>
@endsection

@push('styles')
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

<?php
	$user_role = \Auth::user() -> role_id;
?>

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Upload File</h3>
	</div>
	<div class="box-body">
		{!! Form::open(['url' => url('/upload/file'), 'class' => 'form-horizontal', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload']) !!}
		<div class="form-group">
			<label for="nama" class="col-sm-1 control-label">Nama:</label>
			<div class="col-sm-5">
				<input id="nama" class="form-control" name="nama" type="text" placeholder="Nama file" value="{{ $nama ?? '' }}">
			</div>
		</div>
		<div class="form-group">
			<label for="tipe" class="col-sm-1 control-label">Jenis:</label>
			@if(in_array($user_role, [1, 2]))
			<div class="col-sm-11">
				<?php
					foreach(config('custom.pilihan.tipe') as $k => $v) 
					{
						echo '<label class="radio-inline">';
						echo '<input type="radio" name="tipe" ';
						if($type == $k) echo ' checked="checked" ';
						echo 'value="'. $k .'"> '. $v .'</label>';
					}
				?>
			</div>
			@else
			<div class="col-sm-11">
				<label class="radio-inline"><input type="radio" name="tipe" value="0"> Pedoman Penulisan Skripsi</label>
				<label class="radio-inline"><input type="radio" name="tipe" value="1"> Proposal Skripsi</label>
				<label class="radio-inline"><input type="radio" name="tipe" value="2"> Makalah</label>
				<label class="radio-inline"><input type="radio" name="tipe" value="6"> Kalender Akademik</label>
			</div>
			@endif
		</div>
		@if(in_array($user_role, [1, 2]))
		<div class="form-group">
			<label for="akses" class="col-sm-1 control-label">Akses:</label>
			<div class="col-sm-11">
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
		@else
		{!! Form::hidden('akses[]', '512') !!}
		{!! Form::hidden('akses[]', '128') !!}
		{!! Form::hidden('akses[]', '8') !!}
		@endif
		<div class="form-group">
			<label for="file" class="col-sm-1 control-label">File:</label>
			<input name="random_name" type="hidden" value="{{ $random_name ?? 'y' }}">
			<div class="col-sm-11">
				<input id="file" class="upload" data-multiple-caption="Terdapat {count} file terpilih" name="file" type="file">
				<label for="file" class="btn btn-default" id="upload-label"><i class="fa fa-search" id="upload-icon"></i> <span id="filename">Pilih file...</span></label>
			</div>
		</div>
		{!! Form::close() !!}
	</div>
</div>
@endsection	

@push('styles')
<style>
	/* input file - http://tympanus.net/codrops/2015/09/15/styling-customizing-file-inputs-smart-way/ */
	.upload {
	width: 0.1px;
	height: 0.1px;
	opacity: 0;
	overflow: hidden;
	position: absolute;
	z-index: -1;
	}
	.upload + label {
	display: inline-block;
	cursor: pointer;
	}
	.upload:focus + label {
	outline: 1px dotted #000;
	outline: -webkit-focus-ring-color auto 5px;
	}
	.upload + label * {
	pointer-events: none;
	}
	
	.control-label{
	text-align: left !important;
	}
</style>
@endpush

@push('scripts')
<script src="{{ asset('/js/jquery.form.min.js') }}"></script>
<script>
	$(document).on('change', '#file', function(){
		
		$('#upload-icon').removeClass('fa-search');
		$('#upload-icon').addClass('fa-hourglass-o');
		$('#upload-icon').addClass('fa-spin');
		$('#filename').text('Sedang memproses...');
		
		$('form#upload').submit();
	});
	$('form#upload').ajaxForm({
		beforeSend: function() {
			
		},
		success: function(data) {
			if(!data.success)
			{
				$('#upload-icon').removeClass('fa-hourglass-o');
				$('#upload-icon').removeClass('fa-spin');
				$('#upload-icon').addClass('fa-search');
				$('#upload-label').removeClass('btn-default');
				$('#upload-label').addClass('btn-danger');
				$('#filename').text(data.error);
			}
			else
			{
				window.location = "{{ $redirect ?? '/file' }}";				
			}
		},
		complete: function(xhr) {
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			alert('Terjadi kesalahan: ' + errorThrown);
		} 
	});  
</script>
@endpush					