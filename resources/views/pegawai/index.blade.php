@extends('app')

@section('title')
Daftar Pegawai Non Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Non Dosen
		<small>Daftar</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Pegawai Non Dosen</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Pegawai Non Dosen</h3>
		<div class="box-tools">
			<a href="{{ route('pegawai.export') }}" class="btn btn-success btn-xs btn-flat" title="Export Data Pegawai Non Dosen"><i class="fa fa-file-excel-o"></i> Export Pegawai Non Dosen</a>
			<a href="{{ route('pegawai.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data Pegawai Non Dosen"><i class="fa fa-plus"></i> Tambah Pegawai Non Dosen</a>
		</div>
	</div>
	<div class="box-body">
		<?php 
			$c=1; 
			$conf = config('custom.pilihan.emis');
		?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No</th>
					<th>NIP</th>
					<th>Nama</th>
					<th>Status Tugas</th>
					<th>Status Keaktifan</th>
					<th>Unit Tugas</th>
					<th>Nama Unit</th>
					<th>Tugas Pokok</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$pegawai->count())
				<tr>
					<td colspan="9" align="center">Belum ada data</td>
				</tr>
				@else
				@foreach($pegawai as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> nip }}</td>
					<td>
					<a href="{{ route('pegawai.show', $g -> id) }}" title="Tampilkan data pegawai">{{ $g -> gelar_depan ?? '' }} {{ $g -> nama }} {{ $g -> gelar_belakang ?? '' }}</a></td>
					<td>{{ $conf['status_tugas'][$g -> status_tugas] }}</td>
					<td>{{ $conf['status_keaktifan'][$g -> status_keaktifan] }}</td>
					<td>{{ $conf['unit_tugas'][$g -> unit_tugas] }}</td>
					<td>{{ $g -> nama_unit_tugas }}</td>
					<td>{{ $conf['tugas_pokok'][$g -> tugas_pokok] }}</td>
					<td>
						<a href="{{ route('pegawai.show', $g -> id) }}" class="btn btn-info btn-flat btn-xs" title="Tampilkan data pegawai"><i class="fa fa-search"></i> </a>
						<a href="{{ route('pegawai.edit', $g -> id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data pegawai"><i class="fa fa-edit"></i> </a>
						<a href="{{ route('pegawai.delete', $g -> id) }}" class="btn btn-danger has-confirmation btn-flat btn-xs" title="Hapus data pegawai"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
@endsection												