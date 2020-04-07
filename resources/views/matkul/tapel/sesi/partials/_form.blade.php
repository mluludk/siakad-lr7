
<div class="form-group">
	{!! Form::label('', 'Mata Kuliah:', array('class' => 'col-md-2 control-label')) !!}
	<div class="col-md-10">
		<p class="form-control-static">{{ $kelas -> kurikulum -> matkul -> nama }}</p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('', 'Kelas:', array('class' => 'col-md-2 control-label')) !!}
	<div class="col-md-10">
		<p class="form-control-static">{{ $kelas -> kurikulum -> semester }} {{ $kelas -> kelas2 }}</p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('sesi_ke', 'Sesi Ke:', array('class' => 'col-md-2 control-label')) !!}
	<div class="col-md-1">
		{!! Form::select('sesi_ke', array_combine($r=range(1, 15), $r), null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('judul', 'Judul:', array('class' => 'col-md-2 control-label')) !!}
	<div class="col-md-9">
		{!! Form::text('judul', null, array('class' => 'form-control', 'placeholder' => 'Judul Sesi')) !!}
	</div>
</div>
<div class="form-group">
	<label for="summernote" class="col-md-2 control-label">Tujuan:</label>
	<div class="col-md-10">
		<div id="summernote">{!! $sesi -> tujuan ?? '' !!}</div>
	</div>
</div>
<input type="hidden" name="tujuan" id="isi" >
<div class="form-group">
	{!! Form::label('', 'Hari:', array('class' => 'col-md-2 control-label')) !!}
	<div class="col-md-10">
		<p class="form-control-static">{{ $hari[$jadwal -> hari] }}</p>
	</div>
</div>
<div class="form-group">
	{!! Form::label('tanggal', 'Tanggal:', array('class' => 'col-md-2 control-label')) !!}
	<div class="col-md-2">
		{!! Form::text('tanggal', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('', 'Waktu:', array('class' => 'col-md-2 control-label')) !!}
	<div class="col-md-10">
		<p class="form-control-static">{{ $jadwal -> jam_mulai }} - {{ $jadwal -> jam_selesai }}</p>
	</div>
</div>
<div class="form-group">
	<div class="col-md-offset-2 col-md-6">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="button" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>		

@push('scripts')
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script src="{{ asset('/summernote/summernote.min.js') }}"></script>
<script>
	$(document).on('click', '#post', function(){
		var content = $('#summernote').summernote('code');
		$('#isi').val(content);
		$('#post-form').submit();
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
		
		$(".date").datepicker({
			format:"dd-mm-yyyy", 
			autoHide:true,
			daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
			daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
			monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
			filter: function(date, view) {
				if (date.getDay() !== {{ $jadwal -> hari }} && view === 'day') {
					return false; 
				}
			}@if(!isset($sesi)),
			startDate: '{{ date("d-m-Y") }}'
			@endif
		});
	});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
<link href="{{ asset('/summernote/summernote.css') }}" rel="stylesheet">
@endpush