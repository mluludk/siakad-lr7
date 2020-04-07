@extends('app')

@section('title')
Daftar Kelas & Jadwal Kuliah
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Kelas & Jadwal Kuliah</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Kelas Kuliah</li>
	</ol>
</section>
@endsection

@push('styles')
<style>
	ol{
	padding-left: 15px;
	}
	.ddd{
	background: #ddd;
	}
</style>
@endpush

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
		<form method="get" action="{{ url('/kelasperkuliahan') }}" id="filter-form">
			{!! csrf_field() !!}
			<table class="table table-bordered">
				<tr>
					<td class="ddd"><label for="prodi_id">Program Studi</label></td><td>{!! Form::select('prodi_id', $prodi, Request::get('prodi_id'), ['class' => 'form-control filter']) !!}</td>
					<td class="ddd"><label for="tapel_id">Periode</label></td><td>{!! Form::select('tapel_id', $periode, Request::get('tapel_id'), ['class' => 'form-control filter']) !!}</td>
				</tr>
				<tr>
					<td class="ddd"><label for="kurikulum_id">Kurikulum</label></td><td>{!! Form::select('kurikulum_id', $kurikulum, Request::get('kurikulum_id'), ['class' => 'form-control filter']) !!}</td>
					<td class="ddd"><label for="semester_id">Semester</label></td><td>{!! Form::select('semester_id', $semester, Request::get('semester_id'), ['class' => 'form-control filter']) !!}</td>
				</tr>
			</table>
			<div class="form-group">
				<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
			</div>
		</form>
	</div>
</div>

<?php 
	$c=0;
	$hari = config('custom.hari');
?>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Kelas & Jadwal Kuliah</h3>
		<div class="box-tools">
			<a href="{{ route('matkul.tapel.index') }}" class="btn btn-primary btn-xs btn-flat" title="Kelas Perkuliahan Versi 1"><i class="fa fa-list"></i> Kelas Perkuliahan V1</a>
		</div>
	</div>
	<div class="box-body">
		@if(count($data) < 1)
		<p class="text-muted">Belum ada data</p>
		@else
		@foreach($data as $m)		
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">{{ $m['nama'] }} [{{ $m['sks'] }} SKS | {{ $m['kode'] }}]</h3>
				<div class="box-tools">
					<button class="btn btn-info btn-flat">{{ $m['kelas'] -> count() }} Kelas</button>
					<a href="{{ route('kelasperkuliahan.create', [$m['kmid'], $tapel_id]) }}" class="btn btn-primary btn-flat" title="Pendaftaran Kelas Perkuliahan Baru"><i class="fa fa-plus"></i></a>
				</div>
			</div>
			<div class="box-body">
				@if($m['kelas'] -> count() > 0)
				<table class="table table-striped">
					@foreach($m['kelas'] as $k)
					<tr>
						<td>
							<i class="fa fa-building"></i> {{ $m['semester'] }}{{ $k -> kelas2 }}
						</td>
						<td>
							<i class="fa fa-cube"></i> {{ $k -> program -> nama }}
						</td>
						<td width="10%">
							@foreach($k -> jadwal as $j)
							<div><i class="fa fa-calendar"></i> {{ $hari[$j -> hari] }}</div>
							@endforeach
						</td>
						<td width="15%">
							@foreach($k -> jadwal as $j)
							<div><i class="fa fa-clock-o"></i> {{ $j -> jam_mulai }} - {{ $j -> jam_selesai }}</div>
							@endforeach
						</td>
						<td width="10%">
							@foreach($k -> jadwal as $j)
							<div><i class="fa fa-map-marker"></i> {{ $j -> ruang -> nama }}</div>
							@endforeach
						</td>
						<td width="20%">
							@foreach($k -> tim_dosen as $d)
							<div><i class="fa fa-user"></i> {{ $d -> gelar_depan }} {{ $d -> nama }} {{ $d -> gelar_belakang }}</div>
							@endforeach
						</td>
						<td>
							{{ $k -> keterangan }}
						</td>
						<td>
							<a href="{{ url('/matkul/tapel/' . $k -> id . '/mahasiswa') }}" class="btn btn-xs btn-primary btn-flat" title="Peserta Kuliah"><i class="fa fa-group"></i> Detil Peserta</a>
						</td>
						<td>
							<a href="{{ route('kelasperkuliahan.edit', $k -> id) }}" class="btn btn-xs btn-warning btn-flat" title="Ubah data"><i class="fa fa-edit"></i></a>
							<a href="{{ url('/matkul/tapel/' . $k -> id . '/delete') }}" class="btn btn-xs btn-danger btn-flat has-confirmation" title="Hapus data"><i class="fa fa-trash"></i></a>
						</td>
						<td>
							<a href="{{ url('/matkul/tapel/' . $k -> id . '/cetak/formabsensi') }}" class="btn btn-xs btn-primary btn-flat" title="Cetak Form Absensi" target="_blank"><i class="fa fa-print"></i></a>						
							<a href="{{ url('/matkul/tapel/'. $k -> id . '/nilai/cetak') }}" class="btn btn-xs btn-success btn-flat" title="Cetak form Nilai" target="_blank"><i class="fa fa-copy"></i></a>
						</td>
					</tr>
					@endforeach
				</table>
				@endif
			</div>
		</div>
		@endforeach
	</div>
</div>
@endif
@endsection																																																																												