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

<div class="form-group">
	{!! Form::label('mulai', 'Waktu:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<div class="inline">
			{!! Form::text('mulai', null, array('class' => 'form-control date', 'placeholder' => 'Mulai', 'autocomplete' => "off")) !!}
		</div>
		<div class="inline">
			{!! Form::text('selesai', null, array('class' => 'form-control date', 'placeholder' => 'Selesai', 'autocomplete' => "off")) !!}
		</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('batasRegistrasi', 'Batas Daftar Ulang:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('batasRegistrasi', null, array('class' => 'form-control date', 'placeholder' => 'Batas Daftar Ulang', 'autocomplete' => "off")) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('mulaiKrs', 'Tanggal KRS:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<div class="inline">
			{!! Form::text('mulaiKrs', null, array('class' => 'form-control date', 'placeholder' => 'Mulai', 'autocomplete' => "off")) !!}
		</div>
		<div class="inline">
			{!! Form::text('selesaiKrs', null, array('class' => 'form-control date', 'placeholder' => 'Selesai', 'autocomplete' => "off")) !!}
		</div>
	</div>	
</div>	