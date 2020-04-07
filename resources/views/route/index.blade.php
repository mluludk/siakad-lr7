@extends('app')

@section('title')
Routes & Navigation Links
@endsection

@section('header')
<section class="content-header">
	<h1>
		Navigation Link
		<small>List</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Routes & Navigation Links</li>
	</ol>
</section>
@endsection

@push('scripts')
<script>
	$(document).on('change', '.role', function(){
		window.location.href="{{ url('/route') }}/for/" + $(this) .val();
	});
</script>
@endpush

@push('styles')
<style>
	.roles{
	display:inline-block;
	}
</style>
@endpush

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Routes & Navigation Links for
			<div class="roles">
				{!! Form::select('role_id', $roles, $role, array('class' => 'form-control input-sm role', 'placeholder' => '-- All User --')) !!}
			</div>
		</h3>
		<div class="box-tools">
			<a href="{{ route('route.create') }}" class="btn btn-primary btn-xs btn-flat" title="New Navigation Link"><i class="fa fa-plus"></i> New</a>
		</div>
	</div>
	<div class="box-body">
		@if(count($route) < 1)
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered" style="font-size: 12px;">
			<thead>
				<tr>
					<th>No.</th>
					<th>Group</th>
					<th>Method</th>
					<th>Name</th>
					<th>Uses</th>
					<th>Icon</th>
					<th>Label</th>
					<th>URL</th>
					<th>Parent</th>
					<th>Pos.</th>
					<th>Vs</th>
					<th>Roles</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($route as $g)
				<tr>
					<td>{{ $g[0] }}</td>
					<td>{{ $g[9] }}</td>
					<td>{{ $g[10] }}</td>
					<td>{{ $g[11] }}</td>
					<td>{{ $g[12] }}</td>
					<td>{!! $g[1] !!}</td>
					<td>{{ $g[2] }}</td>
					<td>{{ $g[3] }}</td>
					<td>{{ $g[4] }}</td>
					<td>{{ $g[6] }}</td>
					<td>@if($g[7] == 'y')<i class="fa fa-eye-slash text-danger"></i>@else<i class="fa fa-eye"></i>@endif</td>
					<td>{{ $g[13] }}</td>
					<td>
						@if($g[2] != '')<a href="{{ route('route.create') }}?parent={{ $g[8] }}" class="btn btn-primary btn-xs btn-flat" title="Create Sub-route"><i class="fa fa-code-fork"></i></a>@endif
						<a href="{{ route('route.show', $g[8]) }}" class="btn btn-info btn-xs btn-flat" title="Show Navigation Link"><i class="fa fa-search"></i></a>
						<a href="{{ route('route.edit', $g[8]) }}" class="btn btn-warning btn-xs btn-flat" title="Edit Navigation Link"><i class="fa fa-edit"></i></a>
						<a href="{{ route('route.delete', $g[8]) }}" class="btn btn-danger btn-xs btn-flat" title="Delete Navigation Link"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif
@endsection																			