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
		{!! Form::select('dosen_id', $dosen_list, Request::get('dosen'), ['class' => 'form-control chosen-select', 'id' => 'filter']) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('judul', 'Judul Buku:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::text('judul', null, array('class' => 'form-control', 'placeholder' => 'Judul Buku')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('klasifikasi', 'Klasifikasi:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach([1 => 'Buku Referensi', 2 => 'Buku Monograf'] as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="klasifikasi" ';
				if(isset($buku) and $k == $buku -> klasifikasi) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('penerbit', 'Penerbit:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('penerbit', null, array('class' => 'form-control', 'placeholder' => 'Nama Penerbit Buku')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('isbn', 'No. ISBN:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('isbn', null, array('class' => 'form-control', 'placeholder' => 'Nomor ISBN')) !!}
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