
<div class="form-group">
	{!! Form::label('topic', 'Topik:', array('class' => 'col-sm-2 control-label required')) !!}
	<div class="col-sm-6">
		{!! Form::text('topic', null, array('class' => 'form-control', 'placeholder' => 'Topik', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-magic"></i> Buat</button>
	</div>		
</div>	