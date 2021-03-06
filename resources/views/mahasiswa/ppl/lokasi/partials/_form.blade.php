
<div class="form-group">
	{!! Form::label('', 'Mata Kuliah PPL:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		<p class="form-control-static">{{ $ppl -> matkul }} ({{ $ppl -> kode }})</p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('', 'Prodi:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		<p class="form-control-static">{{ $ppl -> strata }} {{ $ppl -> prodi }}</p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('', 'Tahun Akademik:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		<p class="form-control-static">{{ $ppl -> tapel }}</p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('nama', 'Lokasi PPL:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Lokasi PPL', 'required' => 'required')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('kuota', 'Kuota:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::number('kuota', null, array('class' => 'form-control', 'placeholder' => 'Kuota', 'required' => 'required')) !!}
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	