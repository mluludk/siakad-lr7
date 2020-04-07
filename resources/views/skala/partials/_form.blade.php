<div class="form-group">
	{!! Form::label('prodi_id', 'Prodi:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('prodi_id', $prodi, null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('huruf', 'Nilai Huruf:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-1">
		{!! Form::text('huruf', null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('angka', 'Nilai Indeks:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-1">
		{!! Form::text('angka', null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('bobot_min', 'Bobot Nilai Minimum (0-4):', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-1">
		{!! Form::text('bobot_min', null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('bobot_max', 'Bobot Nilai Maksimum (0-4):', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-1">
		{!! Form::text('bobot_max', null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('bobot_min_100', 'Bobot Nilai Minimum (0-100):', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-1">
		{!! Form::text('bobot_min_100', null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('bobot_max_100', 'Bobot Nilai Maksimum (0-100):', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-1">
		{!! Form::text('bobot_max_100', null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('lulus', 'Lulus:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-8">
		<label class="radio-inline">{!! Form::radio('lulus', 'y') !!} Ya</label>
		<label class="radio-inline">{!! Form::radio('lulus', 'n') !!} Tidak</label>
	</div>
</div>
<div class="form-group">
	{!! Form::label('predikat', 'Predikat:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('predikat', null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('mulai_efektif', 'Tanggal Efektif:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<div class="inline">
			{!! Form::text('mulai_efektif', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Mulai Efektif', 'autocomplete' => "off")) !!}
		</div>
		<div class="inline">
			{!! Form::text('akhir_efektif', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Akhir Efektif', 'autocomplete' => "off")) !!}
		</div>
	</div>	
</div>	
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-9">
		<button class="btn {{ $btn_type }} btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
<style>
	.inline{
	display: inline-block;
	width: 200px;
	}
</style>
@endpush

@push('scripts')
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script>
	$(function(){
		$(".date").datepicker({
			format:"yyyy-mm-dd", 
			autoHide:true,
			daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
			daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
			monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
		});
	});
</script>
@endpush