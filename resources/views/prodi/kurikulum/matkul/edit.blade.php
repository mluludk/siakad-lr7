@extends('app')

@section('title')
Kurikulum {{ $kurikulum -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Kurikulum
		<small> {{ $kurikulum -> nama }}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kurikulum') }}"> Kurikulum</a></li>
		<li class="active"> {{ $kurikulum -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Kurikulum {{ $kurikulum -> nama }}</h3>
		<div class="box-tools">
			<a href="{{ route('prodi.kurikulum.index') }}" class="btn btn-default btn-xs btn-flat" title="Daftar"><i class="fa fa-list"></i> Daftar</a>
		</div>
	</div>	
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				<form class="form-horizontal">
					<div class="form-group">
						{!! Form::label('', 'Program Studi :', array('class' => 'col-sm-3 control-label')) !!}
						<div class="col-sm-6">
							<p class='form-control-static'>{{ $kurikulum -> prodi -> nama }}</p>
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('', 'Mulai Berlaku :', array('class' => 'col-sm-3 control-label')) !!}
						<div class="col-sm-4">
							<p class='form-control-static'>{{ $kurikulum -> tapel -> nama }}</p>
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('', 'Jumlah SKS Wajib :', array('class' => 'col-sm-3 control-label')) !!}
						<div class="inline-block">
							<div class="col-sm-1">
								<p class='form-control-static'>{{ $kurikulum -> sks_wajib }}</p>
							</div>
						</div>
						<div class="inline-block">
							{!! Form::label('', 'Jumlah SKS Pilihan :', array('class' => 'col-sm-3 control-label')) !!}
						</div>
						<div class="inline-block">
							<div class="col-sm-1">
								<p class='form-control-static'>{{ $kurikulum -> sks_pilihan }}</p>
							</div>
						</div>
						<div class="inline-block">
							{!! Form::label('', 'Jumlah SKS :', array('class' => 'col-sm-3 control-label')) !!}
						</div>
						<div class="inline-block">
							<div class="col-sm-1">
								<p class='form-control-static'>{{ $kurikulum -> sks_total }}</p>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<style>
	th{
	vertical-align: middle !important;
	text-align: center;
	}
</style>
<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Mata Kuliah</h3>
	</div>	
	<div class="box-body">
		<form method="post" action="{{ route('prodi.kurikulum.matkul.update', $kurikulum -> id) }}" >
			{!! Form::hidden('_token', csrf_token()) !!}
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th rowspan="2">Pilih</th>
						<th rowspan="2" width="20px">No</th>
						<th rowspan="2"  width="10%">Kode</th>
						<th rowspan="2">Nama</th>
						<th colspan="5"  width="25%">SKS</th>
						<th rowspan="2"  width="5%">Semester</th>
						<th rowspan="2"  width="5%">Wajib?</th>
					</tr>
					<tr>
						<th>Mata Kuliah</th>
						<th>Tatap Muka</th>
						<th>Praktikum</th>
						<th>Prak. Lapangan</th>
						<th>Simulasi</th>
					</tr>
				</thead>
				@if(!count($matkul))
				<tr><th colspan="11">Belum ada data</th></tr>
				@else
				<?php
					$c=1;
					$total = $tm = $prak = $prak_lap = $sim = 0;
				?>
				<tbody>
					@foreach($matkul as $m)
					<tr>
						<td>
							<input type="checkbox" value="{{ $m -> id }}" name="c[]" checked />
						</td>
						<td>{{ $c }}</td>
						<td>{{ $m -> kode}}</td>
						<td>{{ $m -> nama }}</td>
						<td>{{ $m -> sks_total }}</td>
						<td>{{ $m -> sks_tm }}</td>
						<td>{{ $m -> sks_prak }}</td>
						<td>{{ $m -> sks_prak_lap }}</td>
						<td>{{ $m -> sks_sim }}</td>
						<td>
							{!! Form::select('semester[' . $m -> id . ']', array_combine($r = range(1,8), $r), $m -> semester) !!}
						</td>
						<td>
							<input type="checkbox" value="y" name="wajib[{{ $m -> id }}]" @if($m -> wajib == 'y') checked @endif />
						</td>
					</tr>
					<?php
						$c++;
						$total += intval($m -> sks_total);
						$tm += intval($m -> sks_tm);
						$prak += intval($m -> sks_prak);
						$prak_lap += intval($m -> sks_prak_lap);
						$sim += intval($m -> sks_sim);
					?>
					@endforeach
					<tr>
						<th colspan="4" style="text-align: right">Jumlah SKS</th>
						<th>{{ $total }}</th>
						<th>{{ $tm }}</th>
						<th>{{ $prak }}</th>
						<th>{{ $prak_lap }}</th>
						<th>{{ $sim }}</th>
						<th colspan="2"></th>
					</tr>
					@endif
				</tbody>
			</table>
			<br/>
			<p style="text-align: right">
				<button class="btn btn-warning btn-lg btn-flat btn-save" title="Simpan Perubahan Mata Kuliah" type="submit"><i class="fa fa-save"></i> Simpan</button>
			</p>
		</form>
	</div>
</div>
@endsection

@push('scripts')
<script>
	$('.btn-save').click(function(){
		
	});
</script>
@endpush