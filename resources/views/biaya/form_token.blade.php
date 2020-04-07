@extends('app')

@section('title')
Form Pembayaran
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Form Pembayaran dengan Kode Bayar</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Form Pembayaran dengan Kode Bayar</li>
	</ol>
</section>
@endsection

@section('content')
<div class="row">
	<div class=@if($tagihan !== null) "col-md-6" @else "col-sm-12" @endif>
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">Kode Pembayaran</h3>
			</div>
			<div class="box-body">
				<form role="form">
					<div class="form-group has-feedback{{ ($tagihan == null and $kode != '') ? ' has-error' : '' }}">
						<label for="kode">Kode Bayar / NIM Mahasiswa</label>
						<div class="input-group">
							<input type="text" class="form-control" id="kode" name="kode" value="{{ $kode ?? '' }}" placeholder="Masukkan Kode Pembayaran / NIM Mahasiswa" autofocus="autofocus">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search"></i> Cari</button>
							</span>
						</div>
						@if ($tagihan == null and $kode != '')
						<span class="help-block">
							<strong>Kode tidak ditemukan / sudah kadaluarsa</strong>
						</span>
						@endif
					</div>
					@if($tagihan !== null)
					<div class="form-group">
						<label for="nama">Nama</label>
						<p class="form-control-static">@if($tagihan !== null) {{ $tagihan -> nama_mahasiswa }} ({{ $tagihan -> NIM }})@endif</p>
					</div>
					<div class="form-group">
						<label for="prodi">PRODI / Program / Semester / Jenis Pembiayaan</label>
						<p class="form-control-static">
							@if($tagihan !== null) 
							{{ $tagihan -> strata }} {{ $tagihan -> nama_prodi }} / {{ $tagihan -> nama_kelas }} / {{ $tagihan -> semesterMhs }} / 
							@if(isset($config_jenis[$tagihan -> jenisPembayaran]))
							{{ $config_jenis[$tagihan -> jenisPembayaran] }} 
							@else
							<span class="text-danger">-</span>
							@endif
							@endif
						</p>
					</div>
					@endif
				</form>
			</div>
		</div>
	</div>
	@if($tagihan !== null)
	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Form Transaksi</h3>
			</div>
			<div class="box-body">
				<form role="form" method="POST" action="{{ url('/pembayaran/token') }}">
					{{ csrf_field() }}
					@if(!$golongan)
					<div class="form-group">
						<label for="golongan">Golongan</label>
						<p class="form-control-static">{{ $config_golongan[$tagihan -> golongan] }}</p>
					</div>
					@endif
					<div class="form-group">
						<label for="tagihan_id">Nama Tagihan</label>
						<p class="form-control-static">@if($golongan){{ $config_golongan[$tagihan -> golongan] }}@else{{ $tagihan -> nama_tagihan }}@endif</p>
					</div>
					<div class="form-group">
						<label for="jumlah">Jumlah Pembayaran</label>
						<p class="form-control-static">{{ formatRupiah($jumlah) }}</p>
					</div>
					@if($lunas)
					<div class="form-group">
						<label for="jumlah">Status</label>
						<p class="form-control-static text-success">Lunas</p>
					</div>
					@else
					<button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-money"></i> Proses</button>
					@endif
					{!! Form::hidden('kode', $kode) !!}
				</form>	
			</div>
		</div>		
	</div>
	@endif
</div>
@if($status !== null)
<div class="row">
	<div class="col-xs-12">
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">Status Pembayaran</h3>
				<div class="pull-right box-tools">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body">
				<?php 
					$c = 1; 
					$total = $t_bayar = $t_sisa = 0;
				?>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>No.</th>
							<th>Nama Tagihan</th>
							<th class="ac">Nominal</th>
							<th class="ac">Sudah dibayar</th>
							<th class="ac">Sisa</th>
							<th>Prosentase</th>
							<th>Keterangan</th>
						</tr>
					</thead>
					<tbody>
						@foreach($status as $s)
						<?php 
							$prosentase = (int)$s -> jumlah == 0 ? 0 : (int)$s -> bayar / (int)$s -> jumlah * 100; 
							$sisa = $s -> jumlah - $s -> bayar;
						?>
						<tr>
							<td>{{ $c }}</td>
							<td>{{ $s -> nama }}</td>
							<td class="ar">{{ formatRupiah($s -> jumlah) }}</td>
							<td class="ar">{{ formatRupiah($s -> bayar) }}</td>
							<td class="ar">{{ formatRupiah($sisa) }}</td>
							<td>{{ round($prosentase, 1) }}%</td>
							<td>@if($sisa > 0)Tunggakan {{ formatRupiah($sisa) }}@else<span>Lunas</span>@endif</td>
						</tr>
						<?php 
							$c++; 
							$total += $s -> jumlah;
							$t_bayar += $s -> bayar;
							$t_sisa += $sisa;
						?>
						@endforeach						
						<tr>
							<th colspan="2" class="ar">Total</th>
							<th class="ar">{{ formatRupiah($total) }}</th>
							<th class="ar">{{ formatRupiah($t_bayar) }}</th>
							<th class="ar">{{ formatRupiah($t_sisa) }}</th>
							<th colspan="2"></th>
						</tr>
					</tbody>
				</table>
				<br/>
				<a href="{{ url('/biaya/' . $tagihan -> NIM . '/cetak/status') }}" target="_blank" class="btn btn-success btn-flat" title="Cetak Status Pembayaran" style="margin: 0 0 10px 10px;"><i class="fa fa-print"></i> Cetak</a>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box box-success">
			<div class="box-header with-border">
				<h3 class="box-title">Riwayat Transaksi <small>(10 Transaksi terakhir)</small></h3>
			</div>
			<div class="box-body">	
				<?php $c = 1; ?>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>No.</th>
							<th>Nama Tagihan</th>
							<th>Tanggal</th>
							<th>Jumlah Pembayaran</th>
							<th>Petugas</th>
							<th>Metode Pambayaran</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@if($histories -> count())
						@foreach($histories as $history)
						<tr>
							<td>{{ $c }}</td>
							<td>
								@if($history -> nama_tagihan == '')
								{{ $history -> nama }} {{ $history -> tapel }}
								@else
								{{ $history -> nama_tagihan }}
								@endif
							</td>
							<td>{{ formatTanggal(explode(' ', $history -> created_at)[0]) }}</td>
							<td class="ar">{{ formatRupiah($history -> jumlah) }}</td>
							<td>{{ $history -> admin }}</td>
							<td>{{ $history -> metode }}</td>
							<td>
								<a href="{{ url('/biaya/'. $history -> id .'/cetak/kwitansi') }}" class="btn btn-success btn-xs btn-flat" title="Cetak Kwitansi Pembayaran" target="_blank"><i class="fa fa-print"></i></a>
								@if($history -> bank_id == 0)
								<a href="{{ url('/biaya/'. $history -> id .'/delete') }}" class="btn btn-danger btn-xs btn-flat has-confirmation" title="Hapus Data Pembayaran"><i class="fa fa-trash"></i></a>
								@endif
							</td>
						</tr>
						<?php $c++; ?>
						@endforeach
						@else
						<tr>
							<td colspan="6" class="ac">Belum ada data</td>
						</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endif	
@endsection

@push('styles')
<style>
	.ar{
	text-align: right !important;
	}
	.ac{
	text-align: center !important;
	}
	.al{
	text-align: left !important;
	}
</style>
@endpush