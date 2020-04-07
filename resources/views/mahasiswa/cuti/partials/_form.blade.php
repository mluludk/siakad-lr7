<div class="form-group">
	{!! Form::label('', 'Nama:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('mahasiswa_id', $mahasiswa, $mahasiswa_id, array('class' => 'form-control chosen-select')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('status', 'Jenis Cuti:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach([11 => 'Cuti Resmi', 12 => 'Cuti Tanpa Keterangan'] as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="status" ';
				if(isset($data) and $k == $data -> status) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('tapel_id', 'Tahun Akademik:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::select('tapel_id', $tapel, null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('keterangan', 'Keterangan:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::textarea('keterangan', null, array('class' => 'form-control', 'placeholder' => 'Keterangan Cuti', 'rows' => '3')) !!}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<style>
	.chosen-container{
	font-size: inherit;
	min-width: 200px;
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
	min-width: 200px;
	}
</style>
@endpush

@push('scripts')
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({no_results_text: "Tidak ditemukan hasil pencarian untuk: "});
	});
</script>
@endpush
