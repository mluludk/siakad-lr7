@push('styles')
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<style>
	.chosen-container{
	font-size: inherit;
	}
	.chosen-single{
	padding: 6px 10px !important;
	box-shadow: none !important;
    border-color: #d2d6de !important;
	background: white !important;
	height: 34px !important;
	border-radius: 0px !important;
	}
	.chosen-drop{
    border-color: #d2d6de !important;	
	box-shadow: none;
	}
</style>
@endpush

@push('scripts')
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script src="{{ asset('/js/jquery-clock-timepicker.min.js') }}"></script>
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".time").clockTimePicker();
		$(".date").datepicker({
			format:"dd-mm-yyyy", 
			startDate: "{{ $gelombang -> tgl_mulai }}",
			endDate: "{{ $gelombang -> tgl_selesai }}",
			autoHide:true,
			daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
			monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
		});
		$(".chosen-select").chosen({
			no_results_text: "Tidak ditemukan hasil pencarian untuk: "
		});
	});
</script>
@endpush

<div class="form-group">
	{!! Form::label('', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		<p class="form-control-static"><strong>{{ $jus -> mahasiswa -> nama }} ({{ $jus -> mahasiswa -> NIM }})</strong></p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('', 'Judul Skripsi:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<p class="form-control-static">{{ $jus -> mahasiswa -> skripsi -> judul }}</p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('ruang_id', 'Ruangan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::select('ruang_id', $ruang, null, array('class' => 'form-control')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('tanggal', 'Tanggal', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tanggal', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal', 'required' => 'required', 'autocomplete' => 'off')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('jam_mulai', 'Jam', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		<div style="float: left; display:inline-block">
			{!! Form::text('jam_mulai', null, array('class' => 'form-control time', 'placeholder' => 'Jam Mulai', 'required' => 'required', 'autocomplete' => 'off')) !!}
		</div>
		<div style="float: left; display:inline-block">
			{!! Form::text('jam_selesai', null, array('class' => 'form-control time', 'placeholder' => 'Jam Selesai', 'required' => 'required', 'autocomplete' => 'off')) !!}
		</div>
	</div>
</div>

<div class="form-group">
	{!! Form::label('penguji_utama', 'Penguji Utama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::select('penguji_utama', $dosen, null, array('class' => 'form-control chosen-select')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('ketua', 'Ketua:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::select('ketua', $dosen, null, array('class' => 'form-control chosen-select')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('sekretaris', 'Sekretaris:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::select('sekretaris', $dosen, null, array('class' => 'form-control chosen-select')) !!}
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>		