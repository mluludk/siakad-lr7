@extends('app')

@section('title')
Tugas Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Tugas Mahasiswa
		<small>Daftar</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Tugas Mahasiswa</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Tugas Mahasiswa</h3>
		<div class="box-tools">
			<a href="{{ route('mahasiswa.tugas.create') }}" class="btn btn-primary btn-xs btn-flat" title="Tambah Tugas Mahasiswa"><i class="fa fa-plus"></i> Tambah Tugas Mahasiswa</a>
		</div>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="30px">No</th>
					<th>Judul</th>
					<th>Mata Kuliah</th>
					<th>Prodi</th>
					<th>Program</th>
					<th>Kelas</th>
					<th>Dosen</th>
					<th>Jenis Tugas</th>
					<th>Penilaian</th>
					<th colspan="3" align="center">Kuota</th>
					<th width="30%">Deskripsi</th>
					<th>Publikasi</th>
					<th width="9.5%"></th>
				</tr>
			</thead>
			<tbody>
				<?php $c=0; ?>
				@if(!$tugas->count())
				<tr>
					<td colspan="11" align="center">Belum ada data</td>
				</tr>
				@else
				<?php $c=1; ?>
				@foreach($tugas as $g)
				<?php
					$allowed = false;
					if($user -> role_id > 2)
					{
						foreach($g -> perkuliahan -> tim_dosen as $t)
						{
							if($t -> id == $user -> authable_id) $allowed = true;
						}
					}
					else
					{
						$allowed = true;	
					}
				?>
				@if($allowed)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> judul }}</td>
					<td>{{ $g -> matkul }} ({{ $g -> kode }})</td>
					<td>{{ $g -> strata }} {{ $g -> singkatan }}</td>
					<td>{{ $g -> program }}</td>
					<td>{{ $g -> semester }}{{ $g -> kelas2 }}</td>
					<td>{!! formatTimDosen($g -> perkuliahan -> tim_dosen) !!}</td>
					<td>
						@if($g -> jenis_tugas == 1) <i class="fa fa-upload"></i>
						@elseif($g -> jenis_tugas == 2) <i class="fa fa-file-text-o"></i>
						@elseif($g -> jenis_tugas == 3) <i class="fa fa-check-square"></i>
						@endif
						{{ $jenis[$g -> jenis_tugas] }}
					</td>
					<td>
						@if($g -> jnilai == '__FINAL__') Akhir @else {{ $g -> jnilai }} @endif 
						({{ $g -> bobot }}%)
					</td>
					<td><span data-toggle="popover" data-content="{{ $g -> jml_mhs }} Mahasiswa <strong>terdaftar</strong> di Kelas Kuliah ini">{{ $g -> jml_mhs }}</span></td>
					<td><span data-toggle="popover" data-content="{{ $g -> jml_kirim }} Mahasiswa sudah <strong>mengirimkan</strong> Tugas">{{ $g -> jml_kirim }}</span></td>
					<td><span data-toggle="popover" data-content="{{ $g -> jml_nilai }} Tugas sudah diberi <strong>Nilai</strong>">{{ $g -> jml_nilai }}</span></td>
					<td>{!! $g -> keterangan !!}</td>
					<td>
						@if($g -> published == 'y') <span class="label label-success label-flat"><i class="fa fa-unlock"></i> Public</span>
						@else <span class="label label-danger label-flat"><i class="fa fa-lock"></i> Private</span>
						@endif
					</td>
					<td width="125px">
						@if($g -> published == 'n')
						<div class="btn-group" role="group" aria-label="Group button">
							<a href="{{ route('mahasiswa.tugas.detail.index', $g -> id) }}" class="btn btn-info btn-flat btn-xs" title="Detail Tugas"><i class="fa fa-search"></i></a>
							<a href="{{ route('mahasiswa.tugas.hasil.index', $g -> id) }}" class="btn btn-success btn-flat btn-xs" title="Hasil Tugas"><i class="fa fa-inbox"></i></a>
							<a href="{{ route('mahasiswa.tugas.edit', $g->id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data Tugas"><i class="fa fa-pencil-square-o"></i></a>
							<a href="{{ route('mahasiswa.tugas.delete', $g->id) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Hapus Tugas"><i class="fa fa-trash"></i></a>
						</div>
						@else
						<a href="{{ route('mahasiswa.tugas.detail.index', $g -> id) }}" class="btn btn-info btn-flat btn-xs" title="Detail Tugas"><i class="fa fa-search"></i> Detail</a>
						<a href="{{ route('mahasiswa.tugas.hasil.index', $g -> id) }}" class="btn btn-success btn-flat btn-xs" title="Hasil Tugas"><i class="fa fa-inbox"></i> Hasil</a>
						@endif
					</td>
				</tr>
				<?php $c++; ?>
				@endif
				@endforeach
				@if($c == 0)
				<tr>
					<td colspan="11" align="center">Belum ada data</td>
				</tr>
				@endif
				@endif
			</tbody>
		</table>
	</div>
</div>
@endsection					

@push('scripts')
<script>
	$(function () {
		$('[data-toggle="popover"]').popover({
			html: true,
			placement: 'auto top',
			trigger: 'hover'
		})
	})
</script>
@endpush