
@push('scripts')
<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
<script>
	$(function(){
		$(".curr").inputmask('999.999.999', { numericInput: true, removeMaskOnSubmit: true });
		$(".date").inputmask("dd-mm-yyyy",{"placeholder":"dd-mm-yyyy"});
	});
</script>
@endpush

<div class="form-group">
	{!! Form::label('tanggal', 'Tanggal:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
			{!! Form::text('tanggal', date('d-m-Y'), array('class' => 'form-control date', 'placeholder' => 'Tanggal Transaksi')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jenis', 'Jenis Transaksi:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		<label class="radio-inline">
			{!! Form::radio('jenis', '1') !!} Pemasukan
		</label>
		<label class="radio-inline">
			{!! Form::radio('jenis', '2') !!} Pengeluaran
		</label>
	</div>
</div>
<div class="form-group">
	<label for="jumlah" class="col-sm-2 control-label">Jumlah:</label>
	<div class="col-sm-3">
		<div class="input-group">
			<span class="input-group-addon">Rp</span>
			{!! Form::text('jumlah', null, array('class' => 'form-control curr')) !!}
		</div>
	</div>
</div>
<div class="form-group">
	<label for="keterangan" class="col-sm-2 control-label">Ketarangan:</label>
	<div class="col-sm-6">
		{!! Form::text('keterangan', null, array('class' => 'form-control')) !!}
	</div>
	</div>
{!! csrf_field() !!}
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
	<button class="btn btn-primary btn-flat" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
</div>		
</div>	