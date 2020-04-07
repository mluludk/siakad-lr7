
<div class="form-group">
	{!! Form::label('nama', 'Nama Bank:', array('class' => 'col-sm-3 control-label required')) !!}
	<div class="col-sm-3">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('singkatan', 'Singkatan:', array('class' => 'col-sm-3 control-label required')) !!}
	<div class="col-sm-2">
		{!! Form::text('singkatan', null, array('class' => 'form-control', 'placeholder' => 'Singkatan', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('api_key', 'API Key:', array('class' => 'col-sm-3 control-label required')) !!}
	<div class="col-sm-4" style="position: relative;">
		{!! Form::text('api_key', null, array('class' => 'form-control', 'placeholder' => 'API KEY')) !!}
		<button class="btn btn-success btn-flat btn-gen-token" data-target="api_key" type="button"> <i class="fa fa-refresh"></i></button>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	

@push('styles')
<style>
	.btn-gen-token{
	position: absolute;
	top: 0px;
	right: 15px;
	}
</style>
@endpush

@push('scripts')
<script>
	/* https://stackoverflow.com/a/1349426 */
	function makeid(length) {
		var result           = '';
		/* var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'; */
		var characters       = 'abcdef0123456789';
		var charactersLength = characters.length;
		for ( var i = 0; i < length; i++ ) {
			result += characters.charAt(Math.floor(Math.random() * charactersLength));
		}
		return result;
	}
	
	$(document).on('click', '.btn-gen-token', function(){
		if(!confirm('Perubahan API KEY dapat menyebabkan sistem mengalami kegagalan. Pastikan Anda sudah berkonsultasi dengan pihak terkait tentang perubahan ini. Apakah anda yakin akan membuat API KEY baru?')) return false;
		var target=$(this).attr('data-target');
		$('input[name="' + target +'"]').val(makeid(32));
	});
</script>
@endpush