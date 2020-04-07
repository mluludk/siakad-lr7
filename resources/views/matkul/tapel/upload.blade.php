@extends('app')

@section('title')
Upload {{ strtoupper($tipe)}}
@endsection

@section('content')
<h2>Upload {{ strtoupper($tipe) }}</h2>
{!! Form::open(['url' => route('matkul.tapel.upload', [$matkul_tapel_id, $tipe]), 'class' => 'form-horizontal', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload']) !!}
<div class="form-group">
	<label for="file" class="col-sm-1 control-label">Nama:</label>
	<div class="col-sm-3">
		<input name="nama" type="text" value="{{ $nama }}" class="form-control">
	</div>
</div>
<div class="form-group">
	<label for="file" class="col-sm-1 control-label">File:</label>
	{!! Form::hidden('jenis', $tipe) !!}
	{!! Form::hidden('id', $matkul_tapel_id) !!}
	<div class="col-sm-11">
		<input id="file" class="upload" data-multiple-caption="Terdapat {count} file terpilih" name="file" type="file">
		<label for="file" class="btn btn-default" id="upload-label"><i class="fa fa-search" id="upload-icon"></i> <span id="filename">Pilih file...</span></label>
	</div>
</div>
{!! Form::close() !!}
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
<script src="/js/jquery.form.min.js"></script>
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
				window.location = "/jadwaldosen";				
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