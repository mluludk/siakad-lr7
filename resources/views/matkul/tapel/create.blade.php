@extends('app')

@section('title')
Buka Kelas Kuliah
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pekuliahan
		<small>Tambah Kelas Kuliah</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/matkul/tapel') }}"> Kelas Kuliah</a></li>
		<li class="active">Tambah Kelas Kuliah</li>
	</ol>
</section>
@endsection

@push('scripts')
<script>
	$('select[name="angkatan"]').change(function(){
		$.ajax({
			type: "POST",
			data: {
				_token: "{{ csrf_token() }}",
				prodi: $('select[name="prodi_id"]').val(),
				angkatan: $(this).val()
			},
			url: '{{ url("matkul/tapel/getmatkullist") }}',
			beforeSend: function(){
				$('.loader-matkul').show();
			},
			success: function(data) {
				$('select[name="kurikulum_matkul_id"]').empty().html(data).trigger("chosen:updated");
				$('.loader-matkul').hide();
			}
		});
	});
	
	$('select[name="prodi_id"]').change(function(){
		$.ajax({
			type: "POST",
			data: {
				_token: "{{ csrf_token() }}",
				prodi: $(this).val()
			},
			url: '{{ url("matkul/tapel/getangkatanlist") }}',
			beforeSend: function(){
				$('.loader-angkatan').show();
			},
			success: function(data) {
				$('select[name="angkatan"]').empty().html(data);
				$('.loader-angkatan').hide();
			}
		});
	});
</script>	
@endpush

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Kelas Kuliah</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\Matkul, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['matkul.tapel.store']]) !!}
		<div class="form-group">
			{!! Form::label('prodi_id', 'Program Studi:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-8">
				<div style="display: inline-block">
					{!! Form::select('prodi_id', $prodi, null, array('class' => 'form-control')) !!}
				</div>
				<div style="display: inline-block">
					{!! Form::select('kelas', $kelas, null, array('class' => 'form-control')) !!}
				</div>
				<div style="display: inline-block;position: relative">
					<i class="fa fa-spinner fa-spin loader loader-angkatan"></i>
					{!! Form::select('angkatan', [], null, array('class' => 'form-control')) !!}
				</div>
			</div>
		</div>
		@include('matkul/tapel/partials/_form', ['btn_type' => 'btn-primary', 'submit_text' => 'Simpan'])
		{!! Form::close() !!}
	</div>
</div>
@endsection