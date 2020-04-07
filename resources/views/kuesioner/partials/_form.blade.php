
<div class="form-group">
	{!! Form::label('kompetensi', 'Kompetensi:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::select('kompetensi', config('custom.kuesioner.kompetensi') ,null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('pertanyaan', 'Pertanyaan:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		{!! Form::text('pertanyaan', null, array('class' => 'form-control', 'placeholder' => 'Pertanyaan', 'required' => 'required', 'autofocus')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tampil', 'Tampilan:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-8">
		<label class="radio-inline">
			{!! Form::radio('tampil', 'y') !!} ditampilkan
		</label>
		<label class="radio-inline">
			{!! Form::radio('tampil', 'n') !!} disembunyikan
		</label>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>				