@extends('app')

@section('title')
Rincian Biaya Pendidikan
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Rincian Biaya Pendidikan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Rincian Biaya Pendidikan</li>
	</ol>
</section>
@endsection

@push('scripts')
<script>	
	$('[data-toggle="tooltip"]').tooltip({'placement': 'auto top'});
</script>
@endpush

@section('content')
<div class="row">
	<div class="col-md-4 col-xs-6">
		<div class="box box-warning">
			<div class="box-header with-border">
				<h3 class="box-title">Filter</h3>
			</div>
			<div class="box-body">
				<form role="form">
					<div class="form-group">
						<label for="prodi_id">PRODI</label>
						{!! Form::select('prodi', $prodi, Request::get('prodi'), ['class' => 'form-control']) !!}
					</div>
					<div class="form-group">
						<label for="angkatan">Angkatan</label>
						{!! Form::select('angkatan', $angkatan, Request::get('angkatan'), ['class' => 'form-control']) !!}
					</div>
					<div class="form-group">
						<label for="program_id">Program</label>
						{!! Form::select('program', $program, Request::get('program'), ['class' => 'form-control']) !!}
					</div>						
					<div class="form-group">
						<label for="jenisPembayaran">Jenis</label>
						{!! Form::select('jenis', $jenis, Request::get('jenis'), ['class' => 'form-control filter']) !!}
					</div>
					<button class="btn btn-warning btn-flat btn-filter"><i class="fa fa-filter"></i> Filter</button>
				</form>
			</div>
		</div>
	</div>	
	
	<div class="col-md-8 col-xs-6">
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">Biaya Pendidikan</h3>
			</div>
			<div class="box-body">
				@if($setup == null)
				<p class="text-muted">Data tidak ditemukan</p>
				@else
				<table class="table table-bordered table-striped">
					<?php 
						// dd($setup);
						$x = 1; 
						$total = 0;
						$c = $setup -> count();
						$mid = (int)ceil($c / 2);
						$next = 0;
					?>
					@for($s = 0; $s < $mid; $s++)
					<tr>
						<?php
							$next = $s + $mid;
						?>
						<th>{{ $x }}</th>
						<th>{{ $setup[$s] -> nama }}</th>
						<td style="width: 20px; border-right-color: transparent;">Rp</td>
						<td class="align-right">{{ number_format($setup[$s] -> tanggungan , 0, ',', '.') }}</td>
						<?php 
							$y = $x + $mid; 
							$total += $setup[$s] -> tanggungan;
						?>
						<th>{{ $y }}</th>
						<th>@if(isset($setup[$next])) {{ $setup[$next] -> nama }}@endif</th>
						<td style="width: 20px; border-right-color: transparent;">Rp</td>
						<td class="align-right">@if(isset($setup[$next])){{ number_format($setup[$next] -> tanggungan , 0, ',', '.') }}@endif</td>
						<?php 
							$x++; 
							if(isset($setup[$next]))
							{
								$total += $setup[$next] -> tanggungan;
							}
						?>
					</tr>
					@endfor
				</table>
				<h3 style="text-align: right;">Total: Rp{{ number_format($total , 0, ',', '.') }}</h3>
				@endif
			</div>
		</div>		
	</div>
	
</div>
<style>
	table.rincian{
	font-size: 13px;
	border-collapse: collapse;
	width: 100%;
	}
	.rincian th{
	text-align: center;
	padding: 0px 2px;
	}
	.rincian th, .rincian td{
	color: #000;
	border: 1px solid black;
	}
	.rincian td{
	text-align: right;
	font-size: 11px;
	}
	.align-right{
	text-align: right !important;
	}
	.align-left{
	text-align: left !important;
	}
	.align-center{
	text-align: center !important;
	}
</style>
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Rincian Biaya Pendidikan Mahasiswa</h3>
	</div>
	<div class="box-body">
		@if($rincian == null)
		<p class="text-muted">Data tidak ditemukan</p>
		@else
		<?php 
			$c = 1; 
		?>
		<table class="rincian">
			<thead>
				<tr>
				<th width="20px">No</th>
				<th>Nama</th>
				<?php $n = 1; ?>
				@foreach($setup as $j)
				<th><div data-toggle="tooltip" title="{{ $j -> nama }}">{{ $n }}</div></th>
				<?php 
				$s2[] = $j -> id;
				$n++; 
				?>
				@endforeach
				</tr>
				</thead>
				<tbody>
				@foreach($rincian as $k => $v)
				<?php
				$id = explode('-', $k);
				?>
				<tr>
				<td class="align-left">{{ $c }}</td>
				<td class="align-left">{{ $id[1] }}</td>
				@foreach($s2 as $s3)
				<td>{{ $v[$s3] ?? 0 }}</td>
				@endforeach
				</tr>
				<?php $c++; ?>
				@endforeach		
				</tbody>
				</table>
				<br/>
				<p>
				<a class="btn btn-primary btn-flat" href="{{ route('biaya.detail') }}?prodi={{ Request::get('prodi') }}&angkatan={{ Request::get('angkatan') }}&program={{ Request::get('program') }}&jenis={{ Request::get('jenis') }}&mode=print"><i class="fa fa-print"></i> Cetak</a>&nbsp;
				<a class="btn btn-success btn-flat" href="{{ route('biaya.detail') }}?prodi={{ Request::get('prodi') }}&angkatan={{ Request::get('angkatan') }}&program={{ Request::get('program') }}&jenis={{ Request::get('jenis') }}&mode=xlsx"><i class="fa fa-file-excel-o"></i> Excel</a>&nbsp;
				<!--a class="btn btn-danger btn-flat" href="{{ route('biaya.detail') }}??prodi={{ Request::get('prodi') }}&angkatan={{ Request::get('angkatan') }}&program={{ Request::get('program') }}&jenis={{ Request::get('jenis') }}&mode=pdf"><i class="fa fa-file-pdf-o"></i> PDF</a-->
				</p>
				@endif
				</div>
				</div>
				@endsection																																																																															