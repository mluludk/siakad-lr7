@push('scripts')
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script>
	$(function(){
	$(".date").datepicker({
		format:"dd-mm-yyyy", 
		autoHide:true,
		daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
		daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
		monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
	});
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
@endpush

<div class="form-group">
	{!! Form::label('judul', 'Judul Skripsi:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		<p class="form-control-static">{{ $skripsi -> judul }}</p>
	</div>
</div>
<div class="form-group">
	{!! Form::label('dosen_id', 'Dosen Pembimbing:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		@foreach($skripsi -> pembimbing as $pb)
		<p class="form-control-static">{{ $pb -> gelar_depan }} {{ $pb -> nama }} {{ $pb -> gelar_belakang }}</p>
		@endforeach
	</div>
</div>
<div class="form-group">
	{!! Form::label('tglBimbingan', 'Tanggal:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('tglBimbingan', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Bimbingan', 'autocomplete' => 'off', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tentang', 'Perihal:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-9">
		{!! Form::textarea('tentang', null, array('class' => 'form-control', 'placeholder' => 'Perihal yang dikonsultasikan', 'rows' => '3')) !!}
	</div>
</div>
@if(\Auth::user() -> role_id < 512)
<div class="form-group">
	{!! Form::label('disetujui', 'Disetujui:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-8">
		<label class="radio-inline">{!! Form::radio('disetujui', 'y') !!} Ya</label>
		<label class="radio-inline">{!! Form::radio('disetujui', 'n') !!} Tidak</label>
		<label class="radio-inline">{!! Form::radio('disetujui', 'p') !!} Pending</label>
	</div>
</div>
@endif
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-9">
	<button class="btn {{ $btn_type }} btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
	</div>			