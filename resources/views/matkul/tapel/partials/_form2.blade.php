@push('scripts')
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
<script>
	$(function(){
		$(".time").inputmask("hh:mm",{"placeholder":"hh:mm"});
		
		$(".chosen-select").chosen({
			no_results_text: "Tidak ditemukan hasil pencarian untuk: "
		});
		
		$('.btn-rem').on('click', function(){
			var vl = $( ".dosen-select" ).val();
			
			if(vl < 1) return;
			
			$('#h_' + vl).remove();
			$('#' + vl).remove();
		});
		
		$('.btn-add').on('click', function(){
			var tx = $( ".dosen-select option:selected" ).text();
			var vl = $( ".dosen-select" ).val();
			
			if(vl < 1) return;
			if($('li#' + vl).length) return;
			
			$( ".dosen-select" ).after('<input type="hidden" name="tim_dosen[]"  id="h_'+ vl +'"value="'+ vl +'">');
			
			if($('ol.tim_dosen').length)
			{
				$('ol.tim_dosen').append('<li id="'+ vl +'">' + tx + '</li>');
			}
			else
			{
				$( ".dosen-select" ).before('<ol class="tim_dosen"><li id="'+ vl +'">' + tx + '</li></ol>');
			}
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
	{!! Form::label('', 'Program Studi:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		<p class="form-control-static">{{ $kurikulum_matkul -> kurikulum -> prodi -> strata }} - {{ $kurikulum_matkul -> kurikulum -> prodi  -> nama }}</p>
		{!! Form::hidden('prodi_id', $kurikulum_matkul -> kurikulum -> prodi -> id) !!}
		{!! Form::hidden('kurikulum_id', $kurikulum_matkul -> kurikulum -> id) !!}
		{!! Form::hidden('semester_id', $kurikulum_matkul -> semester) !!}
		{!! Form::hidden('kurikulum_matkul_id', $kurikulum_matkul -> id) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('', 'Semester:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-3">
		<p class="form-control-static">{{ $ta -> nama }}</p>
		{!! Form::hidden('tapel_id', $ta -> id) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('', 'Kurikulum:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-3">
		<p class="form-control-static">{{ $kurikulum_matkul -> kurikulum -> nama }} {{ $kurikulum_matkul -> kurikulum -> angkatan }}</p>
	</div>
</div>
<div class="form-group">
	{!! Form::label('', 'Mata Kuliah:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-7" style="position: relative">
		<p class="form-control-static">{{ $kurikulum_matkul -> matkul -> kode }} - {{ $kurikulum_matkul -> matkul -> nama }} ({{ $kurikulum_matkul -> matkul -> sks_total }} sks)</p>
	</div>
</div>

<hr/>

<div class="form-group">
	{!! Form::label('program', 'Program:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		<div style="display:inline-block;padding-right: 10px; ">
			{!! Form::select('kelas', $program, null, array('class' => 'form-control', 'style' => 'width: 180px;')) !!}
		</div>
		<div style="display:inline-block; width: 80px;">
			<strong>Kelas:</strong>
		</div>
		<div style="display:inline-block;padding-right: 10px; ">
			{!! Form::select('kelas2', $kelas, null, array('class' => 'form-control')) !!}
		</div>
	</div>
</div>

<div class="form-group">
	{!! Form::label('hari', 'Hari:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		<div style="display:inline-block;padding-right: 10px; ">
			{!! Form::select('hari', config('custom.hari'), null, array('class' => 'form-control', 'style' => 'width: 180px;')) !!}
		</div>
		<div style="display:inline-block; width: 80px;">
			<strong>Ruang:</strong>
		</div>
		<div style="display:inline-block;">
			{!! Form::select('ruang_id', $ruang, null, array('class' => 'form-control', 'style' => 'width: 180px;')) !!}
		</div>
	</div>
</div>

<div class="form-group">
	{!! Form::label('jam_mulai', 'Jam Mulai:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		<div style="display:inline-block;padding-right: 10px; ">
			{!! Form::text('jam_mulai', null, array('class' => 'form-control time', 'placeholder' => 'Jam mulai mengajar', 'style' => 'width: 180px;')) !!}
		</div>
		
		<div style="display:inline-block; width: 80px;">
			<strong>Sampai:</strong>
		</div>
		
		<div style="display:inline-block;">
			{!! Form::text('jam_selesai', null, array('class' => 'form-control time', 'placeholder' => 'Jam selesai mengajar', 'style' => 'width: 180px;')) !!}
		</div>
	</div>
</div>

<div class="form-group">
	{!! Form::label('dosen_id', 'Dosen:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		<div style="display:inline-block;">
			@if(isset($tim_dosen) and is_array($tim_dosen))
			<ol class="tim_dosen">
				@foreach($tim_dosen as $k => $v)
				<li id="{{ $k }}">{{ $v }}</li>
				@endforeach
			</ol>
			@endif
			
			{!! Form::select('dosen_id', $dosen, null, array('class' => 'form-control chosen-select dosen-select')) !!}
			
			@if(isset($tim_dosen) and is_array($tim_dosen))
			@foreach($tim_dosen as $k => $v)
			<input type="hidden" name="tim_dosen[]"  id="h_{{ $k }}"value="{{ $k }}">
			@endforeach
			@endif
			
		</div>
		<button class="btn btn-success btn-flat btn-add" type="button"><i class="fa fa-plus"></i></button>
		<button class="btn btn-danger btn-flat btn-rem" type="button"><i class="fa fa-minus"></i></button>
	</div>
</div>

<div class="form-group">
	{!! Form::label('keterangan', 'Keterangan:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::textarea('keterangan', null, array('class' => 'form-control', 'rows' => 3)) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('bahasan', 'Bahasan:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::textarea('bahasan', null, array('class' => 'form-control', 'rows' => 3)) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('kuota', 'Kapasitas:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('kuota', null, array('class' => 'form-control')) !!}
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-8">
		<button class="btn {{ $btn_type ?? '' }} btn-flat" type="submit"><i class="fa fa-floppy-o"></i>  {{ $submit_text }}</button>
	</div>		
</div>											