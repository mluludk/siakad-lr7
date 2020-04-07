@push('scripts')
<script src="{{ asset('/js/jquery-clock-timepicker.min.js') }}"></script>
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".time").clockTimePicker();
		
		$(".chosen-select").chosen({
			no_results_text: "Tidak ditemukan hasil pencarian untuk: ",
			placeholder_text_single: "Pilih Tahun Akademik terlebih dahulu",
			search_contains: true
		});
		
		$('#ta').on('change', function(){
			window.location.href = '{{ url('jadwal/create') }}' + '?tapel_id=' + $(this).val();
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
	.loader{
	color: #f00900;
	position: absolute;
	z-index: 999;
	top: 10px;
	right: 50%;
	display: none;
	}
</style>
@endpush

@if(isset($ta))
<div class="form-group">
	{!! Form::label('tapel_id', 'Tahun Akademik:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::select('tapel_id', $ta, Request::get('tapel_id', null), array('class' => 'form-control', 'id' => 'ta', 'placeholder' => 'Pilih Tahun Akademik')) !!}
	</div>
</div>
@else
{!! Form::hidden('tapel_id', $matkul_data -> tapel_id) !!}
@endif
@if(isset($matkul))
<div class="form-group">
	{!! Form::label('matkul_tapel_id', 'Mata Kuliah:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-9">
		{!! Form::select('matkul_tapel_id', $matkul, Request::get('id', null), array('class' => 'form-control chosen-select', 'placeholder' => 'Pilih Kelas Perkuliahan')) !!}
	</div>
</div>
@else
<div class="form-group">
	{!! Form::label('', 'Mata Kuliah:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-9">
		<p class="form-control-static">
			{{ $matkul_data -> kd . ' - ' . $matkul_data -> matkul .' - ' . $matkul_data -> prodi . ' - ' . $matkul_data -> dosen . ' - Smt ' . $matkul_data -> semester . ' - '. $matkul_data -> sks . ' SKS - ' . $matkul_data -> program . ' Kelas ' . $matkul_data -> kelas }}
		</p>
	</div>
</div>
{!! Form::hidden('matkul_tapel_id', $jadwal -> matkul_tapel_id) !!}
@endif

<div class="form-group">
	{!! Form::label('ruang_id', 'Ruang:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('ruang_id', $ruang, null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('hari', 'Hari:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::select('hari', config('custom.hari'), Request::get('hari', null), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jam_mulai', 'Mulai Jam:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('jam_mulai', Request::get('jam_mulai', null), array('class' => 'form-control time', 'placeholder' => 'Jam mulai mengajar')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jam_selesai', 'Sampai Jam:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('jam_selesai', Request::get('jam_selesai', null), array('class' => 'form-control time', 'placeholder' => 'Jam selesai mengajar')) !!}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<button class="btn btn-flat {{ $btn_type }}" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>								