@push('styles')
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
<style>
	.inline{
	display: inline-block;
	}
</style>	
@endpush

@push('scripts')
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script>
	$(function(){
	$(".date").datepicker({
	format:"dd-mm-yyyy", 
	autoHide:true,
	daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
	monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
	});
	});
</script>
@endpush

<div class="form-group">
	{!! Form::label('nama', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama Jadwal Wisuda', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tanggal', 'Tanggal', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		<?php $tanggal = isset($wisuda -> tanggal) ? date('d-m-Y', strtotime($wisuda -> tanggal)) : null; ?>
		{!! Form::text('tanggal', $tanggal, array('class' => 'form-control date', 'placeholder' => 'Tanggal Wisuda', 'required' => 'required')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('SKYudisium', 'No. SK:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('SKYudisium', null, array('class' => 'form-control', 'placeholder' => 'Nomor SK Yudisium', 'required' => 'required')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('tglSKYudisium', 'Tanggal SK:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tglSKYudisium', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal SK Yudisium', 'required' => 'required')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('smt_yudisium', 'Semester Yudisium:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('smt_yudisium', $tapel, null, array('class' => 'form-control')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('daftar', 'Tanggal Pendaftaran:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<div class="inline">
			{!! Form::text('tgl_daftar_mulai', null, array('class' => 'form-control date', 'placeholder' => 'Mulai', 'required' => 'required')) !!}
		</div>
		<div class="inline">
			{!! Form::text('tgl_daftar_selesai', null, array('class' => 'form-control date', 'placeholder' => 'Selesai', 'required' => 'required')) !!}
		</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('kuota', 'Kuota:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('kuota', null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	