@push('styles')
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
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
	{!! Form::label('', 'No. Surat:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		<p class="form-control-static">{{ $pengajuan -> no_surat }}</p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		<p class="form-control-static">{{ $pengajuan -> nama }}</p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('nama', 'Gelombang:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama Gelombang', 'required' => 'required')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('tgl_mulai', 'Mulai', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tgl_mulai', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Mulai', 'required' => 'required')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('tgl_selesai', 'Selesai', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tgl_selesai', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Selesai', 'required' => 'required')) !!}
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
	</div>								