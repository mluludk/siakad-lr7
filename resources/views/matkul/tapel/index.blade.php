@extends('app')

@section('title')
Daftar Kelas Perkuliahan
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Kelas Kuliah</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Kelas Kuliah</li>
	</ol>
</section>
@endsection

@push('scripts')
<script>
	$('.filter').change(function(){
		$('form#filter-form').submit();
	});
</script>
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
	$(".chosen-select").chosen({
		no_results_text: "Tidak ditemukan hasil pencarian untuk: ",
		placeholder_text_single: "Pilih program studi terlebih dahulu"
	});
	$('[data-toggle="popover"]').popover({
		html: true,
		placement: 'auto top',
		trigger: 'hover'
	});
});  
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<style>
	.chosen-container{
	font-size: inherit;
	}
	.chosen-single{
	padding: 6px 10px !important;
	box-shadow: none !important;
	border-color: #d2d6de !important;
	background: white !important;
	height: 34px !important;
	border-radius: 0px !important;
	}
	.chosen-drop{
	border-color: #d2d6de !important;	
	box-shadow: none;
	}
</style>
@endpush

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/matkul/tapel') }}" class="form-inline" id="filter-form">
			{!! csrf_field() !!}
			<div class="form-group">
				<label class="sr-only" for="ta">Tahun Akademik</label>
				{!! Form::select('ta', $ta, Request::get('ta'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="prodi">PRODI</label>
				{!! Form::select('prodi', $prodi, Request::get('prodi'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="dosen">Dosen</label>
				{!! Form::select('dosen', $dosen, Request::get('dosen'), ['class' => 'form-control filter chosen-select']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="perpage">N-Data</label>
				{!! Form::select('perpage', [25 => 25, 50 => 50, 100 => 100, 200 => 200, 300 => 300 ], Request::get('perpage'), ['class' => 'form-control filter']) !!}
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
		<h3 class="box-title">Kelas Kuliah @if(count($data) > 0){{ $data[0] -> tapel }}@endif  <small>{{ $c + 1 }} - {{ $last }} dari {{ $total }}</small></h3>
		<div class="box-tools">
			<a href="{{ route('kelasperkuliahan.index') }}" class="btn btn-success btn-xs btn-flat" title="Kelas Perkuliahan Versi 2"><i class="fa fa-list"></i> Kelas Perkuliahan V2</a>
			<a href="{{ route('matkul.tapel.create') }}" class="btn btn-primary btn-xs btn-flat" title="Pendaftaran Kelas Perkuliahan Baru"><i class="fa fa-plus"></i> Tambah Kelas Perkuliahan</a>
		</div>
	</div>
	<div class="box-body">
		@if(!count($data))
		<p class="text-muted">Belum ada data</p>
		@else
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>NO.</th>
					<th>KODE</th>
					<th colspan="2">MATA KULIAH</th>
					<th>SMT</th>
					<th>SKS</th>
					<th>NAMA DOSEN</th>
					<th>PRODI</th>
					<th>RUANG</th>
					<th>KELAS</th>
					<th colspan="3">KOUTA</th>
					<th>NILAI</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $mk)			
				<?php 
					$c++; 
					$cls = '';
					if($mk -> nilai > 0 and $mk -> peserta > 0)
					{
						$per = $mk -> nilai / $mk -> peserta * 100 ;
						if($per > 40 && $per < 60) $cls = 'text text-warning';
						if($per > 60) $cls = 'text text-success';
					}
					if($cls == '') $cls = 'text text-danger';
				?>
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $mk -> kode }}</td>
					<td>
					@if(\Auth::user() -> role_id <= 2)
						<a href="{{ url('/matkul/tapel/' . $mk -> mtid . '/sesi') }}">{{ $mk -> matkul }}</a>
					@else
						{{ $mk -> matkul }}
					@endif
					</td>
					<td>{{ $mk -> keterangan }}</td>
					<td>{{ $mk -> semester }}</td>
					<td>{{ $mk -> sks }}</td>
					<td>{!! formatTimDosen($mk -> tim_dosen) !!}</td>
					<td>{{ $mk -> prodi }} {{ $mk -> program }}</td>
					<td>{{ $mk -> ruangan }}</td>
					<td>{{ $mk -> semester }}{{ $mk -> kelas }}</td>
					<td><span data-toggle="popover" data-content="<strong>Kuota</strong> Kelas Kuliah adalah {{ $mk -> kuota }}">{{ $mk -> kuota }}</span></td>
					<td><span data-toggle="popover" data-content="{{ $mk -> peserta }} Mahasiswa <strong>terdaftar</strong> di Kelas Kuliah ini">{{ $mk -> peserta }}</td>
						<td><span class="{{ $cls }}" data-toggle="popover" data-content="{{ $mk -> nilai }} Nilai Mahasiswa sudah <strong>diproses</strong>.">{{ $mk -> nilai }}</span> </td>
					<td>
					@if($mk -> locked == 'y')
					<span class="text-danger"><i class="fa fa-lock"></i></span>
					@else
					<span class="text-success"><i class="fa fa-unlock"></i></span>
					@endif
					</td>
					<td>
					<div class="btn-group">
					<a href="{{ url('/matkul/tapel/' . $mk -> mtid . '/mahasiswa') }}" class="btn btn-xs btn-primary btn-flat" title="Peserta Kuliah"><i class="fa fa-group"></i></a>
					<a href="{{ url('/matkul/tapel/' . $mk -> mtid . '/cetak/formabsensi') }}" class="btn btn-xs btn-primary btn-flat" title="Cetak Form Absensi" target="_blank"><i class="fa fa-print"></i></a>
					<a href="{{ url('/kelaskuliah/' . $mk -> mtid . '/cetak/formjurnal') }}" class="btn btn-xs btn-info btn-flat" title="Cetak Form Jurnal" target="_blank"><i class="fa fa-print"></i></a>
					</div>
					<div class="btn-group">
					<a href="{{ url('/matkul/tapel/' . $mk -> mtid . '/nilai') }}" class="btn btn-xs btn-success btn-flat" title="Nilai"><i class="fa fa-bar-chart"></i></a>
					<a href="{{ url('/matkul/tapel/'. $mk -> mtid . '/nilai/cetak') }}" class="btn btn-xs btn-success btn-flat" title="Cetak form Nilai" target="_blank"><i class="fa fa-print"></i></a>
					</div>
					<a href="{{ url('/kelaskuliah/'. $mk->mtid .'/absensi/cetak') }}" class="btn btn-xs btn-warning btn-flat" title="Cetak Absensi Sesuai Jurnal" target="_blank"><i class="fa fa-print"></i></a>
					<a href="{{ route('matkul.tapel.jurnal.print', [$mk->mtid]) }}" class="btn btn-info btn-xs btn-flat" title="Cetak Jurnal" target="_blank"><i class="fa fa-print"></i></a>
					<a href="{{ route('matkul.tapel.export', [$mk->mtid]) }}" class="btn btn-success btn-xs btn-flat" title="Export ke MS Excel"><img src="../images/excl.png"></i></a>

					<a href="{{ route('matkul.tapel.edit', $mk->mtid) }}" class="btn btn-xs btn-warning btn-flat" title="Ubah data"><i class="fa fa-edit"></i></a>
					<a href="{{ url('/matkul/tapel/' . $mk -> mtid . '/delete') }}" class="btn btn-xs btn-danger btn-flat has-confirmation" title="Hapus data"><i class="fa fa-trash"></i></a>
					</td>
					</tr>
					@endforeach
					</tbody>
					</table>
					{!! $data -> appends([
					'_token' => csrf_token(), 
					'ta' => Request::get('ta'), 
					'prodi' => Request::get('prodi'), 
					'dosen' => Request::get('dosen'), 
					'perpage' => Request::get('perpage')
					]) -> render() !!}
					</div>
					</div>
					@endif
					@endsection																																																																																					