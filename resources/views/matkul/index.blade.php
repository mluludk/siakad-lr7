@extends('app')

@section('title')
Daftar Mata Kuliah
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Daftar Mata Kuliah</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		@if(isset($message))
		<li><a href="{{ url('/matkul') }}"> Daftar Mata Kuliah</a></li>
		<li class="active">Pencarian</li>
		@else
		<li class="active">Daftar Mata Kuliah</li>
		@endif
	</ol>
</section>
@endsection

@push('scripts')
<script>
	$('.filter').change(function(){
		$('form#filter-form').submit();
	});
</script>
@endpush

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Cari data mata kuliah</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/matkul/search') }}">
			{!! csrf_field() !!}
			<div class="row">
				<div class="col-xs-12">
					<div class="input-group{{ $errors -> has('q') ? ' has-error' : '' }}">
						<input type="text" class="form-control first-form-control" name="q" placeholder="Pencarian ...." value="{{ Request::get('q') }}">
						<span class="input-group-btn">
							<button class="btn btn-info btn-flat" type="submit">Cari</button>
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
		<form method="get" action="{{ url('/matkul/filter') }}" class="form-inline" id="filter-form">
			{!! csrf_field() !!}
			<div class="form-group">
				<label class="sr-only" for="prodi">PRODI</label>
				{!! Form::select('prodi', $prodi, Request::get('prodi'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="jenis">Jenis</label>
				{!! Form::select('jenis', $jenis, Request::get('jenis'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="kelompok">Kelompok</label>
				{!! Form::select('kelompok', $kelompok, Request::get('kelompok'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="perpage">N-Data</label>
				{!! Form::select('perpage', [25 => 25, 50 => 50, 100 => 100, 200 => 200], Request::get('perpage'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
			</div>
		</form>
	</div>
</div>
<?php 
	$per_page = $matkul -> perPage();
	$total = $matkul -> total();
	$c = ($matkul -> currentPage() - 1) * $per_page;
	$last = $c + $per_page > $total ? $total : $c + $per_page;
?>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Mata Kuliah <small>{{ $c + 1 }} - {{ $last }} dari {{ $total }}</small></h3>
		<div class="box-tools">
			<a href="{{ route('matkul.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data"><i class="fa fa-plus"></i> Tambah Mata Kuliah</a>
		</div>
	</div>
	<div class="box-body">
		<p class="text-muted">{{ $message ?? '' }}</p>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No.</th>
					<th>Kode</th>
					<th>Nama</th>
					<th>SKS</th>
					<th>PRODI</th>
					<th>Jenis</th>
					<th>Kelompok</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$matkul -> count())
				<tr>
					<td colspan="8">Data mata kuliah tidak ditemukan</td>
				</tr>
				@else
				@foreach($matkul as $mk)
				<?php 
					$c++;
				?>
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $mk -> kode }}</td>
					<td>{{ $mk -> nama }}</td>
					<td>{{ $mk -> sks_tm +  $mk -> sks_prak +  $mk -> sks_prak_lap +  $mk -> sks_sim  }}</td>
					<td>{{ $mk -> prodi -> strata  }} {{ $mk -> prodi -> nama  }}</td>
					<td>{{ config('custom.pilihan.jenisMatkul')[$mk -> jenis] }}</td>
					<td>{{ config('custom.pilihan.kelompokMatkul')[$mk -> kelompok] }}</td>
					<td>
						<a href="{{ route('matkul.edit', $mk->id) }}" class="btn btn-warning btn-flat btn-xs"><i class="fa fa-pencil-square-o"></i> Edit</a>
						<a href="{{ route('matkul.delete', $mk-> id) }}" class="btn btn-danger btn-xs has-confirmation btn-flat"><i class="fa fa-trash"></i> Hapus</a>
					</td>
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>
		{!! $matkul -> appends([
		'_token' => csrf_token(), 
		'q' => Request::get('q'), 
		'prodi' => Request::get('prodi'), 
		'jenis' => Request::get('jenis'), 
		'kelompok' => Request::get('kelompok'), 
		'perpage' => Request::get('perpage')
		]) -> render() !!}
	</div>
</div>
@endsection																														