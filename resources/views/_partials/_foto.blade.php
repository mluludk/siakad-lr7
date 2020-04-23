<img class="preview" src="@if(isset($foto) and $foto != '') {{ url('/getimage/' . $foto) }} @else {{ url('images/'. $default_image) }} @endif"></img>
<div class="form-group" style="margin-top: -60px;">
	<input id="{{ $file_selector ?? 'image' }}" class="upload" data-multiple-caption="Terdapat {count} file terpilih" name="image" type="file">
	<label for="{{ $file_selector ?? 'image' }}" class="btn btn-info upload-label btn-block"><i class="fa fa-search upload-icon"></i> <span class="filename">{{ $message ?? "Pilih file gambar"}}</span></label>
	<input type="hidden" name="width" value="{{ $resized_width ?? '300' }}">
	<input type="hidden" name="height" value="{{ $resized_height ?? '400' }}">
</div>
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
	
	.preview{
	display:block;
	width: 100%;
	padding: 5px;
	margin-bottom: 3px;
	border: 1px solid #999;
	}
</style>
@endpush

@push('scripts')
<script src="{{ url('/js/jquery.form.min.js') }}"></script>
@endpush

@push('scripts')
<script>
	$(document).on('change', '#{{ $file_selector ?? "image" }}', function(){
		
		$('form#{{ $form ?? "upload" }} .upload-icon').removeClass('fa-search');
		$('form#{{ $form ?? "upload" }} .upload-icon').addClass('fa-hourglass-o');
		$('form#{{ $form ?? "upload" }} .upload-icon').addClass('fa-spin');
		$('form#{{ $form ?? "upload" }} .upload-label').removeClass('btn-info');
		$('form#{{ $form ?? "upload" }} .upload-label').addClass('btn-warning');
		$('form#{{ $form ?? "upload" }} .filename').text('Sedang memproses...');
		
		$('form#{{ $form ?? "upload" }}').submit();
	});
	$('form#{{ $form ?? "upload" }}').ajaxForm({
		beforeSend: function() {
			
		},
		success: function(data) {
			if(!data.success)
			{
				$('form#{{ $form ?? "upload" }} .upload-label').removeClass('btn-warning');
				$('form#{{ $form ?? "upload" }} .upload-label').addClass('btn-danger');
				$('form#{{ $form ?? "upload" }} .upload-icon').removeClass('fa-search');
				$('form#{{ $form ?? "upload" }} .upload-icon').addClass('fa-times');
				$('form#{{ $form ?? "upload" }} .filename').text('Error');
				alert('Terjadi kesalahan: ' + data.error);
			}
			else
			{
				$('form#{{ $form ?? "upload" }} .preview').attr('src', '/getimage/' + data.filename);
				if($('form#{{ $form ?? "upload" }} .upload-icon').hasClass('fa-times')) 
				{
					$('form#{{ $form ?? "upload" }} .upload-icon').removeClass('fa-times');
					$('form#{{ $form ?? "upload" }} .upload-label').removeClass('btn-danger');
					$('form#{{ $form ?? "upload" }} .upload-label').addClass('btn-info');
				}
				$('#{{ $target ?? "foto" }}').val(data.filename);
			}
		},
		complete: function(xhr) {
			$('form#{{ $form ?? "upload" }} .upload-icon').removeClass('fa-hourglass-o');
			$('form#{{ $form ?? "upload" }} .upload-icon').removeClass('fa-spin');
			$('form#{{ $form ?? "upload" }} .upload-icon').addClass('fa-search');
			$('form#{{ $form ?? "upload" }} .upload-label').removeClass('btn-warning');
			$('form#{{ $form ?? "upload" }} .upload-label').addClass('btn-info');
			$('form#{{ $form ?? "upload" }} .filename').text('{{ $message ?? "Pilih file gambar" }}');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			alert('Terjadi kesalahan: ' + errorThrown);
		}
	});  
	
</script>
@endpush