@extends('app')

@section('title')
Ekspor Data KRS FEEDER
@endsection

@section('header')
<section class="content-header">
	<h1>
		KRS
		<small>Ekspor Data FEEDER</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Ekspor Data KRS</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
	</div>
	<div class="box-body">
		{!! Form::open(['url' => url('/export/feeder/krs'), 'method' => 'get', 'class' => 'form-inline', 'autocomplete' => 'off']) !!}
		<div class="form-group">
			{!! Form::label('prodi_id', 'Prodi:', ['class' => 'sr-only']) !!}
			{!! Form::select('prodi_id', $prodi, Request::get('prodi_id'), ['class' => 'form-control']) !!}
		</div>
		<div class="form-group">
			{!! Form::label('angkatan', 'Angkatan:', ['class' => 'sr-only']) !!}
			{!! Form::select('angkatan', $angkatan, Request::get('angkatan'), ['class' => 'form-control']) !!}
		</div>
		<div class="form-group">
			{!! Form::label('tapel_id', 'Tahun Akademik:', ['class' => 'sr-only']) !!}
			{!! Form::select('tapel_id', $tapel, Request::get('tapel_id'), ['class' => 'form-control']) !!}
		</div>
		<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
		{!! Form::close() !!}
	</div>
</div>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Data KRS</h3>
	</div>
	<div class="box-body">
		@if($krs == null)	
		<p>Data KRS tidak ditemukan. Pilih Program Studi, Angkatan dan Tahun Akademik terlebih dahulu.</p>
		@else
		<div id="div_button">
			<button type="button" class="btn btn-danger btn-flat btn-check">
				<input type="checkbox" class="check-all" value="ttd"/>
				Pilih KRS yang <strong>BELUM</strong> terdaftar di Feeder 
			</button>
		</div>
		{!! Form::open(['url' => url('/export/feeder/krs'), 'method' => 'post']) !!}
		<?php $c=1; ?>
		<table class="table table-bordered" id="tbl-data">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #850bf6;">
					<th>No</th>
					<th>Semester</th>
					<th>NIM</th>
					<th>Nama</th>
					<th>Kode MK</th>
					<th>Nama MK</th>
					<th>Nama Kelas</th>
					<th>PRODI</th>
					<th>Status Feeder <i class="fa fa-question-circle-o text-info" data-toggle="popover" data-content="<i class='fa fa-check text-success'></i>
					: KRS <strong>SUDAH</strong> terdaftar. <br/><i class='fa fa-exclamation-triangle text-danger'></i>: KRS belum terdaftar."></i> </th>
					<th>
						<input type="checkbox" class="check-all" value="del"/>
						<button class="btn btn-danger btn-flat btn-xs btn-del-sel" type="button" data-url='/feeder/krs/delete'><i class="fa fa-trash"></i> Hapus</button>
					</th>
				</tr>
			</thead>
			<tbody>
				@foreach($krs as $m)
				<?php
					$nim = trim($m -> NIM);
					$key = $nim . '-' . $m -> kode . '-' . $m -> kelas . $m -> kelas2;
					$terdaftar = array_key_exists($key, $krs_terdaftar) ? $krs_terdaftar[$key] : '0';
					$id_reg_pd = array_key_exists($nim, $mhs_terdaftar) ? explode(':', $mhs_terdaftar[$nim])[1] : '0';
				?>
				<tr class="@if($terdaftar != '0') success @else warning @endif">
					<td>{{ $c }}</td>
					<td>
						<label for="id_{{ $m -> id_mt }}">
							@if($terdaftar == '0')
							<input type="checkbox" name="id_i[]" id="id_{{ $m -> id_mt }}" class="data_ttd " 
							value="{{ $nim }}:{{ $m -> kode }}:{{ $m -> nama_matkul }}:{{ $m -> kelas . $m -> kelas2 }}:{{ $m -> semester }}" />
							@endif
							{{ $m -> semester }}
						</label>
					</td>
					<td>{{ $m -> NIM }}</td>
					<td>{{ $m -> nama_mahasiswa }}</td>
					<td>{{ $m -> kode }}</td>
					<td>{{ $m -> nama_matkul }}</td>
					<td>{{ $m -> kelas . $m -> kelas2 }}</td>
					<td>{{ $m -> strata . ' ' . $m -> singkatan }}</td>
					<td>
						@if($terdaftar != '0') 
						<i class="fa fa-check text-success"></i> 
						@else
						<i class="fa fa-exclamation-triangle text-danger"></i> 
						@endif
					</td>
					<td>
						@if($terdaftar != '0') 
						<input type="checkbox" class="data_del" value="{{ $terdaftar }}:{{ $id_reg_pd }}" />
						<a class="btn btn-danger btn-flat btn-xs has-confirmation" href="{{ url('/feeder/krs/delete') }}?type=data&id_kls={{ $terdaftar }}&id_reg_pd={{ $id_reg_pd }}&prodi_id={{ Request::get('prodi_id') }}&tapel_id={{ Request::get('tapel_id') }}&angkatan={{ Request::get('angkatan') }}"><i class="fa fa-trash"></i></a>
						@endif
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>	
		<button type="button" class="btn btn-danger btn-flat btn-check">
			<input type="checkbox" class="check-all" value="ttd">
			Pilih KRS yang <strong>BELUM</strong> terdaftar di Feeder 
		</button>
		<hr/>
		<button class="btn btn-primary btn-lg btn-flat"><i class="fa fa-upload"></i> Kirim data</button>
		{!! Form::hidden('id_prodi', $id_prodi) !!}
		{!! Form::hidden('id_prodi_local', $id_prodi_local) !!}
		{!! Form::hidden('id_semester', $id_semester) !!}
		{!! Form::hidden('angkatan', $id_angkatan) !!}
		{!! Form::close() !!}
		@endif
	</div>
</div>
@endsection	

@include('feeder.lib')														