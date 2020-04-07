
<div class="form-group">
	<label for="golongan" class="col-sm-1 control-label">Golongan:</label>
	<div class="col-sm-3">
		{!! Form::select('golongan', $golongan, null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	<label for="nama" class="col-sm-1 control-label">Nama:</label>
	<div class="col-sm-4">
		{!! Form::text('nama', null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	<label for="periode" class="col-sm-1 control-label">Periode:</label>
	<div class="col-sm-3">
		{!! Form::select('periode', $periode, null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	<label for="keterangan" class="col-sm-1 control-label">Keterangan:</label>
	<div class="col-sm-5">
		{!! Form::textarea('keterangan', null, array('class' => 'form-control', 'rows' => '3')) !!}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-1 col-sm-10">
		<button class="btn btn-flat {{ $btn_type }}" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	