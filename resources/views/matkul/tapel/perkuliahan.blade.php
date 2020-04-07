@extends('app')

@section('title')
Kelas Perkuliahan Prodi {{ $prodi -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>{{ $prodi -> nama }}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Kelas Kuliah Prodi {{ $prodi -> nama }}</li>
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
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/perkuliahan') }}" class="form-inline" id="filter-form">
			{!! csrf_field() !!}
			<div class="form-group">
				<label class="sr-only" for="ta">Tahun Akademik</label>
				{!! Form::select('ta', $ta, Request::get('ta'), ['class' => 'form-control filter']) !!}
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
	$per_page = $data -> perPage();
	$total = $data -> total();
	$c = ($data -> currentPage() - 1) * $per_page;
	$last = $c + $per_page > $total ? $total : $c + $per_page;
?>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Kelas Kuliah Prodi {{ $prodi -> nama }} @if(count($data) > 0){{ $data[0] -> tapel }}@endif  <small>{{ $c + 1 }} - {{ $last }} dari {{ $total }}</small></h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No.</th>
					<th>Kode</th>
					<th>Nama</th>
					<th>Ket.</th>
					<th>Smt</th>
					<th>SKS</th>
					<th>Dosen</th>
					<th>PRODI</th>
					<th>Kelas</th>
					<th>Peserta</th>
					<th>Nilai</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!count($data))
				<tr>
					<td colspan="12" align="center">Belum ada data</td>
				</tr>
				@else
				@foreach($data as $mk)			
				<?php $c++; ?>
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $mk -> kode }}</td>
					<td>{{ $mk -> matkul }}</td>
					<td>{{ $mk -> keterangan }}</td>
					<td>{{ $mk -> semester }}</td>
					<td>{{ $mk -> sks }}</td>
					<td>{{ formatTimDosen($mk -> tim_dosen) }}</td>
					<td>{{ $mk -> prodi }} {{ $mk -> program }}</td>
					<td>{{ $mk -> semester }}{{ $mk -> kelas }}</td>
					<td>{{ $mk -> peserta }} / {{ $mk -> kuota }}</td>
					<td>@if($mk -> nilai == 0)<i class="fa fa-exclamation-triangle text-danger"></i>@else<i class="fa fa-check text-success"></i>@endif</td>
					<td>
						<a href="{{ url('/matkul/tapel/' . $mk -> mtid . '/cetak/formabsensi') }}" class="btn btn-xs btn-primary btn-flat" title="Cetak Form Absensi" target="_blank"><i class="fa fa-print"></i></a>
						<a href="{{ url('/kelaskuliah/' . $mk -> mtid . '/cetak/formjurnal') }}" class="btn btn-xs btn-info btn-flat" title="Cetak Form Jurnal" target="_blank"><i class="fa fa-print"></i></a>
						
						<a href="{{ url('/kelaskuliah/'. $mk->mtid .'/absensi/cetak') }}" class="btn btn-xs btn-warning btn-flat" title="Cetak Absensi Sesuai Jurnal" target="_blank"><i class="fa fa-print"></i></a>
						<a href="{{ route('matkul.tapel.jurnal.print', [$mk->mtid]) }}" class="btn btn-info btn-xs btn-flat" title="Cetak Jurnal" target="_blank"><i class="fa fa-print"></i></a>						
					</td>
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>
		{!! $data -> appends([
		'_token' => csrf_token(), 
		'ta' => Request::get('ta'), 
		'perpage' => Request::get('perpage')
		]) -> render() !!}
	</div>
</div>
@endsection																																													