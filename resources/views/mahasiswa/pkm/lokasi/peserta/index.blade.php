@extends('app')

@section('title')
Peserta PKM {{ $pkm -> tapel -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		PKM {{ $pkm -> tapel -> nama }}
		<small>Daftar Peserta</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pkm') }}"> Data PKM</a></li>
		<li class="active">Peserta PKM {{ $pkm -> tapel -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Peserta PKM {{ $pkm -> tapel -> nama }}</h3>
		<div class="box-tools">
			<a href="{{ route('mahasiswa.pkm.lokasi.peserta.index', [$pkm -> id, 'print']) }}" class="btn btn-success btn-xs btn-flat" title="Cetak Data PKM"><i class="fa fa-print"></i> Cetak Data Peserta</a>
		</div>
	</div>
	<div class="box-body">
		<?php $c=1; ?>
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="30px">NO</th>
					<th>LOKASI</th>
					<th>DOSEN PENDAMPING</th>
					<th>PRODI</th>
					<th>MATA KULIAH</th>
					<th>NIM</th>
					<th>NAMA</th>
					<th colspan="2">NILAI</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(count($peserta) < 1)
				<tr>
					<td colspan="8" align="center">Belum ada data</td>
				</tr>
				@else
				<?php
					$l1 = false;
				?>
				@foreach($peserta as $lokasi)
				<?php
					if(!isset($rs1))
					{
						$rs1=0;
						foreach($lokasi as $matkul) 
						{
							$rs1 += count($matkul);
						}
					}
					$span1 = $rs1 > 1 ? ' rowspan="' . $rs1 . '"' : '';
				?>
				@foreach($lokasi as $matkul)
				<?php
					$l2 = false;
					$rs2 = count($matkul);
					$span2 = $rs2 > 1 ? ' rowspan="' . $rs2 . '"' : '';
				?>
				@foreach($matkul as $g)
				@if(in_array($user -> role_id, [1,2,8,257]) || ($user -> role_id == 128 && isPendamping($g -> pkm_lokasi_id, $user -> authable_id, $pendamping)))
				<tr>
					<td>{{ $c }}</td>
					@if(!$l1)
					<td {!! $span1 !!}>{{ $g -> lokasi }}</td>
					<td {!! $span1 !!}>{!! formatPendamping($g -> pkm_lokasi_id, $pendamping) !!}</td>
					@endif
					@if(!$l2)
					<td {!! $span2 !!}>
						{{ $g -> strata }} {{ $g -> singkatan }}
					</td>
					<td {!! $span2 !!}>
						{{ $g -> nama_matkul }} ({{ $g -> kode_matkul }})
						@if($user -> role_id <=2 || ($user -> role_id == 128 && isPendamping($g -> pkm_lokasi_id, $user -> authable_id, $pendamping)))
							<a href="{{ route('mahasiswa.pkm.lokasi.peserta.nilai', [$pkm -> id, $g -> pkm_lokasi_id, $g -> matkul_id]) }}"
							class="btn btn-primary btn-xs btn-flat" title="Penilaian"><i class="fa fa-list"></i> Nilai PKM</a>
							@endif
						</td>
						@endif
						<td>{{ $g -> NIM }}</td>
						<td>{{ $g -> mahasiswa }}</td>
						<td>
							{{ $g -> nilai ?? '' }}
						</td>
						<td>
							{{ $g -> nilai_angka ?? '0' }}
						</td>
					</td>
					<td>
						<a href="{{ route('mahasiswa.pkm.lokasi.peserta.delete', [$pkm -> id, $g -> pkm_lokasi_id, $g -> mahasiswa_id]) }}"
						class="btn btn-danger btn-xs btn-flat has-confirmation" title="Hapus Peserta PKM"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
				@endif
				<?php
					$c++;
					if(!$l2) $l2 = true;
					if(!$l1) $l1 = true;
				?>
				@endforeach
				@endforeach
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
@endsection		