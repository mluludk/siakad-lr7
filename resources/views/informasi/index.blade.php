@extends('app')

@section('title')
Informasi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pengumuman
		<small>Daftar Pengumuman</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Pengumuman</li>
	</ol>
</section>
@endsection

@push('scripts')
<script>
	$(document).on('click', '.btn-show', function(){
		var me = $(this);
		var td = me.closest('td');
		var id = td.attr('id');
		$.ajax({
			type: "GET",
			url: '/informasi/' + id + '/show',
			dataType: 'json',
			success: function(response) {
				if(response.success)
				{
					td.siblings('.isi').html(response.isi);
					me.attr('disabled', 'disabled');
				}
			}
		});
	});
	
</script>
@endpush

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Pengumuman</h3>
		<div class="box-tools">
			<a href="{{ route('informasi.create') }}" class="btn btn-info btn-xs btn-flat" title="Posting Pengumuman"><i class="fa fa-plus"></i></a>
		</div>
	</div>
	<div class="box-body">	
		@if(!$info->count())
		Belum ada data
		@else
		<?php $c = 1; ?>
		<table class="table table-bordered table-striped">
		<thead>
			<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
				<th>No.</th>
				<th>Tanggal</th>
				<th>Isi</th>
				<th>Oleh</th>
				<th></th>
			</tr>	
			</thead>
			<tbody>
			@foreach($info as $i)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ $i -> created_at }}</td>
				<td class="isi">{{ str_limit(strip_tags($i -> isi), 50, '...') }}</td>
				<td>{{ $i -> poster -> authable -> nama }}</td>
				<td id="{{ $i -> id }}">
					<button class="btn btn-info btn-xs btn-show btn-flat"><i class="fa fa-eye"></i> Lihat</button>
					@if($i -> user_id == \Auth::user() -> id ?? \Auth::user() -> id == 2 ?? \Auth::user() -> id == 1)
					<a href="{{ route('informasi.edit', $i -> id) }}" class="btn btn-warning btn-flat btn-xs"><i class="fa fa-edit"></i> Edit</button>
					@endif
				</td>
			</tr>	
			<?php $c ++; ?>
			@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>
@endsection