@push('styles')
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<style>	
	.chosen-container{
	font-size: inherit;
	min-width: 200px;
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
	min-width: 200px;
	}
</style>
@endpush

@push('scripts')
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({no_results_text: "Tidak ditemukan hasil pencarian untuk: "});
	});
</script>
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
	{!! Form::label('kode_dikti', 'Kode:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('kode_dikti', null, array('class' => 'form-control', 'placeholder' => 'Kode PRODI')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('strata', 'Jenjang:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('strata', null, array('class' => 'form-control', 'placeholder' => 'Jenjang', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('nama', 'Nama PRODI:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama Prodi', 'required' => 'required', 'style' => 'width: 200px; display: inline-block')) !!}
		{!! Form::text('singkatan', null, array('class' => 'form-control', 'placeholder' => 'Singkatan', 'required' => 'required', 'style' => 'width: 100px; display: inline-block')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('wilayah', 'Wilayah:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2 col-xs-4">
		{!! Form::text('wilayah', null, array('class' => 'form-control', 'placeholder' => 'Wilayah')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('no_sk', 'Nomor SK:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4 col-xs-9">
		{!! Form::text('no_sk', null, array('class' => 'form-control', 'placeholder' => 'Nomor SK')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('tgl_sk', 'Tgl. SK:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tgl_sk', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal SK')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('peringkat', 'Peringkat:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2 col-xs-3">
		{!! Form::text('peringkat', null, array('class' => 'form-control', 'placeholder' => 'Peringkat')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tgl_daluarsa', 'Tgl. Daluarsa:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tgl_daluarsa', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Daluarsa')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('kaprodi_id', 'KAPRODI:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4 col-xs-9">
		{!! Form::select('kaprodi_id', $dosen, null, array('class' => 'form-control chosen-select', 'placeholder' => 'Kepala PRODI')) !!}
	</div>
</div>
{!! csrf_field() !!}
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	