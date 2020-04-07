@extends('app')

@section('title')
Status Validasi Mahasiswa
@endsection

@push('scripts')
<script>
	$('.filter').change(function(){
		$('form#filter-form').submit();
	});
</script>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Status Validasi Mahasiswa</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Status Validasi Mahasiswa</li>
	</ol>
</section>
@endsection

@section('content')
<style>
	th{
	vertical-align: middle !important
	}
	.ctr{
	text-align: center !important;
	}
</style>

<div class="row">
	<div class="col-sm-6">
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">Cari Mahasiswa</h3>
			</div>
			<div class="box-body">
				<form method="get" action="{{ url('/validasi') }}">
					{!! csrf_field() !!}
					<div class="row">
						<div class="col-xs-12">
							<div class="input-group{{ $errors -> has('q') ? ' has-error' : '' }}">
								<input type="text" class="form-control" name="q" placeholder="Pencarian ...." value="{{ Request::get('q') }}" onClick="this.select();">
								<span class="input-group-btn">
									<button class="btn btn-info btn-flat" type="submit"><i class="fa fa-search"></i> Cari</button>
								</span>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div class="col-sm-6">
		<div class="box box-warning">
			<div class="box-header with-border">
				<h3 class="box-title">Filter</h3>
			</div>
			<div class="box-body">
				<form method="get" action="{{ url('/validasi') }}" class="form-inline" id="filter-form">
					{!! csrf_field() !!}
					<div class="form-group">
						<label class="sr-only" for="prodi">PRODI</label>
						{!! Form::select('prodi', $prodi, Request::get('prodi'), ['class' => 'form-control filter']) !!}
					</div>
					<div class="form-group">
						<label class="sr-only" for="semester">Semester</label>
						{!! Form::select('semester', $semester, Request::get('semester'), ['class' => 'form-control filter']) !!}
					</div>
					<div class="form-group">
						<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@if(!$mahasiswa -> count())
<div class="alert alert-info alert-dismissible">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	<h4><i class="icon fa fa-info"></i> Informasi</h4>
	Data mahasiswa tidak ditemukan
</div>
@else
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Status Validasi Mahasiswa</h3>
	</div>
	<div class="box-body">
		<form method="post" action="{{ url('/validasi') }}">
			{!! csrf_field() !!}
			
			<table class="table table-bordered table-striped">
				<thead>
					<tr style="background-color: #70bbb0;">
						<th rowspan="2" width="40px">NO</th>
						<th rowspan="2" width="130px">NIM</th>
						<th rowspan="2" width="300px">NAMA</th>
						<th rowspan="2" width="100px">ANGKATAN</th>
						<th rowspan="2" width="60px">PRODI</th>
						<th colspan="3" class="ctr" style="background-color: #bdeaef;">KEUANGAN</th>
						<th colspan="3" class="ctr" style="background-color: #f0e5e5;">AKADEMIK</th>
						<th colspan="3" class="ctr">KAPRODI</th>
						<th colspan="3" class="ctr" style="background-color: #f27beb;">TANGGUNGAN SKS NILAI</th>
					</tr>
					<tr style="background-color: #70bbb0;">
						<th class="ctr" style="background-color: #bdeaef;">PKM</th>
						<th class="ctr" style="background-color: #eef5a4;">PPL</th>
						<th class="ctr" style="background-color: #a2f5a6;">WISUDA</th>
						<th class="ctr" style="background-color: #f0e5e5;">PKM</th>
						<th class="ctr" style="background-color: #eef5a4;">PPL</th>
						<th class="ctr" style="background-color: #a2f5a6;">WISUDA</th>
						<th class="ctr" style="background-color: #70bbb0;">PKM</th>
						<th class="ctr" style="background-color: #eef5a4;">PPL</th>
						<th class="ctr" style="background-color: #a2f5a6;">WISUDA</th>
						<th class="ctr" style="background-color: #f27beb;">PKM</th>
						<th class="ctr" style="background-color: #aeaef2;">PPL</th>
						<th class="ctr" style="background-color: #f6ae7c;">WISUDA</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$n=0;			
					?>
					@foreach($mahasiswa as $g)
					<?php 
						$n++;			
					?>
					<tr>
						<td>{{ $n }}</td>
						<td>{{ $g -> NIM }}</td>
						<td>{{ $g -> nama }}</td>
						<td>{{ $g -> angkatan }}</td>
						<td>{{ $g -> prodi -> singkatan }}</td>
						<td class="ctr">
							<input type="hidden" name="{{ $g -> id }}[id]" />
							<input type="checkbox" name="{{ $g -> id }}[tg_ku_pkm]" value="1" @if($g -> tg_ku_pkm == 1) checked="checked" @endif>
						</td>
						<td class="ctr">
							<input type="checkbox" name="{{ $g -> id }}[tg_ku_ppl]" value="1" @if($g -> tg_ku_ppl == 1) checked="checked" @endif>
						</td>
						<td class="ctr">
							<input type="checkbox" name="{{ $g -> id }}[tg_ku_wis]" value="1" @if($g -> tg_ku_wis == 1) checked="checked" @endif>
						</td>
						<td class="ctr">
							<input type="checkbox" name="{{ $g -> id }}[tg_ak_pkm]" value="1" @if($g -> tg_ak_pkm == 1) checked="checked" @endif>
						</td>
						<td class="ctr">
							<input type="checkbox" name="{{ $g -> id }}[tg_ak_ppl]" value="1" @if($g -> tg_ak_ppl == 1) checked="checked" @endif>
						</td>
						<td class="ctr">
							<input type="checkbox" name="{{ $g -> id }}[tg_ak_wis]" value="1" @if($g -> tg_ak_wis == 1) checked="checked" @endif>
						</td>
						<td class="ctr">
							<input type="checkbox" name="{{ $g -> id }}[tg_pr_pkm]" value="1" @if($g -> tg_pr_pkm == 1) checked="checked" @endif>
						</td>
						<td class="ctr">
							<input type="checkbox" name="{{ $g -> id }}[tg_pr_ppl]" value="1" @if($g -> tg_pr_ppl == 1) checked="checked" @endif>
						</td>
						<td class="ctr">
							<input type="checkbox" name="{{ $g -> id }}[tg_pr_wis]" value="1" @if($g -> tg_pr_wis == 1) checked="checked" @endif>
						</td>
						
						<td class="ctr">
							<input type="checkbox" name="{{ $g -> id }}[tg_sks_pkm]" value="1" @if($g -> tg_sks_pkm > 0) checked="checked" @endif>
						</td>
						<td class="ctr">
							<input type="checkbox" name="{{ $g -> id }}[tg_sks_ppl]" value="1" @if($g -> tg_sks_ppl > 0) checked="checked" @endif>
						</td>
						<td class="ctr">
							<input type="checkbox" name="{{ $g -> id }}[tg_sks_wis]" value="1" @if($g -> tg_sks_wis > 0) checked="checked" @endif>
						</td>
						
					</tr>
					@endforeach
				</tbody>
			</table>
			<br/>
			<button class="pull-right btn btn-lg btn-warning btn-flat"><i class="fa fa-save"></i> Simpan</button>
		</form>
	</div>
</div>
@endif
@endsection																																																															