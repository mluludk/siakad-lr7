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
<link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
@endpush

<div class="form-group">
	{!! Form::label('', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		@if($dosen_list === null)
		<p class="form-control-static">{{ $auth -> authable -> gelar_depan }}{{ $auth -> authable -> nama }}{{ $auth -> authable -> gelar_belakang }}</p>
		{!! Form::hidden('dosen_id', $auth -> authable_id) !!}
		@else
		{!! Form::select('dosen_id', $dosen_list, Request::get('dosen'), ['class' => 'form-control chosen-select']) !!}
		@endif
	</div>
</div>
<div class="form-group">
	{!! Form::label('tapel_id', 'Tahun Ajaran:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::select('tapel_id', $tapel, null, array('class' => 'form-control', 'placeholder' => 'Tahun Ajaran')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('prodi_id', 'Program Studi:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('prodi_id', $prodi, null, array('class' => 'form-control', 'placeholder' => 'Program Studi')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('no_surat_tugas', 'No. Surat Tugas:', array('class' => 'col-sm-3 control-label')) !!}
<div class="col-sm-5">
{!! Form::text('no_surat_tugas', null, array('class' => 'form-control', 'placeholder' => 'No. Surat Tugas')) !!}
</div>
</div>
<div class="form-group">
{!! Form::label('tgl_surat_tugas', 'Tgl. Surat Tugas:', array('class' => 'col-sm-3 control-label')) !!}
<div class="col-sm-3">
{!! Form::text('tgl_surat_tugas', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Surat Tugas', 'autocomplete' => 'off')) !!}
</div>
</div>
<div class="form-group">
{!! Form::label('tmt_surat_tugas', 'TMT Surat Tugas:', array('class' => 'col-sm-3 control-label')) !!}
<div class="col-sm-3">
{!! Form::text('tmt_surat_tugas', null, array('class' => 'form-control date', 'placeholder' => 'TMT Surat Tugas', 'autocomplete' => 'off')) !!}
</div>
</div>
<div class="form-group">
{!! Form::label('berlaku_sampai', 'Berlaku sampai:', array('class' => 'col-sm-3 control-label')) !!}
<div class="col-sm-3">
{!! Form::text('berlaku_sampai', null, array('class' => 'form-control date', 'placeholder' => 'Berlaku sampai', 'autocomplete' => 'off')) !!}
</div>
</div>
<div class="form-group">
{!! Form::label('homebase', 'Homebase:', array('class' => 'col-sm-3 control-label')) !!}
<div class="col-sm-8">
<?php
foreach(['Tidak', 'Ya'] as $k => $v) 
{
echo '<label class="radio-inline">';
echo '<input type="radio" name="homebase" ';
if(isset($penugasan) and $k == $penugasan -> homebase) echo 'checked="checked" ';
echo 'value="'. $k .'"> '. $v .'</label>';
}
?>
</div>
</div>
<div class="form-group">
{!! Form::label('keterangan', 'Keterangan:', array('class' => 'col-sm-3 control-label')) !!}
<div class="col-sm-9">
{!! Form::textarea('keterangan', null, array('class' => 'form-control', 'placeholder' => 'Keterangan')) !!}
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
</div>		
</div>	