@extends('app')

@section('title')
Data Perwalian Mahasiswa Prodi {{ $prodi }}
@endsection


@push('scripts')
<script>
	$('.filter').change(function(){
		$('form#filter-form').submit();
	});
</script>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Data Perwalian</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Data Perwalian Mahasiswa Prodi {{ $prodi }}</li>
	</ol>
</section>
@endsection

@section('content')

<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/perwalian') }}" class="form-inline" id="filter-form">
			{!! csrf_field() !!}
			
			<div class="form-group">
				<label class="sr-only" for="program">Nama Dosen</label>
				{!! Form::select('dosen', $dosen, Request::get('dosen'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
			</div>
		</form>
	</div>
</div>

<?php 	
	$status = config('custom.pilihan.statusMhs');
	
	if($mahasiswa -> perPage())
	{
		$per_page = $mahasiswa -> perPage();
		$total = $mahasiswa -> total();
		$n = ($mahasiswa -> currentPage() - 1) * $per_page;
		$last = $n + $per_page > $total ? $total : $n + $per_page;
	}
	else
	$n = 0;
?>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Data Perwalian Mahasiswa Prodi {{ $prodi }} @if($mahasiswa -> perPage()) @if($mahasiswa -> count())<small>{{ $n + 1 }} - {{  $last }} dari {{ $total }}</small> @endif @endif</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>NO</th>
				<th>NIM</th>
				<th>NAMA </th>
				<th>ANGKATAN </th>
				<th>SEMESTER</th>
				<th>PRODI</th>
				<th>PROGRAM</th>
				<th>DOSEN WALI</th>
				<th>STATUS</th>
				</tr>
			</thead>
			<tbody>
				@if(!$mahasiswa -> count())
				<tr>
					<td colspan="8" align="center">Data tidak ditemukan</td>
				</tr>
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
					<td>{{ $g -> semesterMhs }}</td>
					<td>{{ $g -> prodi -> singkatan }}</td>
					<td>{{ $g -> kelas -> nama }}</td>
					<td>{{ $g -> dosenwali -> gelar_depan }} {{ $g -> dosenwali -> nama }} {{ $g -> dosenwali -> gelar_belakang }}</td>
					<td>{{ $status[$g -> statusMhs] }}</td>
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>
		@if($mahasiswa -> perPage())
		{!! $mahasiswa -> appends([
		'_token' => csrf_token(), 
		'dosen' => Request::get('dosen')
		]) -> render() !!}
		@endif
	</div>
</div>
@endsection																																											