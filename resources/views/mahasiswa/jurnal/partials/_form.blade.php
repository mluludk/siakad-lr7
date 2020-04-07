
<div class="form-group">
	{!! Form::label('', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<p class="form-control-static">{{ $mahasiswa -> nama }} ({{ $mahasiswa -> NIM }})</p>
	</div>
</div>
<div class="form-group">
	{!! Form::label('judul_artikel', 'Judul Artikel:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::text('judul_artikel', null, array('class' => 'form-control', 'placeholder' => 'Judul Artikel')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('nama_jurnal', 'Nama Jurnal:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('nama_jurnal', null, array('class' => 'form-control', 'placeholder' => 'Nama Jurnal')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('website_jurnal', 'Website Jurnal:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('website_jurnal', null, array('class' => 'form-control', 'placeholder' => 'Website Jurnal')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('level_jurnal', 'Level Jurnal:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach($level as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="level_jurnal" ';
				if(isset($jurnal) and $k == $jurnal -> level_jurnal) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('penerbit', 'Penerbit:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('penerbit', null, array('class' => 'form-control', 'placeholder' => 'Nama Penerbit')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('issn', 'No. ISSN:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('issn', null, array('class' => 'form-control', 'placeholder' => 'Nomor ISSN / ISBN')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('akreditasi', 'Akreditasi', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach(['Belum', 'Sudah'] as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="akreditasi" ';
				if(isset($jurnal) and $k == $jurnal -> akreditasi) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('tahun_terbit', 'Tahun Terbit:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('tahun_terbit', null, array('class' => 'form-control', 'placeholder' => 'Tahun Terbit')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('', '', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	