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
	
	#preview{
	display:block;
	width: 200px;
	padding: 5px;
	margin-bottom: 15px;
	border: 1px solid #999;
	}
</style>
@endpush

@push('scripts')
<script src="{{ url('/js/jquery.form.min.js') }}"></script>
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
				$('#upload-label').removeClass('btn-default');
				$('#upload-label').addClass('btn-danger');
				$('#upload-icon').removeClass('fa-search');
				$('#upload-icon').addClass('fa-times');
				$('#filename').text('Error');
			}
			else
			{
				if($('#upload-icon').hasClass('fa-times')) 
				{
					$('#upload-icon').removeClass('fa-times');
					$('#upload-label').removeClass('btn-danger');
					$('#upload-label').addClass('btn-default');
				}
				$('#fileupload').val(data.filename);
			}
		},
		complete: function(xhr) {
			$('#upload-icon').removeClass('fa-hourglass-o');
			$('#upload-icon').removeClass('fa-spin');
			$('#upload-icon').addClass('fa-search');
			$('#filename').text({{ $label ?? 'Pilih file...' }});
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			alert('Terjadi kesalahan: ' + errorThrown);
		}
	});  
	
</script>
@endpush
<div class="form-group">
	<input id="file" class="upload" data-multiple-caption="Terdapat {count} file terpilih" name="file" type="file">
	<label for="file" class="btn btn-default" id="upload-label"><i class="fa fa-search" id="upload-icon"></i> <span id="filename">{{ $label ?? 'Pilih file...' }}</span></label>
</div>