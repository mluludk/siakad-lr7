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
		<li><a href="{{ url('/kurikulum') }}?nama={{ $kurikulum -> nama }}"> {{ $kurikulum -> nama }}</a></li>
		<li class="active"> Angkatan {{ $kurikulum -> angkatan }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Kurikulum {{ $kurikulum -> nama }}</h3>
		<div class="box-tools">
			<a onclick="javascript:history.back()" class="btn btn-default btn-xs btn-flat" title="Daftar"><i class="fa fa-list"></i> DAFTAR</a>
			<a href="{{ route('prodi.kurikulum.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data"><i class="fa fa-plus"></i> TAMBAH</a>
			<a href="{{ route('prodi.kurikulum.edit', $kurikulum -> id) }}" class="btn btn-warning btn-xs btn-flat" title="Edit data kurikulum"><i class="fa fa-edit"></i> EDIT </a>
			<a href="{{ route('prodi.kurikulum.delete', $kurikulum -> id) }}" class="btn btn-danger btn-xs has-confirmation btn-flat" title="Hapus data kurikulum"><i class="fa fa-trash"></i> HAPUS</a>	
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
						{!! Form::label('', 'Angkatan :', array('class' => 'col-sm-3 control-label')) !!}
						<div class="col-sm-6">
							<p class='form-control-static'>{{ $kurikulum -> angkatan }}</p>
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
		<div class="box-tools">
			{!! Form::model(new Siakad\Kurikulum, ['class' => 'form-inline', 'role' => 'form', 'route' => ['prodi.kurikulum.matkul.add', $kurikulum ->  id]]) !!}
			Salin Mata Kuliah Kurikulum dari 
			{!! Form::select('from', $kurikulums, null, ['class' => 'form-control input-sm']) !!}
			<button class="btn btn-success btn-xs btn-flat" type="submit"><i class="fa fa-copy"></i> Salin Mata Kuliah</button>	
			<a href="{{ route('prodi.kurikulum.matkul.edit',  $kurikulum -> id) }}" class="btn btn-warning btn-xs btn-flat" title="Edit Mata Kuliah"><i class="fa fa-edit"></i> Edit Kolektif Mata Kuliah</a>	
			<a href="{{ route('prodi.kurikulum.matkul.create',  $kurikulum -> id) }}" class="btn btn-primary btn-xs btn-flat" title="Tambah Mata Kuliah"><i class="fa fa-plus"></i> Tambah Mata Kuliah</a>
			{!! Form::close() !!}
		</div>
	</div>	
	<div class="box-body">
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-color: #70bbb0;">
					<th rowspan="2" width="20px">No</th>
					<th rowspan="2"  width="10%">KODE MATA KULIAH</th>
					<th rowspan="2">MATA KULIAH</th>
					<th colspan="5"  width="25%" style="background-color: #bdeaef;">SKS</th>
					<th rowspan="2"  width="5%" style="background-color: #bdeaef;" >SEMESTER</th>
					<th rowspan="2"  width="5%">WAJIB?</th>
					<th rowspan="2"></th>
				</tr>
				<tr>
					<th class="ctr" style="background-color: #bdeaef;">SKS</th>
					<th class="ctr" style="background-color: #eef5a4;">Tatap Muka</th>
					<th class="ctr" style="background-color: #a2f5a6;">Praktikum</th>
					<th class="ctr" style="background-color: #f0e5e5;">Prak. Lapangan</th>
					<th class="ctr" style="background-color: #e3ecc6;">Simulasi</th>
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
					<td>{{ $c }}</td>
					<td>{{ $m -> kode}}</td>
					<td>{{ $m -> nama }}</td>
					<td>{{ $m -> sks_total }}</td>
					<td>{{ $m -> sks_tm }}</td>
					<td>{{ $m -> sks_prak }}</td>
					<td>{{ $m -> sks_prak_lap }}</td>
					<td>{{ $m -> sks_sim }}</td>
					<td>{{ $m -> semester }}</td>
					<td>@if($m -> wajib == 'y')<i class="fa fa-check text-success"></i>@endif</td>
					<td>
						<a href="{{ route('prodi.kurikulum.matkul.delete', [$m -> kurikulum_id, $m -> matkul_id . '-' . $m -> semester]) }}" class="btn btn-danger btn-xs has-confirmation btn-flat"><i class="fa fa-trash"></i></a>
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
					<th colspan="3" style="text-align: right">Jumlah SKS</th>
					<th>{{ $total }}</th>
					<th>{{ $tm }}</th>
					<th>{{ $prak }}</th>
					<th>{{ $prak_lap }}</th>
					<th>{{ $sim }}</th>
					<th colspan="3"></th>
				</tr>
				@endif
			</tbody>
		</table>
	</div>
</div>
@endsection																												