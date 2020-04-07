@extends('app')

@section('title')
Detail Hasil Kuesioner
@endsection

@section('header')
<section class="content-header">
	<h1>
		Kuesioner
		<small>Detail Hasil Kuesioner {{ $matkul_tapel -> tapel -> nama }}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kuesioner/results') }}"> Hasil Kuesioner</a></li>
		<li><a href="{{ url('/kuesioner/result/' . $matkul_tapel -> tapel -> id) }}"> {{ $matkul_tapel -> tapel -> nama }}</a></li>
		<li class="active">Detail</li>
	</ol>
</section>
@endsection

@push('styles')
<style>
	.table-detail{
	font-size: 12px;
	}
	.table-detail th{
	padding: 0px !important;
	text-align: center;
	vertical-align: middle !important;
	}
	.sig{
	text-transform:uppercase; width: 30px
	}
	.poin{
	max-width: 30px;
	text-align: center;
	}
</style>
@endpush

@section('content')
<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Data Dosen</h3>
	</div>
	<div class="box-body">
		<table width="100%">
			<tr>
				<th width="120px">Nama</th><th width="10px">:</th><td>{{ $matkul_tapel -> dosen }}</td>
			</tr>
			<tr>
				<th>Mata Kuliah</th><th>:</th><td>{{ $matkul_tapel -> matkul }}</td>
			</tr>
			<tr>
				<th>PRODI</th><th>:</th><td>{{ $matkul_tapel -> prodi }} ({{ $matkul_tapel -> program }})</td>
			</tr>
			<tr>
				<th>Tahun Akademik</th><th>:</th><td>{{ $matkul_tapel -> ta }}</td>
			</tr>
		</table>
	</div>
</div>

<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Monitoring dan Evaluasi Kinerja Dosen</h3>
	</div>
	<div class="box-body">
		<?php 
			$c = 1; 
			$kompetensi = config('custom.kuesioner.kompetensi');
			$ck = count($kompetensi);
			$t1 = $t2 = $t3 = $col = 0;
			$q = 1;
		?>
		<table class="table table-bordered table-hover table-detail">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,3 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th rowspan="2">No</th>
					<th rowspan="2" width="150px">NIM</th>
					@foreach($kuesioners as $k => $v)
					<th colspan="{{ count($v) }}" class="poin">{{ $kompetensi[$k] }}</th>
					<th rowspan="2" class="sig">&sigma;</th>
					@endforeach
					<th rowspan="2">Jumlah</th>
				</tr>
				<tr>
					@foreach($kuesioners as $k => $v)
					@foreach($v as $x)
					<th>{{ $q }}</th>
					<?php $col ++; $q ++; ?>
					@endforeach
					@endforeach
				</tr>
			</thead>
			<tbody>
				@foreach($mahasiswa as $id => $nim)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $nim }}</td>
					
					@for($n = 1; $n <= $ck; $n++)
					@for($o = 0; $o < count($kuesioners[$n]); $o++)
					<?php
						$skor = isset($results[$id][$n][$kuesioners[$n][$o]]) ? $results[$id][$n][$kuesioners[$n][$o]] : 0;
						$t1 += $skor;
					?>
					<td>{{ $skor }}</td>
					@endfor
					<td>{{ $t1 }}</td>
					<?php $t2 += $t1; $t1 = 0; ?>
					@endfor
					
					<td>{{ $t2 }}</td>
					<?php $t3 += $t2; $t2 = 0; ?>
				</tr>				
				<?php $c++; ?>
				@endforeach
				<tr>
					<td colspan="{{ $col + $ck + 2 }}" align="right"><strong>Total</strong></td>
					<td><strong>{{ $t3 }}</strong></td>
				</tr>
				<tr>
					<td colspan="{{ $col + $ck + 2 }}" align="right"><strong>Rata-rata</strong></td>
					<td><strong>
						@if($t3 > 0){{ round($t3 / ($col * count($mahasiswa)), 2) }}
						@else
						0
						@endif
					</strong></td>
				</tr>
			</tbody>			
		</table>
	</div>
</div>
@endsection																								