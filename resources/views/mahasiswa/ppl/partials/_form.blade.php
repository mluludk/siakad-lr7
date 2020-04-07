
@push('styles')
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
<style>
	.inline{
	display: inline-block;
	width: auto;
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
	{!! Form::label('matkul', 'Mata Kuliah PPL', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('matkul', $matkul, null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('tapel_id', 'Tahun Akademik', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		@if($edit)
		<p class="form-control-static">{{ $ppl -> tapel -> nama }}</p>
		@else
		{!! Form::select('tapel_id', $tapel, null, ['class' => 'form-control']) !!}
		@endif
	</div>
</div>

<div class="form-group">
	{!! Form::label('tanggal_mulai', 'Tanggal PPL', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<div class="inline">
			{!! Form::text('tanggal_mulai', null, array('class' => 'form-control date', 'autocomplete' => 'off', 'placeholder' => 'Tanggal Mulai PPL', 'required' => 'required')) !!}
		</div>
		<div class="inline">
			{!! Form::text('tanggal_selesai', null, array('class' => 'form-control date', 'autocomplete' => 'off', 'placeholder' => 'Tanggal Selesai PPL', 'required' => 'required')) !!}
		</div>
	</div>
</div>

<div class="form-group">
	{!! Form::label('sk', 'No. SK:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('sk', null, array('class' => 'form-control', 'placeholder' => 'Nomor SK', 'required' => 'required')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('tanggal_sk', 'Tanggal SK:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tanggal_sk', null, array('class' => 'form-control date', 'autocomplete' => 'off', 'placeholder' => 'Tanggal SK', 'required' => 'required')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('tgl_mulai_daftar', 'Tanggal Pendaftaran Peserta', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<div class="inline">
			{!! Form::text('tgl_mulai_daftar', null, array('class' => 'form-control date', 'autocomplete' => 'off', 'placeholder' => 'Tanggal Mulai Pendaftaran PPL', 'required' => 'required')) !!}
		</div>
		<div class="inline">
			{!! Form::text('tgl_selesai_daftar', null, array('class' => 'form-control date', 'autocomplete' => 'off', 'placeholder' => 'Tanggal Selesai Pendaftaran PPL', 'required' => 'required')) !!}
		</div>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>			