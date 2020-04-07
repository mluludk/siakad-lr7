<div class="form-group">
	{!! Form::label('prodi_id', 'Program Studi:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('prodi_id', $prodi, null, array('class' => 'form-control', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('nama', 'Nama:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama komponen penilaian', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('bobot', 'Bobot:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		<div class="input-group">
			<input class="form-control" placeholder="Bobot nilai" required="required" min="0" max="100" name="bobot" type="number" id="bobot" value="{{ $jenis-> bobot ?? '' }}">
			<span class="input-group-addon">%</span>
		</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('aktif', 'Status:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-8">
		<label class="radio-inline">{!! Form::radio('aktif', 'y') !!} Aktif</label>
		<label class="radio-inline">{!! Form::radio('aktif', 'n') !!} Tidak Aktif</label>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<button class="btn btn-primary btn-flat" type="submit"><i class="fa fa-floppy-o"></i>  Simpan</button>
	</div>		
</div>				