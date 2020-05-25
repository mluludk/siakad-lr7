@push('scripts')
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script>
	$(".date").datepicker({
	format:"dd-mm-yyyy", 
	autoHide:true,
	daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
	daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
	monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
});
</script>
@endpush

@push('styles')
<link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
@endsection

<div class="form-group">
	{!! Form::label('', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		<p class="form-control-static">{{ $dosen -> nama }}</p>
	</div>
</div>
<div class="form-group">
	{!! Form::label('jabatan', 'Jabatan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::select('jabatan', config('custom.pilihan.jabatan_fungsional'), null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('sk', 'SK Jabatan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('sk', null, array('class' => 'form-control', 'placeholder' => 'SK Jabatan')) !!}
	</div>
	</div>
	<div class="form-group">
		{!! Form::label('tmt', 'TMT Jabatan:', array('class' => 'col-sm-3 control-label')) !!}
		<div class="col-sm-3">
			{!! Form::text('tmt', null, array('class' => 'form-control date', 'placeholder' => 'TMT Jabatan', 'autocomplete' => 'off')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
		</div>		
	</div>						