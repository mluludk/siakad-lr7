<div class="form-group">
	{!! Form::label('nama', 'Nama:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama Program', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('keterangan', 'Keterangan:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::textarea('keterangan', null, array('class' => 'form-control', 'placeholder' => 'Keterangan', 'rows' => '3')) !!}
	</div>
</div>
{!! csrf_field() !!}
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-9">
		<button class="btn {{ $btn_type }} btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	