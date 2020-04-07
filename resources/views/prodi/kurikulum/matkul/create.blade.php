@extends('app')

@section('title')
Tambah Mata Kuliah Kurikulum {{ $kurikulum -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Kurikulum {{ $kurikulum -> nama }}
		<small> Tambah Mata Kuliah</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kurikulum') }}"> Kurikulum</a></li>
		<li><a href="{{ url('/kurikulum/' . $kurikulum -> id . '/detail') }}"> {{ $kurikulum -> nama }}</a></li>
		<li class="active"> Tambah Mata Kuliah</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Mata Kuliah Kurikulum {{ $kurikulum -> nama }}</h3>
	</div>	
	<div class="box-body">
		{!! Form::model(new Siakad\KurikulumMatkul, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['prodi.kurikulum.matkul.store', $kurikulum -> id]]) !!}
		<div class="form-group">
			{!! Form::label('matkul_id', 'Mata Kuliah :', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-6">
				{!! Form::select('matkul_id', $matkul, null, array('class' => 'form-control chosen-select')) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('semester', 'Semester :', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-2">
				{!! Form::text('semester', null, ['class' => 'form-control', 'required' => 'required']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('wajib', 'Wajib? :', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-2">
				<div class="checkbox">
					<label>
						<input type="checkbox" name="wajib" id="wajib" value="y">
					</label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-9">
				<button class="btn btn-primary btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
			</div>		
		</div>	
		{!! Form::close() !!}
	</div>
</div>
@endsection

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

@push('scripts')
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({no_results_text: "Tidak ditemukan hasil pencarian untuk: "});
	});  
</script>
@endpush