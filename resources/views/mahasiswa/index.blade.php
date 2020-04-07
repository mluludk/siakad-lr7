@extends('app')

@section('title')
Daftar Mahasiswa
@endsection

@push('styles')
<style>
	td > small{
	font-size: 9px;
	}
	time{
	display: block;
	font-size: 10px;
	}
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Daftar Mahasiswa</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		@if(isset($message))
		<li><a href="{{ url('/mahasiswa') }}"> Daftar Mahasiswa</a></li>
		<li class="active">Pencarian</li>
		@else
		<li class="active">Daftar Mahasiswa</li>
		@endif
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Cari data mahasiswa</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/mahasiswa/search') }}">
			{!! csrf_field() !!}
			<div class="row">
				<div class="col-xs-12">
					<div class="input-group{{ $errors -> has('q') ? ' has-error' : '' }}">
						<input type="text" class="form-control" name="q" placeholder="Pencarian ...." value="{{ Request::get('q') }}">
						<span class="input-group-btn">
							<button class="btn btn-info btn-flat" type="submit"><i class="fa fa-search"></i> Cari</button>
						</span>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/mahasiswa/filter') }}" class="form-inline" id="filter-form">
			{!! csrf_field() !!}
			@if($prodi !== null)
			<div class="form-group">
				<label class="sr-only" for="prodi">PRODI</label>
				{!! Form::select('prodi', $prodi, Request::get('prodi'), ['class' => 'form-control filter']) !!}
			</div>
			@endif
			<div class="form-group">
				<label class="sr-only" for="program">Program</label>
				{!! Form::select('program', $program, Request::get('program'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="angkatan">Angkatan</label>
				{!! Form::select('angkatan', $angkatan, Request::get('angkatan'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="semester">Semester</label>
				{!! Form::select('semester', $semester, Request::get('semester'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="status">Status</label>
				{!! Form::select('status', $status, Request::get('status'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="perpage">N-Data</label>
				{!! Form::select('perpage', [25 => 25, 50 => 50, 100 => 100, 200 => 200, 300 => 300], Request::get('perpage'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
			</div>
		</form>
	</div>
</div>

<?php 
	$role_id = \Auth::user() -> role_id; 
	
	$per_page = $mahasiswa -> perPage();
	$total = $mahasiswa -> total();
	$n = ($mahasiswa -> currentPage() - 1) * $per_page;
	$last = $n + $per_page > $total ? $total : $n + $per_page;
?>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Mahasiswa <small>{{ $n + 1 }} - {{ $last }} dari {{ $total }}</small></h3>
		@if($role_id <= 2)
		<div class="box-tools">
			<a href="{{ route('mahasiswa.import') }}" class="btn btn-success btn-xs btn-flat" title="Impor Data Mahasiswa"><i class="fa fa-file-excel-o"></i> Import Data Mahasiswa</a>
			<a href="{{ route('mahasiswa.yudisium.import') }}" class="btn btn-success btn-xs btn-flat" title="Impor Yudisium Mahasiswa"><i class="fa fa-file-excel-o"></i> Import Yudisium Mahasiswa</a>
			<a href="{{ route('mahasiswa.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data"><i class="fa fa-plus"></i> Tambah Mahasiswa</a>
		</div>
		@endif
	</div>
	<div class="box-body">
		<p class="text-muted">{{ $message ?? '' }}</p>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>NO</th>
					<th width="150px">NIM</th>
					<th width="350px">NAMA </th>
					<th width="90px">ANGKATAN </th>
					<th>LOGIN</th>
					<th width="90px">SEMESTER</th>
					<th>PRODI</th>
					<th>PROGRAM</th>
					<th>STATUS</th>
					<th>HER</th>
					<th>KRS</th>
					<th width="90px"></th>
				</tr>
			</thead>
			<tbody>
				@if(!$mahasiswa -> count())
				<tr>
					<td colspan="12" align="center">Data mahasiswa tidak ditemukan</td>
				</tr>
				</div>
				@else
				@foreach($mahasiswa as $g)
				<?php 
					$n++; 			
				?>
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $g -> NIM }}</td>
					<td>
						<a href="{{ route('mahasiswa.show', $g -> id) }}">{{ $g -> nama }}</a>
					</td>
					<td>{{ $g -> angkatan }}</td>	
					<td>
						<time class="timeago" datetime="{{ $g -> authInfo -> last_login ?? '' }}"></time>
					</td>
					<td>{{ $g -> semesterMhs }}</td>
					<td>{{ $g -> prodi -> singkatan }}</td>
					<td>{{ $g -> kelas -> nama }}</td>
					<td>{{ config('custom.pilihan.statusMhs')[$g -> statusMhs] }}</td>
					<td>{!! cekHer($aktif -> nama2, $g -> tagihan) !!}</td>
					<td>{!! checkKRS($aktif -> id, $g -> krs) !!}</td>
					<td>
						<div class="btn-group">
							@if($role_id <= 2)
							<a href="{{ route('mahasiswa.edit', $g -> id) }}" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-pencil-square-o"></i></a>
							
							@if(isset($g -> authInfo))
							<a href="{{ route('users.impersonate', $g -> authInfo -> id) }}" class="btn btn-info btn-xs btn-flat" title="Login sebagai {{ $g -> nama }}"><i class="fa fa-sign-in"></i></a>
							@else
							<button class="btn btn-info btn-xs btn-flat" title="Login sebagai {{ $g -> nama }}" disabled="disabled"><i class="fa fa-sign-in"></i></button>
							@endif
							
							<a href="{{ route('mahasiswa.delete', $g -> id) }}" class="btn btn-danger btn-xs has-confirmation btn-flat" data-message="Hapus data mahasiswa {{ $g -> nama }}?"><i class="fa fa-trash"></i></a>
							@else
							<a href="{{ route('mahasiswa.show', $g -> id) }}" class="btn btn-info btn-xs btn-flat" title="Detail Mahasiswa"><i class="fa fa-newspaper-o"></i> Detail</a>							
							@endif
						</div>
					</td>
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>
		{!! $mahasiswa -> appends([
		'_token' => csrf_token(), 
		'q' => Request::get('q'), 
		'prodi' => Request::get('prodi'), 
		'program' => Request::get('program'), 
		'angkatan' => Request::get('angkatan'), 
		'semester' => Request::get('semester'), 
		'status' => Request::get('status'), 
		'perpage' => Request::get('perpage')
		]) -> render() !!}
	</div>
</div>
@endsection		

@push('scripts')
<script>
	$('.filter').change(function(){
		$('form#filter-form').submit();
	});
</script>

<script src="{{ url('/js/jquery.timeago.js') }}" type="text/javascript"></script>
<script>
	jQuery.timeago.settings.strings = {
		prefixAgo: null,
		prefixFromNow: null,
		suffixAgo: "yang lalu",
		suffixFromNow: "dari sekarang",
		seconds: "kurang dari semenit",
		minute: "sekitar satu menit",
		minutes: "%d menit",
		hour: "sekitar sejam",
		hours: "sekitar %d jam",
		day: "sehari",
		days: "%d hari",
		month: "sekitar sebulan",
		months: "%d bulan",
		year: "sekitar setahun",
		years: "%d tahun"
	};
	jQuery(document).ready(function() {
		jQuery("time.timeago").timeago();
	});
</script>
@endpush																																											