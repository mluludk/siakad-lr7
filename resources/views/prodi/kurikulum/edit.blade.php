@extends('app')

@section('title')
Edit Kurikulum
@endsection

@section('header')
<section class="content-header">
	<h1>
		Kurikulum
		<small>Edit data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kurikulum') }}"> Kurikulum</a></li>
		<li class="active">Edit data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Kurikulum</h3>
	</div>	
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($kurikulum, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['prodi.kurikulum.update', $kurikulum->id]]) !!}
				@include('prodi/kurikulum/partials/_form', ['btn_type' => 'btn-warning'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection