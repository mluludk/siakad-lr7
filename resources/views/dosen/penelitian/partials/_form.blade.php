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
	<div class="col-sm-4">
		{!! Form::select('dosen_id', $dosen_list, Request::get('dosen'), ['class' => 'form-control chosen-select', 'id' => 'filter']) !!}
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
			foreach([1 => 'Pribadi', 2 => 'Kelompok'] as $k => $v) 
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
	<div class="col-sm-offset-3 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	