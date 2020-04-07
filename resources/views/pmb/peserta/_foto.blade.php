<img class="preview" src="@if(isset($current_image) and $current_image != '') /getimage/{{ $current_image }} @else {{ $default_image }} @endif"></img>
<div class="form-group" style="margin-top: -60px;">
	<input id="{{ $file_selector }}" class="upload" data-multiple-caption="Terdapat {count} file terpilih" name="image" type="file">
	<label for="{{ $file_selector }}" class="btn btn-info upload-label btn-block"><i class="fa fa-search upload-icon"></i> <span class="filename">{{ $message }}</span></label>
	<input type="hidden" name="width" value="{{ $resized_width }}">
	<input type="hidden" name="height" value="{{ $resized_height }}">
</div>

@push('scripts')
<script>
	$(document).on('change', '#{{ $file_selector }}', function(){
		
		$('form#{{ $form }} .upload-icon').removeClass('fa-search');
		$('form#{{ $form }} .upload-icon').addClass('fa-hourglass-o');
		$('form#{{ $form }} .upload-icon').addClass('fa-spin');
		$('form#{{ $form }} .filename').text('Sedang memproses...');
		
		$('form#{{ $form }}').submit();
	});
	$('form#{{ $form }}').ajaxForm({
		beforeSend: function() {
			
		},
		success: function(data) {
			if(!data.success)
			{
				$('form#{{ $form }} .upload-label').removeClass('btn-default');
				$('form#{{ $form }} .upload-label').addClass('btn-danger');
				$('form#{{ $form }} .upload-icon').removeClass('fa-search');
				$('form#{{ $form }} .upload-icon').addClass('fa-times');
				$('form#{{ $form }} .filename').text('Error');
			}
			else
			{
				$('form#{{ $form }} .preview').attr('src', '/getimage/' + data.filename);
				if($('form#{{ $form }} .upload-icon').hasClass('fa-times')) 
				{
					$('form#{{ $form }} .upload-icon').removeClass('fa-times');
					$('form#{{ $form }} .upload-label').removeClass('btn-danger');
					$('form#{{ $form }} .upload-label').addClass('btn-default');
				}
				$('#{{ $target }}').val(data.filename);
			}
		},
		complete: function(xhr) {
			$('form#{{ $form }} .upload-icon').removeClass('fa-hourglass-o');
			$('form#{{ $form }} .upload-icon').removeClass('fa-spin');
			$('form#{{ $form }} .upload-icon').addClass('fa-search');
			$('form#{{ $form }} .filename').text('{{ $message }}');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			alert('Terjadi kesalahan: ' + errorThrown);
		}
	});  
	
</script>
@endpush