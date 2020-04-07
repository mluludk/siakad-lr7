@extends('app')

@section('title')
Detail Jurnal Perkuliahan
@endsection

@push('styles')
<style>
	.control-label{
	text-align: left !important;
	}
	.form-control-static:before{
	content: ":  ";
	}
	.form-group{
	margin-bottom: 0px;
	}
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Detail Jurnal Perkuliahan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kelaskuliah') }}"> Mengajar Kelas</a></li>
		<li><a href="{{ route('matkul.tapel.jurnal.index', $matkul_tapel_id) }}"> Jurnal Perkuliahan</a></li>
		<li class="active"> Edit Jurnal Perkuliahan</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Data Mata Kuliah</h3>
		<div class="box-tools">
			<a href="{{ url('/kelaskuliah/' . $matkul_tapel_id . '/peserta') }}" class='btn btn-primary btn-xs btn-flat' title='Peserta'><i class='fa fa-group'></i></a>
			<a href="{{ url('/kelaskuliah/' . $matkul_tapel_id . '/absensi') }}" class='btn btn-danger btn-xs btn-flat' title='Absensi'><i class='fa fa-font'></i></a>
			<a href="{{ url('/kelaskuliah/' . $matkul_tapel_id . '/nilai') }}" class='btn btn-success btn-xs btn-flat' title='Nilai'><i class='fa fa-bar-chart'></i></a>
		</div>
	</div>
	<div class="box-body">	
		<table width="100%">
			<tr>
				<th width="20%">Matakuliah & Semester</th><th width="2%">:</th><td width="30%">{{ $data -> matkul }} ({{ $data -> kd }}) ({{ $data -> semester }})</td>
				<th width="20%">Dosen</th><th width="2%">:</th><td>{{ $data -> dosen }}</td>
			</tr>
			<tr>
				<th>Program & Kelas</th><th>:</th><td>{{ $data -> program }} @if(isset($data -> kelas)) ({{ $data -> kelas }})@endif</td>
				<th>PRODI</th><th>:</th><td>{{ $data -> prodi }} ({{ $data -> singkatan }})</td>
			</tr>
			<tr>
				<th>Jadwal & Ruang</th><th>:</th><td>@if(isset($data -> hari)){{ config('custom.hari')[$data -> hari] }}, {{ $data -> jam_mulai }} - {{ $data -> jam_selesai }} ({{ $data -> ruang }})@else<span class="text-muted">Belum ada jadwal</span>@endif</td>
				<th>Tahun Akademik</th><th>:</th><td>{{ $data -> ta }}</td>
			</tr>
			<tr>
				<th>Jumlah Mahasiswa</th><th>:</th><td>{{ $anggota -> count() }}</td>
				<th></th><th></th><td></td>
			</tr>
		</table>
	</div>
</div>

<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Detail Jurnal Perkuliahan</h3>
		<div class="box-tools">
			<a href="{{ url('/kelaskuliah/' . $matkul_tapel_id . '/jurnal') }}" class='btn btn-warning btn-xs btn-flat' title='Jurnal'><i class='fa fa-book'></i></a>
		</div>
	</div>
	<div class="box-body">
		<form class="form-horizontal">
			<div class="form-group">
				{!! Form::label('pertemuan_ke', 'Pertemuan Ke', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-8">
					<p class="form-control-static">{{ $jurnal -> pertemuan_ke }}</p>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('tanggal', 'Tanggal', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-8">
					<p class="form-control-static">{{ $jurnal -> tanggal }}, {{ $jurnal -> jam_mulai }} - {{ $jurnal -> jam_selesai }}</p>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('ruang_id', 'Ruang', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-8">
					<p class="form-control-static">{{ $jurnal -> ruang -> nama }}</p>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('jenis_pertemuan', 'Jenis Pertemuan', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-8">
					<p class="form-control-static">{{ config('custom.pilihan.jenisPertemuan')[$jurnal -> jenis_pertemuan] }}</p>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('rencana_materi', 'Rencana Materi', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-8">
					<p class="form-control-static">{{ $jurnal -> rencana_materi }}</p>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('materi_kegiatan', 'Materi/Kegiatan', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-8">
					<p class="form-control-static">{{ $jurnal -> materi_kegiatan}}</p>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('catatan_dosen', 'Catatan Dosen', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-8">
					<p class="form-control-static">{{ $jurnal -> catatan_dosen }}</p>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('alasan_ganti', 'Alasan Ganti', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-8">
					<p class="form-control-static">{{ $jurnal -> alasan_ganti }}</p>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('kesesuaian_sap', 'Kesesuaian SAP (rencana dan materi)', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-8">
					<p class="form-control-static">@if($jurnal -> kesesuaian_sap == 'y')<span>Sesuai</span>@else<span>Tidak sesuai</span>@endif</p>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('file', 'File Materi', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-4">
					<p class="form-control-static">@if(isset($jurnal -> file))<a href="{{ url('/download/' . $jurnal -> file . '/' . csrf_token()) }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-download"></i> Download</a>@endif</p>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('status', 'Status', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-4">
					<p class="form-control-static">{{ config('custom.pilihan.statusJurnal')[$jurnal -> status] }}</p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-10">
					<a href="" class="btn btn-info btn-flat"><i class="fa fa-print"></i> Cetak</a>
				</div>		
			</div>	
		</form>
	</div>
</div>
@endsection				