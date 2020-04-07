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
	{!! Form::label('kaprodi', 'KAPRODI:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4 col-xs-9">
		{!! Form::text('kaprodi', null, array('class' => 'form-control', 'placeholder' => 'Kepala PRODI')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('telp_kaprodi', 'Telp. KAPRODI:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4 col-xs-9">
		{!! Form::text('telp_kaprodi', null, array('class' => 'form-control', 'placeholder' => 'No. Telp. Kepala PRODI')) !!}
	</div>
</div>
{!! csrf_field() !!}
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	