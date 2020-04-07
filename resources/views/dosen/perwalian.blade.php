@extends('app')

@section('title')
Dosen Wali
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Perwalian</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Perwalian</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Data Mahasiswa Perwalian</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No.</th>
					<th>NIM</th>
					<th>Nama</th>
					<th>L/P</th>
					<th>PRODI</th>
					<th>Program</th>
					<th>Sem.</th>
					<th>SKS</th>
					<th>Status</th>
					<th>Link</th>
				</tr>
			</thead>
			<tbody>
				@if(!$mahasiswa -> count())
				<tr><td colspan="12">Data Mahasiswa tidak ditemukan</td></tr>
				@else
				<?php 
					// $per_page = $mahasiswa -> perPage();
					// $total = $mahasiswa -> total();
					// $c = ($mahasiswa -> currentPage() - 1) * $per_page;
					// $last = $c + $per_page > $total ? $total : $c + $per_page;
					$c = 0;
					$config = config('custom.pilihan.statusMhs');
					$non_aktif = [];
				?>
				@foreach($mahasiswa as $mhs)
				<?php 
					if($mhs -> statusMhs != 1) 
					$non_aktif[] = $mhs;
					else
					{
						$c++; 
					?>
					<tr>
						<td>{{ $c }}</td>
						<td>{{ $mhs -> NIM }}</td>
						<td>{{ $mhs -> nama }}</td>
						<td>{{ $mhs -> jenisKelamin }}</td>
						<td>{{ $mhs -> strata }} {{ $mhs -> prodi }}</td>
						<td>{{ $mhs -> kelas }}</td>
						<td>{{ $mhs -> semesterMhs }}</td>
						<td>{{ $mhs -> sks }}</td>
						<td>{{ $config[$mhs -> statusMhs] }}</td>
						<td>
							<div class="btn-group">
								<a class="btn btn-warning btn-xs btn-flat" href='{{ url("/mahasiswa/". $mhs -> NIM ."/krs") }}' title='Tampilkan KRS'><i class="fa fa-puzzle-piece"></i></a>
								<a class="btn btn-danger btn-xs btn-flat" href='{{ url("/mahasiswa/". $mhs -> NIM ."/krs/histori") }}' title='Tampilkan Riwayat KRS'><i class="fa fa-inbox"></i></a>
								<a class="btn btn-info btn-xs btn-flat" href='{{ url("/mahasiswa/". $mhs -> NIM ."/khs") }}' title='Tampilkan KHS'><i class="fa fa-list-alt"></i></a>
								<a class="btn btn-success btn-xs btn-flat" href='{{ url("/mahasiswa/". $mhs -> id ."/transkrip") }}' title='Transkrip'><i class="fa fa-file-text-o"></i></a>
								<a class="btn btn-primary btn-xs btn-flat" href='{{ url("/mahasiswa/". $mhs -> id ."/kemajuan") }}' title='Kemajuan Belajar'><i class="fa fa-line-chart"></i></a>
								<a class="btn btn-xs btn-flat" style="background: rgb(224, 220, 0); color: #000;" href="{{ route('mahasiswa.tagihan', $mhs -> id) }}" title="Status Pembayaran" ><i class="fa fa-star"></i></a>
								<a class="btn bg-maroon btn-xs btn-flat" href="{{ route('mahasiswa.pembayaran', $mhs -> id) }}" title="Riwayat Pembayaran"><i class="fa fa-money"></i></a>
							</div>
						</td>
					</tr>
				<?php } ?>
				@endforeach
				
				@if(count($non_aktif) > 1)
				@foreach($non_aktif as $mhs)
				<?php $c++; ?>
				<tr class="danger">
					<td>{{ $c }}</td>
					<td>{{ $mhs -> NIM }}</td>
					<td>{{ $mhs -> nama }}</td>
					<td>{{ $mhs -> jenisKelamin }}</td>
					<td>{{ $mhs -> strata }} {{ $mhs -> prodi }}</td>
					<td>{{ $mhs -> kelas }}</td>
					<td>{{ $mhs -> semesterMhs }}</td>
					<td>{{ $mhs -> sks }}</td>
					<td>{{ $config[$mhs -> statusMhs] }}</td>
					<td>
						<div class="btn-group">
							<a class="btn btn-warning btn-xs btn-flat" href='{{ url("/mahasiswa/". $mhs -> NIM ."/krs") }}' title='Tampilkan KRS'><i class="fa fa-puzzle-piece"></i></a>
							<a class="btn btn-danger btn-xs btn-flat" href='{{ url("/mahasiswa/". $mhs -> NIM ."/krs/histori") }}' title='Tampilkan Riwayat KRS'><i class="fa fa-inbox"></i></a>
							<a class="btn btn-info btn-xs btn-flat" href='{{ url("/mahasiswa/". $mhs -> NIM ."/khs") }}' title='Tampilkan KHS'><i class="fa fa-list-alt"></i></a>
							<a class="btn btn-success btn-xs btn-flat" href='{{ url("/mahasiswa/". $mhs -> id ."/transkrip") }}' title='Transkrip'><i class="fa fa-file-text-o"></i></a>
							<a class="btn btn-primary btn-xs btn-flat" href='{{ url("/mahasiswa/". $mhs -> id ."/kemajuan") }}' title='Kemajuan Belajar'><i class="fa fa-line-chart"></i></a>
						</div>
					</td>
				</tr>
				@endforeach
				@endif
				
				@endif
			</tbody>
		</table>
	</div>
@endsection																										