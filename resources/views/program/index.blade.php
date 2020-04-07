@extends('app')

@section('title')
Program Kerja 
@endsection

@section('header')
<section class="content-header">
	<h1>
		Program Kerja
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Program Kerja</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-body">
		{!! $program -> program !!}		
	</div>	
	<div class="box-footer">
		<div class="box-tools">
			<a href="{{ url('/program/edit') }}" class="btn btn-warning btn-xs btn-flat" title="Edit Program Kerja"><i class="fa fa-edit"></i> Edit Program Kerja</a>
		</div>
	</div>		
</div>				
@endsection