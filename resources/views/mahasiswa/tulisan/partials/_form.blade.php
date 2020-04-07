
<div class="form-group">
	{!! Form::label('', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<p class="form-control-static">{{ $mahasiswa -> nama }} ({{ $mahasiswa -> NIM }})</p>
	</div>
</div>
<div class="form-group">
	{!! Form::label('judul', 'Judul Tulisan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::text('judul', null, array('class' => 'form-control', 'placeholder' => 'Judul Tulisan')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('link', 'Link Website:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::text('link', null, array('class' => 'form-control', 'placeholder' => 'Link Website')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tahun', 'Tahun Tulisan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		<input class="form-control" name="tahun" type="number" id="tahun" min="1910" max="3000" value="{{ $tulisan -> tahun ?? '' }}">
	</div>
</div>
<div class="form-group">
	{!! Form::label('', '', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	