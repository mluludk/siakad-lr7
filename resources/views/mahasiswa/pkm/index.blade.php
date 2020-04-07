@extends('app')

@section('title')
PKM
@endsection

@section('header')
<section class="content-header">
	<h1>
		PKM
		<small>Data PKM</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Data PKM</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Data PKM</h3>
		<div class="box-tools">
			<a href="{{ route('mahasiswa.pkm.create') }}" class="btn btn-primary btn-xs btn-flat" title="Tambah Data PKM"><i class="fa fa-plus"></i> Tambah Data PKM</a>
		</div>
	</div>
	<div class="box-body">
		<?php
			$c=1;
			$now = time();
		?>
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No</th>
					<th>Tahun Akademik</th>
					<th>Tanggal</th>
					<th>Lokasi</th>
					<th>Prodi</th>
					<th>Mata Kuliah</th>
					<th>SK</th>
					<th>Tanggal SK</th>
					<th>Pendaftaran</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$pkm -> count())
				<tr>
					<td colspan="10" align="center">Belum ada data</td>
				</tr>
				@else
				@foreach($pkm as $g)
				<?php 
					$lokasi = formatLokasiPkm($g -> lokasi, $user);
					// $j_lokasi = count($lokasi);
					
					$j_mk = 0;
					$c_lokasi = 0;
					if(count($lokasi) > 0)
					{
						foreach($lokasi as $l)
						{
							$count_matkul = isset($l['matkul']) ? count($l['matkul']) : 1;
							
							$j_mk += $count_matkul;
							$j_mk_lokasi[$c_lokasi] = $count_matkul;							
							
							$c_lokasi++;
						}
					}
					else
					{
						$j_mk_lokasi[0] = 1;
						$c_lokasi = 1;						
					}
					$cspan = $j_mk > 1 ? 'rowspan="' . $j_mk . '"' : '';
					
				?>
				<tr>
					<td {!! $cspan !!}>{{ $c }}</td>
					<td class='rotate' {!! $cspan !!}><span>{{ $g -> tapel }}</span></td>
					<td class='rotate' {!! $cspan !!}>
						<span>
							{{ formatTanggal(date('Y-m-d', strtotime($g -> tanggal_mulai))) }} 
							- 
							{{ formatTanggal(date('Y-m-d', strtotime($g -> tanggal_selesai))) }}
						</span>
					</td>
					<td rowspan="{{ $j_mk_lokasi[0] }}">
						{!! $lokasi[0]['lokasi'] ?? '' !!}
						
						@if($c_lokasi == 1)
						@if(in_array($user -> role_id, [1,2,8,257]))
						<a href="{{ route('mahasiswa.pkm.lokasi.create', $g -> id) }}" class="btn btn-primary btn-flat btn-xs" title="Tambah Lokasi PKM">
							<i class="fa fa-map-marker"></i> Tambah Lokasi
						</a>
						@endif
						@else
						<?php $c_lokasi--; ?>
						@endif
						
					</td>
					
					<td>{{ $lokasi[0]['matkul'][0]['prodi']  ?? '' }}</td>
					<td>
						{{ $lokasi[0]['matkul'][0]['nama']  ?? '' }}
						@if(isset($lokasi[0]['matkul'][0]['nama']))
						@if(in_array($user -> role_id, [1,2,8,257]))
						<a href="{{ route('mahasiswa.pkm.lokasi.matkul.delete', $lokasi[0]['matkul'][0]['id']) }}" title="Hapus Mata Kuliah" class="btn btn-danger btn-xs btn-flat has-confirmation"><i class="fa fa-trash"></i></a>
						@endif
						@endif
					</td>
					<?php $j_mk--; ?>
					
					<td class='rotate' {!! $cspan !!}><span>{{ $g -> sk ?? '' }}</span></td>
					<td class='rotate' {!! $cspan !!}><span>{{ formatTanggal(date('Y-m-d', strtotime($g -> tanggal_sk))) }}</span></td>
					<td {!! $cspan !!}>
						@if($now >= strtotime($g -> tgl_mulai_daftar . ' 00:00:00') and $now <= strtotime($g -> tgl_selesai_daftar . ' 23:59:59'))
							<span class="label label-success label-flat">Buka</span>
							@else
							<span class="label label-danger label-flat">Tutup</span>
							@endif
						</td>
						<td {!! $cspan !!}>
							<a href="{{ route('mahasiswa.pkm.lokasi.peserta.index', $g -> id) }}" class="btn btn-info btn-flat btn-xs" title="Peserta PKM"><i class="fa fa-share-alt"></i> Peserta</a>
							@if(in_array($user -> role_id, [1,2,8,257]))
							<a href="{{ route('mahasiswa.pkm.edit', $g -> id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit Data PKM"><i class="fa fa-pencil-square-o"></i> Edit</a>
							<a href="{{ route('mahasiswa.pkm.delete', $g -> id) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Hapus PKM"><i class="fa fa-trash"></i> Hapus</a>
							@endif
						</td>
					</tr>
					
					@if($j_mk > 0)
					<?php 
						$i_mk = 0;
						$c_mk = 1;
					?>
					@for($i_baris=1; $i_baris <= $j_mk; $i_baris++)
					<tr>
						@if($c_mk >= $j_mk_lokasi[$i_mk])
						<?php $i_mk++;	?>
						
						<td rowspan="{{ $j_mk_lokasi[$i_mk] }}">
							{!! $lokasi[$c_mk]['lokasi'] ?? '' !!}
							
							@if($c_lokasi == 1)
							@if(in_array($user -> role_id, [1,2,8,257]))
							<a href="{{ route('mahasiswa.pkm.lokasi.create', $g -> id) }}" class="btn btn-primary btn-flat btn-xs" title="Tambah Lokasi PKM">
								<i class="fa fa-map-marker"></i> Tambah Lokasi
							</a>
							@endif
							@else
							<?php $c_lokasi--; ?>
							@endif
							
						</td>
						<?php $c_mk = 0;?>
						@endif
						
						<td>{{ $lokasi[$i_mk]['matkul'][$c_mk]['prodi'] ?? '' }}</td>
						<td>
							@if(isset($lokasi[$i_mk]['matkul']))
							{{ $lokasi[$i_mk]['matkul'][$c_mk]['nama'] ?? '' }}
							@if(in_array($user -> role_id, [1,2,8,257]))
							<a href="{{ route('mahasiswa.pkm.lokasi.matkul.delete', $lokasi[$i_mk]['matkul'][$c_mk]['id']) }}" title="Hapus Mata Kuliah" class="btn btn-danger btn-xs btn-flat has-confirmation"><i class="fa fa-trash"></i></a>
							@endif
							@endif
						</td>
						<?php $c_mk++; ?>						
					</tr>
					
					@endfor
					@endif
					
					<?php $c++; ?>
					@endforeach
					@endif
				</tbody>
			</table>
		</div>
		</div>
		@endsection
		
		@push('styles')
		<style type="text/css">
		td.rotate
		{
		vertical-align: middle !important;
		text-align: center !important;
		}
		
		td.rotate span
		{
		text-align: center !important;
		-ms-writing-mode: tb-rl;
		-webkit-writing-mode: vertical-rl;
		writing-mode: vertical-rl;
		transform: rotate(180deg);
		white-space: nowrap;
		}
		</style>
		@endpush																													