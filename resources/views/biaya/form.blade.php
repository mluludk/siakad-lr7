@extends('app')

@section('title')
Form Pembayaran
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Form Pembayaran</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Form Pembayaran</li>
	</ol>
</section>
@endsection

@push('scripts')
<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
<script>
	$(function(){
	$(".currency").inputmask('999.999.999', { numericInput: true, autoUnmask: true, removeMaskOnSubmit: true, unmaskAsNumber: true });
	});
	
	@if(isset($jenis_per_gol))
	$(document).on('change', 'select[name=golongan]', function(){
	var gol_id = $(this).val();
	var gol0 = "@foreach($jenis_list as $k => $v)<option value='{{ $k }}'>{{ $v }}</option>@endforeach";
	@foreach($jenis_per_gol as $k => $v)
	var gol{{ $k }} = "@foreach($v as $data)<option value='{{ $data[0] }}'>{{ $data[1] }}</option>@endforeach";
	@endforeach
	
	$('select[name=tagihan_id]').html(eval('gol' + gol_id));
	});
	@endif
</script>
@endpush

@section('content')
<div class="row">
	<div class=@if($mahasiswa !== null and !$lunas)"col-md-6"@else"col-sm-12"@endif>
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">Data Mahasiswa</h3>
			</div>
			<div class="box-body">
				<form role="form">
					<div class="form-group has-feedback{{ ($mahasiswa == null and $nim != '') ? ' has-error' : '' }}">
						<label for="nim">NIM Mahasiswa / Kode bayar</label>
						<div class="input-group">
							<input type="text" class="form-control" id="nim" name="nim" value="{{ $nim ?? '' }}" placeholder="Masukkan NIM Mahasiswa / Kode Bayar" autofocus="autofocus">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search"></i> Cari</button>
							</span>
						</div>
						@if ($mahasiswa == null and $nim != '')
						<span class="help-block">
							<strong>NIM tidak ditemukan</strong>
						</span>
						@endif
					</div>
					@if($mahasiswa !== null)
					<div class="form-group">
						<label for="nama">Nama</label>
						<p class="form-control-static">@if($mahasiswa !== null) {{ $mahasiswa -> nama }} ({{ $mahasiswa -> NIM }})@endif</p>
					</div>
					<div class="form-group">
						<label for="prodi">PRODI / Program / Semester / Jenis Pembiayaan</label>
						<p class="form-control-static">
							@if($mahasiswa !== null) 
							{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }} / {{ $mahasiswa -> kelas -> nama }} / {{ $mahasiswa -> semesterMhs }} / 
							@if(isset(config('custom.pilihan.jenisPembayaran')[$mahasiswa -> jenisPembayaran]))
							{{ config('custom.pilihan.jenisPembayaran')[$mahasiswa -> jenisPembayaran] }} 
							@else
							<span class="text-danger">-</span>
							@endif
							@endif
						</p>
					</div>
					@if(Auth::user() -> role_id <= 4)
					
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
					
					<a href="{{ url('/pembayaran/fix/' . $mahasiswa -> id) }}" class="btn btn-danger btn-flat" 
					data-toggle="popover" data-content="Gunakan <strong>HANYA</strong> jika diperlukan untuk memperbaiki data 
					Pembayaran yang tidak sinkron antara <u>Riwayat Transaksi</u> dan <u>Status Pembayaran</u>. 
					Pastikan data Pembayaran Mahasiswa sudah benar di <u>Riwayat Transaksi</u>.">
					<i class="fa fa-wrench"></i></a>
					@endif
					@endif
				</form>
			</div>
		</div>
	</div>
	@if($mahasiswa !== null and !$lunas)
	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Form Transaksi</h3>
			</div>
			<div class="box-body">
				@if(!count($jenis_list))
				<p class="help-block">Semua Biaya Kuliah sudah Lunas.</p>
				@else
				<form role="form" method="POST" action="{{ url('/biaya/form') }}">
					{{ csrf_field() }}
					<div class="form-group">
						<label for="golongan">Golongan</label>
						{!! Form::select('golongan', $golongan, null, ['class' => 'form-control']) !!}
					</div>
					<div class="form-group">
						<label for="tagihan_id">Tagihan</label>
						{!! Form::select('tagihan_id', $jenis_list, null, ['class' => 'form-control']) !!}
					</div>
					<div class="form-group has-feedback{{ $errors->has('jumlah') ? ' has-error' : '' }}">
						<label for="jumlah">Jumlah Pembayaran</label>
						<div class="input-group">
							<span class="input-group-addon">Rp </span>
							{!! Form::hidden('mahasiswa_id', $mahasiswa -> id) !!}
							{!! Form::text('jumlah', '', ['class' => 'form-control currency']) !!}
						</div>
						@if ($errors->has('jumlah'))
						<span class="help-block">
							<strong>{{ $errors->first('jumlah') }}</strong>
						</span>
						@endif
					</div>
					<button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
				</form>
				@endif		
			</div>
		</div>		
	</div>
	@endif
</div>
@if($tagihan !== null)
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
						@foreach($tagihan as $s)
						<?php 
							$prosentase = (int)$s -> jumlah == 0 ? 0 : (int)$s -> bayar / (int)$s -> jumlah * 100; 
							$sisa = $s -> jumlah - $s -> bayar;
						?>
						<tr>
							<td>{{ $c }}</td>
							<td>{{ $s -> nama }} {{ $s -> tapel }}</td>
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
				<a href="{{ url('/biaya/' . $mahasiswa -> NIM . '/cetak/status') }}" target="_blank" class="btn btn-danger btn-flat" title="Cetak Status Pembayaran" style="margin: 0 0 10px 10px;"><i class="fa fa-print"></i> Cetak</a>
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