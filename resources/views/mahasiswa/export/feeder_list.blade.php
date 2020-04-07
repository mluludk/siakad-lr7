@extends('app')

@section('title')
Ekspor Data Mahasiswa FEEDER
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Ekspor Data Mahasiswa FEEDER</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url('/mahasiswa/') }}"> Mahasiswa</a></li>
		<li class="active">Ekspor Data FEEDER</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
	</div>
	<div class="box-body">
		{!! Form::open(['url' => url('/export/feeder/mahasiswa'), 'method' => 'get', 'class' => 'form-inline', 'autocomplete' => 'off']) !!}
		<div class="form-group">
			{!! Form::label('prodi', 'Prodi:', ['class' => 'sr-only']) !!}
			{!! Form::select('prodi', $prodi, Request::get('prodi'), ['class' => 'form-control']) !!}
		</div>
		<div class="form-group">
			{!! Form::label('angkatan', 'Angkatan:', ['class' => 'sr-only']) !!}
			{!! Form::select('angkatan', $angkatan, Request::get('angkatan'), ['class' => 'form-control']) !!}
		</div>
		<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
		{!! Form::close() !!}	
	</div>
</div>


<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Data Mahasiswa</h3>	
	</div>
	
	<div class="box-body">	
		@if($mahasiswa == null)	
		<p>Data Mahasiswa tidak ditemukan. Pilih Program Studi dan Angkatan terlebih dahulu.</p>
		@else
		<div id="div_button">
			<button type="button" class="btn btn-danger btn-flat btn-check">
				<input type="checkbox" class="check-all" value="ttd"/>
				Pilih Mahasiswa yang <strong>BELUM</strong> terdaftar di Feeder 
			</button>
		</div>
		{!! Form::open(['url' => url('/export/feeder/mahasiswa'), 'method' => 'post']) !!}
		<?php $c=1; ?>
		<table class="table table-bordered" id="tbl-data">
			<thead>
				<tr style="
				background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>No</th>
					<th>NIM</th>
					<th>Nama</th>
					<th>PRODI</th>
					<th>Status Feeder 
						<i class="fa fa-question-circle-o text-info" 
						data-toggle="popover" data-content="
						<i class='fa fa-check text-success'></i>: Mahasiswa <strong>SUDAH</strong> terdaftar. <br/>
						<i class='fa fa-exclamation-triangle text-danger'></i>: Mahasiswa belum terdaftar."></i>
					</th>
					<th>
						<input type="checkbox" class="check-all" value="del"/>
						<button class="btn btn-danger btn-flat btn-xs btn-del-sel" type="button" data-url='/feeder/mahasiswa/delete'><i class="fa fa-trash"></i> Hapus</button>
					</th>
				</tr>
			</thead>
			<tbody>
				@foreach($mahasiswa as $m)
				<?php
					$terdaftar = array_key_exists($m -> NIM, $mhs_terdaftar) ? $mhs_terdaftar[$m -> NIM] : '0';
					$id = explode(':', $terdaftar);
				?>
				<tr class="@if($terdaftar != '0') success @else warning @endif">
					<td>{{ $c }}</td>
					<td>
						<label for="id_{{ $m -> id }}">
							@if($terdaftar != '0')
							<a data-toggle="popover" data-content="Sync data Mahasiswa di SIAKAD dan Feeder." class="btn btn-warning btn-xs btn-flat" 
							href="{{ route('sync.feeder') }}?id={{ $m -> id }}&id_feeder={{ $terdaftar }}">
								<i class="fa fa-refresh" ></i>
							</a>
							@else
							<input type="checkbox" name="id_i[]" id="id_{{ $m -> id }}" class="data_ttd " value="{{ $m -> id }}" />
							@endif
							{{ $m -> NIM }}
						</label>
					</td>
					<td><a href="{{ route('mahasiswa.show', $m -> id) }}">{{ $m -> nama }}</a></td>
					<td>{{ $m -> strata }} {{ $m -> prodi }}</td>
					<td>
						@if($terdaftar != '0') 
						<i class="fa fa-check text-success"></i> 
						@else
						<i class="fa fa-exclamation-triangle text-danger"></i> 
						@endif
					</td>
					<td>
						@if($terdaftar != '0') 
						<input type="checkbox" class="data_del" value="{{ $terdaftar }}" />
						<a class="btn btn-danger btn-flat btn-xs has-confirmation" href="{{ url('/feeder/mahasiswa/delete?type=data&id_pd='. $id[0] .'&id_reg_pd=' . $id[1]) }}&kode_dikti={{ Request::get('prodi') }}&angkatan={{ Request::get('angkatan') }}"><i class="fa fa-trash"></i></a>
						@endif
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>	
		<button type="button" class="btn btn-danger btn-flat btn-check">
			<input type="checkbox" class="check-all" value="ttd">
			Pilih Mahasiswa yang <strong>BELUM</strong> terdaftar di Feeder 
		</button>
		<hr/>
		<button class="btn btn-primary btn-lg btn-flat"><i class="fa fa-upload"></i> Kirim data</button>
		{!! Form::hidden('kode_dikti', $kode_dikti) !!}
		{!! Form::hidden('id_semester', $id_semester) !!}
		{!! Form::hidden('id_prodi', $id_prodi) !!}
		{!! Form::hidden('id_pt', $id_pt) !!}
		{!! Form::close() !!}
		@endif
	</div>
</div>
@endsection	

@include('feeder.lib')								