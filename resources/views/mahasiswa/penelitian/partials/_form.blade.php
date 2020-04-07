
<div class="form-group">
	{!! Form::label('', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<p class="form-control-static">{{ $mahasiswa -> nama }} ({{ $mahasiswa -> NIM }})</p>
	</div>
</div>
<div class="form-group">
	{!! Form::label('tahun', 'Tahun Penelitian:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		<input class="form-control" name="tahun" type="number" id="tahun" min="1910" max="3000" value="{{ $penelitian -> tahun ?? '' }}">
	</div>
</div>
<div class="form-group">
	{!! Form::label('judul', 'Judul Penelitian:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::text('judul', null, array('class' => 'form-control', 'placeholder' => 'Judul Penelitian')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jenis', 'Jenis Penelitian:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach($jenis as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="jenis" ';
				if(isset($penelitian) and $k == $penelitian -> jenis) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('ketua_penelitian', 'Ketua Penelitian:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('ketua_penelitian', null, array('class' => 'form-control', 'placeholder' => 'Ketua Penelitian (isi jika merupakan penelitian kelompok)')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('dana_pribadi', 'Nilai Dana Pribadi:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		<div class="input-group">
  		<span class="input-group-addon">Rp</span>
		{!! Form::text('dana_pribadi', null, array('class' => 'form-control', 'placeholder' => 'Nilai Dana Pribadi')) !!}
	</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('dana_lembaga', 'Nilai Dana Lembaga:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		<div class="input-group">
  		<span class="input-group-addon">Rp</span>
		{!! Form::text('dana_lembaga', null, array('class' => 'form-control', 'placeholder' => 'Nilai Dana Lembaga')) !!}
	</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('dana_hibah_nasional', 'Nilai Dana Hibah Nasional:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		<div class="input-group">
  		<span class="input-group-addon">Rp</span>
		{!! Form::text('dana_hibah_nasional', null, array('class' => 'form-control', 'placeholder' => 'Nilai Hibah Nasional')) !!}
	</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('dana_hibah_internasional', 'Nilai Dana Hibah Internasional:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		<div class="input-group">
  		<span class="input-group-addon">Rp</span>
		{!! Form::text('dana_hibah_internasional', null, array('class' => 'form-control', 'placeholder' => 'Nilai Hibah Internasional')) !!}
	</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('', '', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	