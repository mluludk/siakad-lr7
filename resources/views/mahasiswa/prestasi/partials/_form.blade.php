<div class="form-group">
	{!! Form::label('', 'Nama:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		<p class="form-control-static">{{ $mahasiswa -> nama }}</p>
	</div>
</div>
<div class="form-group">
	{!! Form::label('jenis', 'Bidang:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::select('jenis', config('custom.pilihan.dikti.jenis_prestasi'), null, array('class' => 'form-control', 'placeholder' => 'Bidang Prestasi')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tingkat', 'Tingkat:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::select('tingkat', config('custom.pilihan.dikti.tingkat_prestasi'), null, array('class' => 'form-control', 'placeholder' => 'Tingkat Prestasi')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('nama', 'Nama:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama Prestasi', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tahun', 'Tahun:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		<input class="form-control" placeholder="Tahun" name="tahun" type="number" id="tahun" min="1970" max="2099" required='required' value="{{ $prestasi -> tahun ?? '' }}">
	</div>
</div>
<div class="form-group">
	{!! Form::label('penyelenggara', 'Penyelenggara:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::text('penyelenggara', null, array('class' => 'form-control', 'placeholder' => 'Instansi Penyelenggara', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('peringkat', 'Peringkat:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-1">
		<input class="form-control" placeholder="Peringkat" name="peringkat" type="number" id="peringkat" min="0" required='required' value="{{ $prestasi -> peringkat ?? '' }}">
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	