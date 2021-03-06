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
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({
			no_results_text: "Tidak ditemukan hasil pencarian untuk: ",
			placeholder_text_single: "Pilih program studi terlebih dahulu"
		});
	});  
</script>
@endpush

@push('styles')
<link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
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
	.loader{
	color: #f00900;
	position: absolute;
	z-index: 999;
	top: 10px;
	right: 50%;
	display: none;
	}
</style>
@endpush

<div class="form-group">
	{!! Form::label('dosen_id', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('dosen_id', $dosen, null, array('class' => 'form-control chosen-select')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('jabatan', 'Jabatan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('jabatan', $jabatan, null, array('class' => 'form-control')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('no_sk', 'Nomor SK Penugasan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('no_sk', null, array('class' => 'form-control', 'placeholder' => 'Nomor SK')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('tanggal_mulai', 'Tgl. Mulai Penugasan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('tanggal_mulai', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Mulai Penugasan', 'autocomplete' => 'off')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('tanggal_selesai', 'Tgl. Selesai Penugasan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('tanggal_selesai', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Selesai Penugasan', 'autocomplete' => 'off')) !!}
	</div>
</div>

{!! csrf_field() !!}
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	