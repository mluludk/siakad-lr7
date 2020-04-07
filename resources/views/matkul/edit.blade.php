@extends('app')

@section('title')
Ubah data matkul - {{ $matkul -> nama ?? 'Invalid'}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mata Kuliah
		<small>Edit Mata Kuliah</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/matkul') }}"> Mata Kuliah</a></li>
		<li class="active">Edit Mata Kuliah</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Mata Kuliah</h3>
	</div>	
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($matkul, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['matkul.update', $matkul->id]]) !!}
				<input type="hidden" name="id" value="{{ $matkul -> id }}"/>
				@include('matkul/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection