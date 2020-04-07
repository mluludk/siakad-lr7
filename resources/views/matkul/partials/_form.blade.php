
<div class="form-group">
	{!! Form::label('kode', 'Kode :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('kode', null, array('class' => 'form-control', 'placeholder' => 'Kode mata kuliah')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('nama', 'Nama :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama mata kuliah', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('singkatan', 'Singkatan :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('singkatan', null, array('class' => 'form-control', 'placeholder' => 'Singkatan', 'required' => 'required')) !!}
		<span class="help-block">Maks. 3 huruf</span>
	</div>
</div>
<div class="form-group">
	{!! Form::label('prodi_id', 'PRODI :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::select('prodi_id', $prodi, null, ['class' => 'form-control']) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jenis', 'Jenis :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		<?php
			foreach(config('custom.pilihan.jenisMatkul') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="jenis" ';
				if(isset($matkul) and $k == $matkul -> jenis) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('kelompok', 'Kelompok :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::select('kelompok', config('custom.pilihan.kelompokMatkul'), null, ['class' => 'form-control']) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('total_sks', 'SKS :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-1">
			<input class="form-control" disabled id="total_sks" type="text" id="sks" name="sks_total" value={{ $matkul -> sks_total ?? 0 }} style="padding: 8px;" />
	</div>
	<span class="help-block">( SKS Tatap Muka + SKS Praktikum + SKS Praktek Lapangan + SKS Simulasi )</span>
</div>
<div class="form-group">
	{!! Form::label('sks_tm', 'SKS Tatap Muka :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-1">
		{!! Form::text('sks_tm', null, array('class' => 'form-control sks')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('sks_prak', 'SKS Praktikum :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-1">
		{!! Form::text('sks_prak', null, array('class' => 'form-control sks')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('sks_prak_lap', 'SKS Praktek Lap. :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-1">
		{!! Form::text('sks_prak_lap', null, array('class' => 'form-control sks')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('sks_sim', 'SKS Simulasi :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-1">
		{!! Form::text('sks_sim', null, array('class' => 'form-control sks')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('sap', 'Ada SAP? :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		<?php
			foreach(['y' => 'Ya', 'n' => 'Tidak'] as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="sap" ';
				if(isset($matkul) and $k == $matkul -> sap) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('silabus', 'Ada Silabus? :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		<?php
			foreach(['y' => 'Ya', 'n' => 'Tidak'] as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="silabus" ';
				if(isset($matkul) and $k == $matkul -> silabus) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('bahan_ajar', 'Ada Bahan Ajar? :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		<?php
			foreach(['y' => 'Ya', 'n' => 'Tidak'] as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="bahan_ajar" ';
				if(isset($matkul) and $k == $matkul -> bahan_ajar) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('praktek', 'Ada Praktek? :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		<?php
			foreach(['y' => 'Ya', 'n' => 'Tidak'] as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="praktek" ';
				if(isset($matkul) and $k == $matkul -> praktek) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('diktat', 'Ada Diktat? :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		<?php
			foreach(['y' => 'Ya', 'n' => 'Tidak'] as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="diktat" ';
				if(isset($matkul) and $k == $matkul -> diktat) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit"><i class="fa fa-floppy-o"></i>  Simpan</button>
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