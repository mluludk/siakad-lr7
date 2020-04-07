<div class="form-group">
	{!! Form::label('date', 'Tanggal:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<p class="form-control-static">{{ formatTanggal(date('Y-m-d')) }}</p>
	</div>
</div>
<div class="form-group">
	{!! Form::label('reporter', 'Pelapor:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<p class="form-control-static">{{ $user -> username }}</p>
	</div>
</div>
<div class="form-group">
	{!! Form::label('title', 'Judul Laporan:', array('class' => 'col-sm-3 control-label required')) !!}
	<div class="col-sm-9">
		{!! Form::text('title', null, array('class' => 'form-control', 'placeholder' => 'Judul Laporan', 'required' => 'required', 'autocomplete' => 'off')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('url', 'Link Website:', array('class' => 'col-sm-3 control-label required')) !!}
	<div class="col-sm-9">
		{!! Form::text('url', null, array('class' => 'form-control', 'placeholder' => 'Link website Bug yang terjadi (diawali dengan '. url('/') .')', 'autocomplete' => 'off', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('description', 'Keterangan:', array('class' => 'col-sm-3 control-label required')) !!}
	<div class="col-sm-9">
		<div id="sn_desc" class="summernote">{!! $bug -> description ?? '' !!}</div>
		<input type="hidden" name="description" id="inp_desc" >
	</div>
</div>
<div class="form-group">
	{!! Form::label('reproduce_step', 'Langkah terjadinya Bug:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<div id="sn_step" class="summernote">{!! $bug -> reproduce_step ?? '' !!}</div>
		<input type="hidden" name="reproduce_step" id="inp_step" >
	</div>
</div>
<div class="form-group">
	{!! Form::label('priority', 'Prioritas Penanganan:', array('class' => 'col-sm-3 control-label required')) !!}
	<div class="col-sm-3">
		{!! Form::select('priority', $priority, null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('severity', 'Dampak Bug pada sistem:', array('class' => 'col-sm-3 control-label required')) !!}
	<div class="col-sm-3">
		{!! Form::select('severity', $severity, null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('attachment', 'Attachment 1:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		@if(isset($bug -> attachment[0]['name']) && $bug -> attachment[0]['name'] != '') 
		@if(substr($bug -> attachment[0]['type'], 0, 5) == 'image')
		<img class="preview" src="{{ url('/getimage/' . $bug -> attachment[0]['name']) }}"></img>
		@else
		<a href="{{ url('/file/' . $bug -> attachment[0]['name']) }}">{{ substr($bug -> attachment[0]['name'], 8) }}</a>
		@endif
		@endif
		{!! Form::file('attachment[0]', null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('attachment', 'Attachment 2:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		@if(isset($bug -> attachment[1]['name']) && $bug -> attachment[1]['name'] != '') 
		@if(substr($bug -> attachment[1]['type'], 0, 5) == 'image')
		<img class="preview" src="{{ url('/getimage/' . $bug -> attachment[1]['name']) }}"></img>
		@else
		<a href="{{ url('/file/' . $bug -> attachment[1]['name']) }}">{{ substr($bug -> attachment[1]['name'], 8) }}</a>
		@endif
		@endif
		{!! Form::file('attachment[1]', null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('attachment', 'Attachment 3:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		@if(isset($bug -> attachment[2]['name']) && $bug -> attachment[2]['name'] != '') 
		@if(substr($bug -> attachment[2]['type'], 0, 5) == 'image')
		<img class="preview" src="{{ url('/getimage/' . $bug -> attachment[2]['name']) }}"></img>
		@else
		<a href="{{ url('/file/' . $bug -> attachment[2]['name']) }}">{{ substr($bug -> attachment[2]['name'], 8) }}</a>
		@endif
		@endif
		{!! Form::file('attachment[2]', null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-3"></div>
	<div class="col-sm-9">
		<span class="help-block">Kolom dengan tanda (<span style="color: red;">*</span>) harus diisi</span>
	</div>
</div>

@push('scripts')
<script src="{{ asset('/summernote/summernote.min.js') }}"></script>
<script>
	$(document).on('click', '#post', function(){
	var desc = $('#sn_desc').summernote('code');
	var step = $('#sn_step').summernote('code');
	$('#inp_desc').val(desc);
	$('#inp_step').val(step);
	$('#frm').submit();
	});
	$(function(){
	$('.summernote').summernote({
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
</script>
@endpush

@push('styles')
<link href="{{ asset('/summernote/summernote.css') }}" rel="stylesheet">
@endpush