@extends('app')

@section('title')
Jadwal Ujian {{ $jenis }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Jadwal Ujian {{ $jenis }}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Jadwal Ujian {{ $jenis }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Jadwal Ujian {{ $jenis }}</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th width="150px">Nama</th>
					<td>{{ $jadwal_ujian -> nama_ujian }}</td>
				</tr>
				<tr>
					<th>Gelombang</th>
					<td>{{ $jadwal_ujian -> nama_gelombang }}</td>
				</tr>
				<tr>
					<th>Ruang</th>
					<td>{{ $jadwal_ujian -> ruang }}</td>
				</tr>
				<tr>
					<th>Tanggal</th>
					<td>{{ $jadwal_ujian -> tanggal }}</td>
				</tr>
				<tr>
					<th>Waktu</th>
					<td>{{ $jadwal_ujian -> jam_mulai }} - {{ $jadwal_ujian -> jam_selesai }}</td>
				</tr>
				<tr>
					<th>Penguji Utama</th>
					<td>{{ $jadwal_ujian -> p_gd }} {{ $jadwal_ujian -> p_nama }} {{ $jadwal_ujian -> p_gb }}</td>
				</tr>
				<tr>
					<th>Ketua</th>
					<td>{{ $jadwal_ujian -> k_gd }} {{ $jadwal_ujian -> k_nama }} {{ $jadwal_ujian -> k_gb }}</td>
				</tr>
				<tr>
					<th>Sekretaris</th>
					<td>{{ $jadwal_ujian -> s_gd }} {{ $jadwal_ujian -> s_nama }} {{ $jadwal_ujian -> s_gb }}</td>
				</tr>
			</tbody>
		</table>
		<br/>
		<div style="text-align: center">
			<a href="{{ route('skripsi.ujian.pendaftaran.print', [$mahasiswa -> skripsi_id, $jenis]) }}" target="_blank" class="btn btn-success btn-lg btn-flat"><i class="fa fa-print"></i> Cetak Formulir</a>
		</div>
	</div>
</div>
@endsection																																																																																																