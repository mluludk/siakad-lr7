@push('scripts')
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script src="{{ asset('/summernote/summernote.min.js') }}"></script>
<script>
	$(document).on('click', '#post', function(){
	var content = $('#summernote').summernote('code');
	$('#isi').val(content);
	$('#frm').submit();
});
$(function(){
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
	
	$(".chosen-select").chosen({
		no_results_text: "Tidak ditemukan hasil pencarian untuk: ",
		placeholder_text_single: "Pilih Tahun Akademik terlebih dahulu",
		search_contains: true
	});
	
	@if(!$edit)
	$('.refresh').on('change', function(){
		window.location.href = "{{ url('mahasiswa/tugas/create') }}" + "?tapel_id=" + $('select[name="tapel_id"]').val()
		+ "&mt_id=" + $('select[name="matkul_tapel_id"]').val();
	});
	@endif
	
	$(".date").datepicker({
		format:"dd-mm-yyyy", 
		autoHide:true,
		daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
		monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
	});
});  
</script>
@endpush

@push('styles')
<link href="{{ asset('/summernote/summernote.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
<style>
	.chosen-container{
	font-size: inherit;
	}
	.chosen-single{
	padding: 6px 10px !important;
	box-shadow: none !important;
    border-color: #d2d6de !important;
	background: white !important;
	height: 34px !important;
	border-radius: 0px !important;
	}
	.chosen-drop{
    border-color: #d2d6de !important;	
	box-shadow: none;
	}
</style>
@endpush

@if($tapel !== null)
@if(!$edit)
<div class="form-group">
	{!! Form::label('tapel_id', 'Tahun Akademik:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('tapel_id', $tapel, Request::get('tapel_id', null), array('class' => 'form-control refresh', 'placeholder' => 'Pilih Tahun Akademik')) !!}
	</div>
</div>
@endif
@endif

@if(!$edit)
<div class="form-group">
	{!! Form::label('matkul_tapel_id', 'Mata Kuliah:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-9">
		{!! Form::select('matkul_tapel_id', $matkul, Request::get('mt_id', null), array('class' => 'form-control chosen-select refresh', 'placeholder' => '-- Kelas Perkuliahan --')) !!}
	</div>
</div>
@endif

<div class="form-group">
	{!! Form::label('jenis_nilai_id', 'Jenis Penilaian:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::select('jenis_nilai_id', $jenis_nilai, null, array('class' => 'form-control', 'placeholder' => '-- Jenis Nilai --')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('nama', 'Judul Tugas:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-9">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Judul Tugas')) !!}
	</div>
</div>

@if(!$edit)
<div class="form-group">
	{!! Form::label('jenis_tugas', 'Jenis Tugas:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::select('jenis_tugas', $jenis, null, array('class' => 'form-control', 'placeholder' => 'Jenis Tugas')) !!}
	</div>
</div>
@endif

<div class="form-group">
	{!! Form::label('keterangan', 'Deskripsi Tugas:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-9">
		<div id="summernote">{!! $tugas -> keterangan ?? '' !!}</div>
		<input type="hidden" name="keterangan" id="isi" >
	</div>
</div>

<div class="form-group">
	{!! Form::label('tanggal', 'Tanggal Tugas:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('tanggal', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Tugas', 'autocomplete' => 'off')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('batas', 'Batas Akhir Tugas:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('batas', null, array('class' => 'form-control date', 'placeholder' => 'Batas Akhir Tugas', 'autocomplete' => 'off')) !!}
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
	</div>										