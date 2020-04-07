@push('scripts')
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
<script>
	$(function(){
	$(".currency").inputmask('999.999.999', { numericInput: true, autoUnmask: true, removeMaskOnSubmit: true, unmaskAsNumber: true });
	$(".date").datepicker({
			format:"dd-mm-yyyy", 
			autoHide:true,
			daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
			daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
			monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
		});
	});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
<style>
	.required:after{
	content: " *";
	color: red;
	}
</style>
@endpush
<div class="form-group">
	{!! Form::label('prodi_id', 'PRODI:', array('class' => 'col-sm-3 control-label required')) !!}
	<div class="col-sm-4">
		{!! Form::select('prodi_id', $prodi, null, array('class' => 'form-control', 'placeholder' => 'Program Studi')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('angkatan', 'Angkatan:', array('class' => 'col-sm-3 control-label required')) !!}
	<div class="col-sm-3">
		{!! Form::select('angkatan', $angkatan, null, array('class' => 'form-control', 'placeholder' => 'Angkatan')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('kelas_id', 'Program:', array('class' => 'col-sm-3 control-label required')) !!}
	<div class="col-sm-3">
		{!! Form::select('kelas_id', $program, null, array('class' => 'form-control', 'placeholder' => 'Program')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jenisPembayaran', 'Jenis Pembiayaan:', array('class' => 'col-sm-3 control-label required')) !!}
	<div class="col-sm-4">
		{!! Form::select('jenisPembayaran', $jenis, null, array('class' => 'form-control', 'placeholder' => 'Jenis Pembiayaan')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jenis_biaya_id', 'Biaya:', array('class' => 'col-sm-3 control-label required')) !!}
	<div class="col-sm-4">
		{!! Form::select('jenis_biaya_id', $jbiaya, null, array('class' => 'form-control', 'placeholder' => 'BIaya')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jumlah', 'Jumlah:', array('class' => 'col-sm-3 control-label required')) !!}
	<div class="col-sm-4">
		<div class="input-group">
			<span class="input-group-addon" id="basic-addon1">Rp</span>
			{!! Form::text('jumlah', null, array('class' => 'form-control currency', 'placeholder' => 'Jumlah Pembayaran', 'required' => 'required')) !!}
		</div>
	</div>
</div>

<div class="form-group">
	{!! Form::label('cicilan[1][jml]', 'Cicilan 1:', array('class' => 'col-sm-3 control-label')) !!}
		<div class="col-sm-4">
			<div class="input-group">
				<span class="input-group-addon">Rp.</span>
				{!! Form::text('cicilan[1][jml]', null, array('class' => 'form-control currency', 'placeholder' => 'Cicilan 1 (Kosongkan jika tanpa cicilan)')) !!}
			</div>
		</div>
		<div class="col-sm-2">
			{!! Form::text("cicilan[1][tgla]", null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Awal Cicilan 1', 'autocomplete' => 'off')) !!}
		</div>
		<div class="col-sm-2">
			{!! Form::text("cicilan[1][tglb]", null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Akhir Cicilan 1', 'autocomplete' => 'off')) !!}
		</div>
</div>

<div class="form-group">
	{!! Form::label('cicilan[2][jml]', 'Cicilan 2:', array('class' => 'col-sm-3 control-label')) !!}
		<div class="col-sm-4">
			<div class="input-group">
				<span class="input-group-addon">Rp.</span>
				{!! Form::text("cicilan[2][jml]", null, array('class' => 'form-control currency', 'placeholder' => 'Cicilan 2 (Kosongkan jika tanpa cicilan)')) !!}
			</div>
		</div>
		<div class="col-sm-2">
			{!! Form::text("cicilan[2][tgla]", null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Awal Cicilan 2', 'autocomplete' => 'off')) !!}
		</div>
		<div class="col-sm-2">
			{!! Form::text("cicilan[2][tglb]", null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Akhir Cicilan 2', 'autocomplete' => 'off')) !!}
		</div>
</div>

<div class="form-group">
	{!! Form::label('cicilan[3][jml]', 'Cicilan 3:', array('class' => 'col-sm-3 control-label')) !!}
		<div class="col-sm-4">
			<div class="input-group">
				<span class="input-group-addon">Rp.</span>
				{!! Form::text("cicilan[3][jml]", null, array('class' => 'form-control currency', 'placeholder' => 'Cicilan 3 (Kosongkan jika tanpa cicilan)')) !!}
			</div>
		</div>
		<div class="col-sm-2">
			{!! Form::text("cicilan[3][tgla]", null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Awal Cicilan 3', 'autocomplete' => 'off')) !!}
		</div>
		<div class="col-sm-2">
			{!! Form::text("cicilan[3][tglb]", null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Akhir Cicilan 3', 'autocomplete' => 'off')) !!}
		</div>
</div>

<div class="form-group">
	{!! Form::label('bank_id', 'Metode Pembayaran:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::select('bank_id', $bank, null, array('class' => 'form-control', 'placeholder' => 'Metode Pembayaran')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('krs', 'Syarat KRS:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		<div class="input-group">
			{!! Form::number('krs', null, array('class' => 'form-control', 'placeholder' => 'Syarat KRS', 'min' => 0, 'max' => 100 )) !!}
			<span class="input-group-addon" id="basic-addon2">%</span>
		</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('uts', 'Syarat UTS:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		<div class="input-group">
			{!! Form::number('uts', null, array('class' => 'form-control', 'placeholder' => 'Syarat UTS', 'min' => 0, 'max' => 100)) !!}
			<span class="input-group-addon" id="basic-addon2">%</span>
		</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('uas', 'Syarat UAS:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		<div class="input-group">
			{!! Form::number('uas', null, array('class' => 'form-control', 'placeholder' => 'Syarat UAS', 'min' => 0, 'max' => 100)) !!}
			<span class="input-group-addon" id="basic-addon2">%</span>
		</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('login', 'Syarat Login:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		<label class="radio-inline">
			{!! Form::radio('login', 'y', null) !!} Ya
		</label>
		<label class="radio-inline">
			{!! Form::radio('login', 'n', null) !!} Tidak
		</label>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>		