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
<style>
	.inline{
	display: inline-block;
	width: 200px;
	}
</style>
@endpush

<div class="form-group">
	{!! Form::label('tahun', 'Tahun:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		<input class="form-control" placeholder="Tahun" required="required" name="tahun" type="number" id="tahun" min="2000" max="2036" value="{{ (isset($agenda) ? $agenda -> tahun : (null !== Request::get('tahun') ? Request::get('tahun') : date('Y'))) }}"/>
	</div>
</div>

<div class="form-group">
	{!! Form::label('kegiatan', 'Kegiatan:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::text('kegiatan', null, array('class' => 'form-control', 'placeholder' => 'Kegiatan', 'required' => 'required')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('kode', 'Kode:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('kode', null, array('class' => 'form-control')) !!}
		<span class="help-block">max. 3 karakter</span>
	</div>
</div>

<div class="form-group">
	{!! Form::label('mulai1', 'Smt. Ganjil:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-9">
		<div class="inline">
			{!! Form::text('mulai1', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Mulai', 'autocomplete' => 'off' )) !!}
		</div>
		<div class="inline">
			{!! Form::text('sampai1', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Selesai', 'autocomplete' => 'off' )) !!}
		</div>
	</div>
</div>

<div class="form-group">
	{!! Form::label('mulai2', 'Smt. Genap:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-9">
		<div class="inline">
			{!! Form::text('mulai2', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Mulai', 'autocomplete' => 'off' )) !!}
		</div>
		<div class="inline">
			{!! Form::text('sampai2', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Selesai', 'autocomplete' => 'off' )) !!}
		</div>
	</div>
</div>

<div class="form-group">
	{!! Form::label('fg', 'Warna:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		<input type="color" name="fg"  value="{{ $agenda -> fg ?? '#000000' }}"/>
	</div>
</div>

<div class="form-group">
	{!! Form::label('bg', 'Warna Belakang:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		<input type="color" name="bg" value="{{ $agenda -> bg ?? '#ffffff' }}"/>
		</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<button class="btn btn-primary" type="submit"><i class="fa fa-floppy-o"></i> {{ $submit_text }}</button>
	</div>		
</div>				