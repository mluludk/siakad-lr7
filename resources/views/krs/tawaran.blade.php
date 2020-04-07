@extends('app')

@section('title')
Tawaran Mata Kuliah
@endsection

@push('styles')
<style>
	.checkbox{
	margin: 0px !important;
	padding-top: 0px !important;
	}
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Tawaran Mata Kuliah</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Tawaran Mata Kuliah</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Tawaran Mata Kuliah</h3>
	</div>
	<div class="box-body">
		@if(!$admin)
		<div>
			<h3>CARA MELAKUKAN KRS ONLINE</h3>
			<ol style="padding-left: 18px;">
				<li>KLIK 
					<span class="label bg-blue"><i class="fa fa-check"></i> MAPEL YANG MAU DIAMBIL</span> 
					<span class="label bg-red">YANG SESUAI PROGRAM KULIAH ANDA</span> 
					<span class="label bg-blue">PADA SAAT INI,</span> 
					<span class="label bg-yellow"> SELAMA KAPASITAS KELAS MASIH MENCUKUPI</span>
				</li>
				<li>JIKA 
					<span class="label bg-red">STATUS PENUH</span> 
					<span class="label bg-yellow">MAKA HARUS PILIH MAPEL DI KELAS PROGRAM LAIN</span> 
					<span class="label bg-blue">DAN OTOMATIS ANDA PINDAH KELAS DAN MASUK KELAS TERSEBUT</span>
				</li>
				<li>MAHASISWA 
					<span class="label bg-yellow">HANYA BISA</span> 
					<span class="label bg-red">MEMILIH SATU MAPEL YANG SAMA DI KELAS,</span> 
					<span class="label bg-yellow">JIKA INGIN GANTI MAPEL HAPUS MAPEL YG SDH DIAMBIL</span>
				</li>
				<li>KLIK 
					<span class="label bg-blue"><i class="fa fa-plus"></i> TAMBAHKAN</span>
				</li>
			</div>
			
			<div>
				<h3>CARA MEMBATALKAN ATAU HAPUS KRS YANG SALAH</h3>
				<ol style="padding-left: 18px;">
					<li>Klik <span class="label bg-blue">Menu KRS Online--> pilih menu ke 2 KRS---> click mapel yang mau di hapus---> click HAPUS  </span></li>
				</ol>
			</div>
			@endif
			
			@if(!count($matkul) && !count($matkul2))
			@if(!$open)
			<div class="callout callout-danger">
				<h4>Error</h4>
				Waktu pengisian KRS Online sudah habis. Hubungi bagian Akademik jika anda belum melakukan KRS Online.
			</div>
			@else
			<p class="text-muted">Tidak ada Mata Kuliah yang ditawarkan pada semester ini</p>
			@endif
			@else
			<?php $c=1; ?>
			{!! Form::model(new Siakad\Krs, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['krs.store']]) !!}
			<table class="table table-bordered table-striped">
				<thead>
					<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
						<th>No</th>
						<th>MATA KULIAH YANG DI TAWARKAN</th>
						<th>PRODI DAN PROGRAM KELAS</th>
						<th>KELAS</th>
						<th>DOSEN PENGAMPU</th>
						<th>JADWAL</th>
						<th>SEMESTER</th>
						<th>SKS</th>
						<th>KOUTA KELAS</th>
						<th>JUMLAH PESERTA</th>
					</tr>
				</thead>
				<tbody>
					@foreach($matkul as $g)
					<?php
						$full = false;
						if(intval($g -> kuota) === 0 )
						{
							$full = true;
						}
						elseif(intval($g -> peserta) / intval($g -> kuota) === 1) 
						{
							$full = true;
						}
					?>
					<tr>
						<td>{{ $c }}</td>
						<td>
							@if($full ?? !$open)
							{{ $g -> nama_matkul }} ({{ $g -> kode }})
							@else
							<div class="checkbox"><label>{!! Form::checkbox('matkul_tapel_id[]', $g -> mtid, false, ['class' => 'cb ' . $g -> mid, 'id' => $g -> mtid]) !!} {{ $g -> nama_matkul }} ({{ $g -> kode }})</label></div>
							@endif
						</td>
						<td>{{ $g -> nama_prodi }} {{ $g -> program }}</td>
						<td>{{ $mhs -> semesterMhs }} {{ $g -> kelas2 }}</td>
						<td>{{ formatTimDosen($g -> tim_dosen) }}</td>
						<td>{!! formatJadwal($g -> jadwal) !!}</td>
						<td>{{ $g -> semester }}</td>
						<td>{{ $g -> sks }}</td>
						<td>@if($full)<span class="label bg-red">KELAS PENUH</span>@else{{ $g -> kuota }}@endif</td>
						<td>@if($full)<span class="label bg-red">KELAS PENUH</span>@else{{ $g -> peserta }}@endif</td>
					</tr>
					<?php $c++; ?>
					@endforeach
					
					@foreach($matkul2 as $g)
					<?php
						$full = false;
						if(intval($g -> kuota) === 0 )
						{
							$full = true;
						}
						elseif(intval($g -> peserta) / intval($g -> kuota) === 1) 
						{
							$full = true;
						}
					?>
					<tr>
						<td>{{ $c }}</td>
						<td>
							@if($full ?? !$open)
							{{ $g -> nama_matkul }} ({{ $g -> kode }})
							@else
							<div class="checkbox"><label>{!! Form::checkbox('matkul_tapel_id[]', $g -> mtid, false, ['class' => 'cb ' . $g -> mid, 'id' => $g -> mtid]) !!} {{ $g -> nama_matkul }} ({{ $g -> kode }})</label></div>
							@endif
						</td>
						<td>{{ $g -> nama_prodi }} {{ $g -> program }}</td>
						<td>{{ $mhs -> semesterMhs }} {{ $g -> kelas2 }}</td>
						<td>{{ formatTimDosen($g -> tim_dosen) }}</td>
						<td>{!! formatJadwal($g -> jadwal) !!}</td>
						<td>{{ $g -> semester }}</td>
						<td>{{ $g -> sks }}</td>
						<td>@if($full)<span class="label bg-red">KELAS PENUH</span>@else{{ $g -> kuota }}@endif</td>
						<td>@if($full)<span class="label bg-red">KELAS PENUH</span>@else{{ $g -> peserta }}@endif</td>
					</tr>
					<?php $c++; ?>
					@endforeach
				</tbody>
			</table>
			@if($open)
			{!! Form::hidden('mahasiswa_id', $mhs -> id) !!}
			{!! Form::hidden('mahasiswa_nim', $mhs -> NIM) !!}
			{!! Form::hidden('krs_id', $krs -> id) !!}
			<div class="form-group">
				<div class="col-sm-10">
					<button class="btn btn-primary btn-flat" type="submit"><i class="fa fa-plus"></i> TAMBAHKAN</button>
				</div>		
			</div>
			@endif
			{!! Form::close() !!}
			@endif
		</div>
	</div>
@endsection																																																																																								