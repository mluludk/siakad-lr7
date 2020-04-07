@push('scripts')
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
	$(".chosen-select").chosen({
		no_results_text: "Tidak ditemukan hasil pencarian untuk: "
	});
});  
</script>
@endpush
@push('styles')
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<style>
	.chosen-container{
	font-size: inherit;
	}
	.chosen-single{
	padding: 6px 10px !important;
	box-shadow: none !important;
    border-color: #d2d6de !important;
	background: white !important;
	height: 34px !important;
	border-radius: 0px !important;
	}
	.chosen-drop{
    border-color: #d2d6de !important;	
	box-shadow: none;
	}
</style>
@endpush

<div class="form-group">
	{!! Form::label('', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		@if($dosen_list === null)
		<p class="form-control-static">{{ $auth -> authable -> gelar_depan }}{{ $auth -> authable -> nama }}{{ $auth -> authable -> gelar_belakang }}</p>
		{!! Form::hidden('dosen_id', $auth -> authable_id) !!}
		@else
		{!! Form::select('dosen_id', $dosen_list, Request::get('dosen'), ['class' => 'form-control chosen-select', 'id' => 'filter']) !!}
		@endif
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
			foreach([1 => 'Nasional', 2 => 'Internasional'] as $k => $v) 
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
		{!! Form::text('penerbit', null, array('class' => 'form-control', 'placeholder' => 'Nama Penerbit Jurnal')) !!}
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
	<div class="col-sm-offset-3 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
	</div>					