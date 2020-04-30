
<div class="form-group">
	{!! Form::label('', 'Mata Kuliah:') !!}
	<p class="form-control-static">{{ $kelas -> kurikulum -> matkul -> nama }}</p>
</div>

<div class="form-group">
	{!! Form::label('', 'Kelas:') !!}
	<p class="form-control-static">{{ $kelas -> kurikulum -> semester }} {{ $kelas -> kelas2 }}</p>
</div>

<div class="form-group">
	{!! Form::label('sesi_ke', 'Sesi Ke:') !!}
	{!! Form::select('sesi_ke', array_combine($r=range(1, 15), $r), null, array('class' => 'form-control')) !!}
</div>
<div class="form-group">
	{!! Form::label('judul', 'Judul:') !!}
	{!! Form::text('judul', null, array('class' => 'form-control', 'placeholder' => 'Judul Sesi')) !!}
</div>
<div class="form-group" style="max-width: 500px;">
	<label for="summernote">Tujuan:</label>
			<div id="summernote">{!! $sesi -> tujuan ?? '' !!}</div>
</div>
<input type="hidden" name="tujuan" id="isi" >
<div class="form-group">
	{!! Form::label('', 'Hari:') !!}
	<p class="form-control-static">{{ $hari[$jadwal -> hari] }}</p>
</div>
<div class="form-group">
	{!! Form::label('tanggal', 'Tanggal:') !!}
	{!! Form::text('tanggal', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal')) !!}
</div>
<div class="form-group">
	{!! Form::label('', 'Waktu:') !!}
	<p class="form-control-static">{{ $jadwal -> jam_mulai }} - {{ $jadwal -> jam_selesai }}</p>
</div>
<button class="btn btn-primary btn-flat {{ $btn_type }}" type="button" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>	

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