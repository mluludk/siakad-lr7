
<div class="form-group">
	{!! Form::label('prodi_id', 'PRODI:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		@if($disabled)
		{!! Form::select('', $prodi, $prodi_id, array('class' => 'form-control', 'disabled' => 'disabled')) !!}
		{!! Form::hidden('prodi_id', $prodi_id) !!}
		@else
		{!! Form::select('prodi_id', $prodi, $prodi_id, array('class' => 'form-control')) !!}
		@endif
	</div>
</div>

<div class="form-group">
	{!! Form::label('nama', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama Jadwal', 'required' => 'required')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('no_surat', 'No. Surat:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::text('no_surat', null, array('class' => 'form-control', 'placeholder' => 'Nomor Surat', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tgl_surat', 'Tgl. Surat', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tgl_surat', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Surat', 'required' => 'required')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('max_similarity', 'Toleransi Kesamaan Judul:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		<div class="input-group">
			{!! Form::text('max_similarity', null, array('class' => 'form-control', 'required' => 'required')) !!}
			<span class="input-group-addon">%</span>
		</div>
	</div>
	<span class="help-block">Judul akan otomatis tertolak jika melebihi nilai ini</span>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script>
	$(function(){
	$(".date").datepicker({
		format:"yyyy-mm-dd", 
		autoHide:true,
		daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
		monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
	});
});
</script>
@endpush