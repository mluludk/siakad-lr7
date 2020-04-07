@push('scripts')
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({no_results_text: "Tidak ditemukan hasil pencarian untuk: "});
	});
</script>
@endpush

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

<div class="form-group">
	{!! Form::label('', 'Tahun Akademik:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		<p class="form-control-static">{{ $pkm -> tapel -> nama }}</p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('', 'Tanggal:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		<p class="form-control-static">
			{{ formatTanggal(date('Y-m-d', strtotime($pkm -> tanggal_mulai))) }} 
			- 
			{{ formatTanggal(date('Y-m-d', strtotime($pkm -> tanggal_selesai))) }}
		</p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('', 'SK:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		<p class="form-control-static">
			{{ $pkm -> sk }}
		</p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('nama', 'Lokasi PKM:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		<p class="form-control-static">{{ $lokasi -> nama }}</p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('kuota', 'Kuota:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		<p class="form-control-static">{{ $lokasi -> kuota }}</p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('dosen_id', 'Pendamping:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('dosen_id', $dosen, null, array('class' => 'form-control chosen-select', 'placeholder' => 'Pendamping')) !!}
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	