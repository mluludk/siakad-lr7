@extends('app')

@section('title')
Pendaftaran Ujian {{ ucfirst($j) }} Skripsi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pendaftaran Ujian {{ ucfirst($j) }} Skripsi
		<small>Jadwal</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Jadwal Pendaftaran Ujian {{ ucfirst($j) }} Skripsi</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Jadwal Pendaftaran Ujian {{ ucfirst($j) }} Skripsi</h3>
		<div class="box-tools">
			<a href="{{ route('jadwal.ujian.skripsi.create', $j) }}" class="btn btn-primary btn-xs btn-flat" title="Buat Jadwal Ujian {{ $j }} Skripsi"><i class="fa fa-plus"></i> Tambah Jadwal Baru</a>
		</div>
	</div>
	<div class="box-body">
		<?php $c=1; ?>
		@if(!$ujian -> count())
		<p>Belum ada data</p>
		@else
		@foreach($ujian as $g)
		<table class="table table-bordered table-striped">
			<tbody>
				<tr>
					<th width="200px">No. Surat</th>
					<td colspan="3"width="500px">{{ $g -> no_surat }}</td>
					<td>
						<a href="{{ route('jadwal.ujian.skripsi.gelombang.create', [$j, $g -> id]) }}" class="btn btn-success btn-flat btn-xs" title="Tambah Gelombang Ujian {{ $j }} Skripsi"><i class="fa fa-plus"></i> Gelombang</a>
						<a href="{{ route('jadwal.ujian.skripsi.edit', [$j, $g -> id]) }}" class="btn btn-warning btn-flat btn-xs" title="Edit Jadwal Ujian {{ $j }} Skripsi"><i class="fa fa-pencil-square-o"></i> Edit</a>
						<a href="{{ route('jadwal.ujian.skripsi.delete', [$j, $g -> id]) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Hapus Jadwal Ujian {{ $j }} Skripsi"><i class="fa fa-trash"></i> Hapus</a>
					</td>
				</tr>
				<tr>
					<th>Prodi</th>
					<td class="btn btn-success btn-flat btn-xs" colspan="4">{{ $g -> prodi -> strata }} {{ $g -> prodi -> nama }}</td>
				</tr>
				<tr>
					<th>Tahun Akademik</th>
					<td colspan="4">{{ $g -> tapel -> nama }}</td>
				</tr>
				<tr>
					<th>Nama</th>
					<td colspan="4">{{ $g -> nama }}</td>
				</tr>
				@if($g -> gelombang -> count())
				<?php
					$jgel = $g -> gelombang -> count();
					$cg = 0;
				?>
				<tr>
					<th valign="top" rowspan="{{ $jgel }}">Tanggal Pengajuan</th>
					@foreach($g -> gelombang as $gel)
					
					<?php
						$now = time();
						$status = '<span class="label label-danger">TUTUP</span>';
						if(strtotime($gel -> tgl_mulai) <= $now && strtotime($gel -> tgl_selesai . ' 23:59:59') >= $now) $status = '<span class="label label-success">BUKA</span>';						
					?>
					
					@if($jgel > 1 and $cg > 0) <tr> @endif
						<td width="300px">{{ $gel -> nama }}</td>					
						<td>Tgl {{ $gel -> tgl_mulai }} - {{ $gel -> tgl_selesai }}</td>	
						<td>{!! $status !!}</td>
						<td>
							<a href="{{ route('jadwal.ujian.skripsi.gelombang.peserta.index', [$j, $gel -> id]) }}" 
							class="btn btn-info btn-flat btn-xs" 
							title="Peserta {{ $gel -> nama }}"><i class="fa fa-share-alt"></i> {{ $gel -> peserta -> count() }} Peserta</a>
							<a href="{{ route('jadwal.ujian.skripsi.gelombang.edit', [$j, $gel -> id]) }}" class="btn btn-warning btn-flat btn-xs" title="Edit {{ $gel -> nama }}"><i class="fa fa-pencil-square-o"></i></a>
							<a href="{{ route('jadwal.ujian.skripsi.gelombang.delete', [$j, $gel -> id]) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Hapus {{ $gel -> nama }}"><i class="fa fa-trash"></i></a>
						</td>
					@if($jgel > 1 and $cg > 0) </tr> @endif
					<?php $cg++; ?>
					@endforeach
				@if($jgel == 1) </tr> @endif
				@else
				<tr>
					<td colspan="5" align="center">Belum ada Gelombang yang dibuka.</td>
				</tr>
				@endif
				<?php $c++; ?>
			</tbody>
		</table>
		<br/>
		@endforeach
		@endif
	</div>
</div>
@endsection																																															