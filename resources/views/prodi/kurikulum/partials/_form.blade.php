<div class="form-group">
	{!! Form::label('nama', 'Nama :', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama Kurikulum', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('angkatan', 'Angkatan :', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('angkatan', null, array('class' => 'form-control', 'placeholder' => 'Angkatan', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('prodi_id', 'Program Studi :', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::select('prodi_id', $prodi, null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tapel_mulai', 'Mulai Berlaku :', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('tapel_mulai', $tapel, null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('sks_wajib', 'Jumlah SKS Wajib :', array('class' => 'col-sm-3 control-label')) !!}
	<div class="inline-block">
		<div class="col-sm-1">
			{!! Form::text('sks_wajib', null, array('class' => 'form-control sks', 'required', 'style' => "padding: 8px;" )) !!}
		</div>
	</div>
	<div class="inline-block">
		{!! Form::label('sks_pilihan', 'Jumlah SKS Pilihan :', array('class' => 'col-sm-3 control-label')) !!}
	</div>
	<div class="inline-block">
		<div class="col-sm-1">
			{!! Form::text('sks_pilihan', null, array('class' => 'form-control sks', 'style' => "padding: 8px;" )) !!}
		</div>
	</div>
	<div class="inline-block">
		{!! Form::label('sks', 'Jumlah SKS :', array('class' => 'col-sm-3 control-label')) !!}
	</div>
	<div class="inline-block">
		<div class="col-sm-1">
			<input class="form-control" disabled id="total_sks" type="text" id="sks" name="sks_total" value={{ $total_sks ?? 0 }} style="padding: 8px;" />
		</div>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	

@push('scripts')
<script>
	$('.sks').keyup(function()
	{
		var total = 0;
		$('.sks').each(function()
		{
			if(parseInt($(this).val()) > 0) total += parseInt($(this).val());
		});
		$('#total_sks').val(total);
	});
</script>
@endpush