@extends('app')

@section('title')
Kalender Akademik
@endsection


@push('styles')
<style>
	#preview{
	width: 166px;
	height: 220px;
	padding: 5px;
	margin: 15px auto;
	border: 1px solid #999;
	position: relative;
	overflow: hidden;
	}
	
	#preview img {
	max-width: 100%;
	max-height: 100%;
	width: auto;
	height: auto;
	position: absolute;
	left: 50%;
	top: 50%;
	-webkit-transform: translate(-50%, -50%);
	-moz-transform: translate(-50%, -50%);
	-ms-transform: translate(-50%, -50%);
	transform: translate(-50%, -50%);
	}
	
	
	.status{
	width: 100%;
	text-align: center;
	margin-bottom: 10px;
	}
	.sidebar-menu-small h5{
	text-align: center;
	background-color: #023355;
	color: white;
	padding: 5px;
	}
	.sidebar-menu-small {
    list-style: none;
    margin: 0;
    padding: 0
	}
	.sidebar-menu-small > li {
    position: relative;
    margin: 0;
    padding: 0
	}
	.sidebar-menu-small > li > a {
    padding: 5px 2px 5px 12px;
    display: block
	}
	.sidebar-menu-small > li > a > .fa{
    width: 20px
	}
	
	.sidebar-menu-small > li > a {
    border-left: 3px solid transparent;
	color: #120101;
	border-bottom: 1px solid #bbb;
	}
	.sidebar-menu-small > li:hover > a,
	.sidebar-menu-small > li.active > a {
    color: #3c8dbc;
    background: #f5f9fc;
    border-left-color: #3c8dbc
	}
	
	/* 	.table td, th{
	border-top-width: 0px !important;
	} */
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Akademik
		<small>Kalender Akademik</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Kalender Akademik</li>
	</ol>
</section>
@endsection

@if(!$public)
@push('scripts')
<script>
	$(document).on('change', '.tahun', function(){
		document.location.href = '{{ url('/kalender') }}' + '?tahun=' + $(this).val();
	});
</script>
@endpush
@endif


@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title pull-left">Kalender Akademik</h3>
		@if(!$public)
		{!! Form::select('tahun', $tahun, $aktif, ['class' => 'form-control pull-left tahun', 'style' => 'display: inline-block !important; width: auto; margin-left: 5px; padding: 1px 5px !important ; height: 24px;']) !!}
		<div class="box-tools">
			<a href="{{ route('kalender.index2') }}?tahun={{ $aktif }}" class="btn btn-info btn-xs btn-flat" title="Kalender Akademin"><i class="fa fa-calendar"></i> Kalender Akademik</a>
			<a href="{{ route('kalender.create') }}?tahun={{ $aktif }}" class="btn btn-primary btn-xs btn-flat" title="Tambah Kegiatan"><i class="fa fa-plus"></i> Tambah Kegiatan Akademik</a>
		</div>
		@else
		<div class="box-tools">
			<a href="{{ route('kalender.public2') }}?tahun={{ $aktif }}" class="btn btn-info btn-xs btn-flat" title="Kalender Akademin"><i class="fa fa-calendar"></i> Kalender Akademik</a>
		</div>
		@endif
	</div>
	<div class="box-body">		
		@if(!$agenda -> count())
		Belum ada data kegiatan
		@else
	<?php $c = 1; ?>
	<table class="table table-bordered">
	<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
	<th>No.</th>
	<th>Kegiatan</th>
	<th>Kode</th>
	<th>Semester Ganjil</th>
	<th>Semester Genap</th>
	<th></th>
	</tr>
	@foreach($agenda as $a)
	<tr>
	<td>{{ $c }}</td>
	<td>{{ $a -> kegiatan }}</td>
	<td>{{ $a -> kode }}</td>
	<td>
	@if($a -> mulai1 != '0000-00-00')
	@if($a -> sampai1 != '0000-00-00')
	@if(substr($a -> mulai1, 0, 7) == substr($a -> sampai1, 0, 7))
	{{ substr($a -> mulai1, -2) }} s/d {{ formatTanggal($a -> sampai1) }}
	@else
	{{ formatTanggal($a -> mulai1) }} - {{ formatTanggal($a -> sampai1) }}
	@endif
	@else
	{{ formatTanggal($a -> mulai1) }}
	@endif
	@else
	-
	@endif
	</td>
	<td>
	@if($a -> mulai2 != '0000-00-00')
	@if($a -> sampai2 != '0000-00-00')
	@if(substr($a -> mulai2, 0, 7) == substr($a -> sampai2, 0, 7))
	{{ substr($a -> mulai2, -2) }} s/d {{ formatTanggal($a -> sampai2) }}
	@else
	{{ formatTanggal($a -> mulai2) }} - {{ formatTanggal($a -> sampai2) }}
	@endif
	@else
	{{ formatTanggal($a -> mulai2) }}
	@endif
	@else
	-
	@endif
	</td>
	<td>
	@if(!$public)
	{!! Form::open(array('class' => 'form-inline', 'method' => 'DELETE', 'route' => array('kalender.destroy', $a -> id))) !!}
	<a href="{{ route('kalender.edit', $a->id) }}" class="btn btn-warning btn-flat btn-xs"><i class="fa fa-edit"></i> Edit</a>
	<button class="btn btn-danger btn-flat btn-xs has-confirmation" type="submit" title="Hapus kegiatan"><i class="fa fa-trash-o"></i> Hapus</button>
	{!! Form::close() !!}
	@endif
	</td>
	</tr>
	<?php $c++; ?>
	@endforeach
	</table>
	<br/>
	@if($file)
	@if(explode('/', $file -> mime)[0] == 'image')
	<a href="{{ url('/download/'. $file -> id . '/' . csrf_token()) }}" class="thumbnail" title="Download kalender akademik">
	<img src="{{ url('/getfile/' . $file -> namafile) }}" />
	</a>
	@endif
	@endif
	@if(!$public)
	<a href="{{ url('/upload/file?name=Kalender%20Akademik&type=6') }}" class="btn btn-danger btn-flat" title="Upload kalender akademik"><i class="fa fa-upload"></i> Upload kalender akademik</a>
	@endif
	@endif
	</div>
	</div>
	@endsection																					