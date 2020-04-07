@extends('app')

@section('title')
Validasi Prodi Mahasiswa
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
		<small>Validasi Prodi {{ $prodi }}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Validasi Mahasiswa Prodi {{ $prodi }}</li>
	</ol>
</section>
@endsection

@section('content')
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

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Status Validasi Mahasiswa Prodi {{ $prodi }}</h3>
	</div>
	<div class="box-body">
		<form method="post" action="{{ url('/validasi') }}">
			{!! csrf_field() !!}
			<table class="table table-bordered table-striped">
				<thead>
					<tr style="background-color: #70bbb0;">
						<th width="40px">NO</th>
						<th>NIM</th>
						<th>NAMA</th>
						<th width="100px">ANGKATAN</th>
						<!--
							<th style="background-color: #bdeaef;">PKM</th>
							<th style="background-color: #eef5a4;">PPL</th>
						-->
						<th style="background-color: #a2f5a6;">WISUDA</th>
					</tr>
				</thead>
				<tbody>
					@if(!$mahasiswa -> count())
					<tr>
						<td colspan="5" align="center">Data mahasiswa tidak ditemukan</td>
					</tr>
					@else
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
						<!--
							<td class="ctr">
							<input type="hidden" name="{{ $g -> id }}[id]" />
							<input type="checkbox" name="{{ $g -> id }}[tg_pr_pkm]" value="1" @if($g -> tg_pr_pkm == 1) checked="checked" @endif>
							</td>
							<td class="ctr">
							<input type="checkbox" name="{{ $g -> id }}[tg_pr_ppl]" value="1" @if($g -> tg_pr_ppl == 1) checked="checked" @endif>
							</td>
						-->
						<td class="ctr">
							<input type="checkbox" name="{{ $g -> id }}[tg_pr_wis]" value="1" @if($g -> tg_pr_wis == 1) checked="checked" @endif>
						</td>
					</tr>
					@endforeach
					@endif
				</tbody>
			</table>
			<br/>
			<button class="pull-right btn btn-lg btn-warning btn-flat"><i class="fa fa-save"></i> Simpan</button>
		</form>
	</div>
</div>
@endsection																																																															