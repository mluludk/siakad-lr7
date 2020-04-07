
<div class="form-group">
	{!! Form::label('', 'Tahun Akademik:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		<p class="form-control-static">{{ $pkm -> tapel -> nama }}</p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('', 'Tanggal:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		<p class="form-control-static">
			{{ formatTanggal(date('Y-m-d', strtotime($pkm -> tanggal_mulai))) }} 
			- 
			{{ formatTanggal(date('Y-m-d', strtotime($pkm -> tanggal_selesai))) }}
		</p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('', 'SK:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		<p class="form-control-static">
			{{ $pkm -> sk }}
		</p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('nama', 'Lokasi PKM:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Lokasi PKM', 'required' => 'required')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('kuota', 'Kuota:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::number('kuota', null, array('class' => 'form-control', 'placeholder' => 'Kuota', 'required' => 'required')) !!}
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	