@extends('app')

@section('title')
Jurnal Perkuliahan
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Jurnal Perkuliahan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kelaskuliah') }}"> Mengajar Kelas</a></li>
		<li class="active">Jurnal Perkuliahan</li>
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
			<a href="{{ url('/matkul/tapel/' . $matkul_tapel_id . '/nilai') }}" class='btn btn-success btn-xs btn-flat' title='Nilai'><i class='fa fa-bar-chart'></i></a>
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

<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Jurnal Perkuliahan</h3>
		<div class="box-tools">	
			<a href="{{ route('matkul.tapel.jurnal.print', [$matkul_tapel_id]) }}" class="btn btn-primary btn-xs btn-flat" title="Cetak Jurnal"><i class="fa fa-print"></i></a>
			<a href="{{ route('matkul.tapel.jurnal.create', $matkul_tapel_id) }}" class="btn btn-info btn-xs btn-flat" title="Tambah jurnal"><i class="fa fa-plus"></i></a>
		</div>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="10px">No</th>
					<th>Tanggal/Jam</th>
					<th>Ruang</th>
					<th>Jenis</th>
					<th>Rencana Materi</th>
					<th>Materi/Kegiatan</th>
					<th>Kesan Dosen</th>
					<th>Peserta</th>
					<th>Status</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$jurnals -> count())
				<tr><td colspan="10" align="center">Belum ada data</td></tr>
				@else
				<?php $c = 1;?>
				@foreach($jurnals as $jurnal)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $jurnal -> tanggal }}, {{ $jurnal -> jam_mulai }} - {{ $jurnal -> jam_selesai }}</td>
					<td>{{ $jurnal -> ruang -> nama }}</td>
					<td>{{ config('custom.pilihan.jenisPertemuan')[$jurnal -> jenis_pertemuan] }}</td>
					<td>{{ $jurnal -> rencana_materi }}</td>
					<td>{{ $jurnal -> materi_kegiatan }}</td>
					<td>{{ $jurnal -> catatan_dosen }}</td>
					<td>{{ $jurnal -> peserta }}</td>
					<td>{{ config('custom.pilihan.statusJurnal')[$jurnal -> status] }}</td>
					<td>
						<div class="btn-group">
							<a href="{{ route('matkul.tapel.jurnal.show', [$matkul_tapel_id, $jurnal -> id]) }}" class="btn btn-info btn-xs btn-flat" title="Detail Jurnal"><i class="fa fa-search"></i></a>
							<a href="{{ route('matkul.tapel.jurnal.edit', [$matkul_tapel_id, $jurnal -> id]) }}" class="btn btn-warning btn-xs btn-flat" title="Edit Jurnal"><i class="fa fa-edit"></i></a>
							<a href="{{ route('matkul.tapel.jurnal.delete', [$matkul_tapel_id, $jurnal -> id]) }}" class="btn btn-danger btn-xs btn-flat has-confirmation" title="Hapus Jurnal"><i class="fa fa-trash"></i></a>
						</div>
					</td>
				</tr>
				<?php $c++;?>
				@endforeach
			@endif
			</tbody>
			</table>
			</div>
			</div>
			@endsection																																					