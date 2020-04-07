@extends('app')

@section('title')
Ekspor Data Nilai FEEDER V1
@endsection

@section('header')
<section class="content-header">
	<h1>
		Nilai Perkuliahan V1
		<small>Ekspor Data FEEDER</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Ekspor Data Nilai Perkuliahan FEEDER V1</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Filter Nilai Perkuliahan</h3>
	</div>
	<div class="box-body">
		{!! Form::open(['url' => url('/export/feeder/nilaiv1'), 'method' => 'get', 'class' => 'form-inline', 'autocomplete' => 'off']) !!}
		<div class="form-group">
			{!! Form::label('prodi_id', 'Prodi:', array('class' => 'sr-only')) !!}
			{!! Form::select('prodi_id', $prodi, Request::get('prodi_id'), ['class' => 'form-control']) !!}
		</div>
		<div class="form-group">
			{!! Form::label('angkatan', 'Angkatan:', array('class' => 'sr-only')) !!}
			{!! Form::select('angkatan', $angkatan, Request::get('angkatan'), ['class' => 'form-control']) !!}
		</div>
		<div class="form-group">
			{!! Form::label('tapel_id', 'Tahun Akademik:', array('class' => 'sr-only')) !!}
			{!! Form::select('tapel_id', $tapel, Request::get('tapel_id'), ['class' => 'form-control']) !!}
		</div>
		<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
		{!! Form::close() !!}
	</div>
</div>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Data Nilai</h3>
		<div class="box-tools">
			<a href="{{ url('/export/feeder/nilaikelas') }}" class="btn btn-success btn-xs btn-flat" title="Ekspor Nilai V2"><i class="fa fa-cloud-upload"></i> Ekspor Nilai V2</a>
		</div>
	</div>
	<div class="box-body">
		@if($nilai == null)
		<p>Data Nilai tidak ditemukan. Pilih Program Studi, Angkatan dan Tahun Akademik terlebih dahulu.</p>		
		@else
		<div id="div_button">
			<button type="button" class="btn btn-danger btn-flat btn-check">
				<input type="checkbox" class="check-all" value="ttd"/>
				Pilih Nilai yang <strong>BELUM</strong> terdaftar di Feeder 
			</button>	
			<button type="button" class="btn btn-info btn-flat btn-check">
				<input type="checkbox" class="check-all" value="tnk"/>
				Pilih Nilai yang masih Kosong
			</button>	
			<button type="button" class="btn btn-success btn-flat btn-check">
				<input type="checkbox" class="check-all" value="td"/>
				Pilih Nilai yang <strong>SUDAH</strong> terdaftar di Feeder 
			</button>
		</div>
		{!! Form::open(['url' => url('/export/feeder/nilaiv1'), 'method' => 'post']) !!}
		<?php $c=1; ?>
		<table class="table table-bordered" id="tbl-data">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #850bf6;">
					<th rowspan="2">No</th>
					<th rowspan="2">Semester</th>
					<th rowspan="2">NIM</th>
					<th rowspan="2">Nama</th>
					<th rowspan="2">Kode MK</th>
					<th rowspan="2">Nama MK</th>
					<th rowspan="2">Kelas</th>
					<th colspan="2">Nilai Lokal</th>
					<th colspan="2">Nilai FEEDER</th>
					<th rowspan="2">PRODI</th>
					<th rowspan="2">Status Feeder</th>
					<th rowspan="2"></th>
				</tr>
				<tr>
					<th>Huruf</th>
					<th>Indeks</th>
					<th>Huruf</th>
					<th>Indeks</th>
				</tr>
			</thead>
			<tbody>
				@foreach($nilai as $m)
				<?php
					$key = $m -> NIM . '-' . $m -> kode . '-' . $m -> kelas . $m -> kelas2;
					$terdaftar = array_key_exists($key, $nilai_terdaftar) ? true : false;					
					$id_kls_kuliah = array_key_exists($m -> kode . '-' . $m -> kelas . $m -> kelas2 . '-' . $id_semester, $kls_terdaftar) ? $kls_terdaftar[$m -> kode . '-' . $m -> kelas . $m -> kelas2 . '-' . $id_semester] : '0';
					
					$id_kls = explode(':', $id_kls_kuliah)[0];
					
					$id_reg_mhs = array_key_exists($m -> NIM, $mhs_terdaftar) ? explode(':', $mhs_terdaftar[$m -> NIM])[1] : '0';
					
					$huruf = ($m -> nilai != '' and $m -> nilai != '-') ? $m -> nilai : '-';
					$indeks = ($m -> nilai != '' and $m -> nilai != '-') ? config('custom.konversi_nilai.base_4')[$m -> nilai] : '0';
					$angka = ($m -> nilai != '' and $m -> nilai != '-') ? config('custom.konversi_nilai.base_100')[$m -> nilai] : '0';
					
					$nilai_huruf = $nilai_indeks = '-';
					$nilai_kosong = 0;
					if(isset($nilai_terdaftar[$key]))
					{
						$nilai_pddikti = explode(':', $nilai_terdaftar[$key]);
						$nilai_huruf = $nilai_pddikti[2];
						$nilai_indeks = $nilai_pddikti[3];
						$nilai_kosong = intval($nilai_pddikti[3]);
					}
				?>
				<tr class="@if($terdaftar && $nilai_kosong == 0) info @elseif($terdaftar) success @else warning @endif">
					<td>{{ $c }}</td>
					<td>
						@if(!$terdaftar)
						@if($id_kls_kuliah != '0' and $id_reg_mhs != '0')
						<input type="checkbox" name="dt[]" class="data_ttd" 
						value="{{ $id_kls }}:{{ $id_reg_mhs }}:{{ $huruf }}:{{ $indeks }}:{{ $angka }}:{{ $m -> NIM }}-{{ $m -> kode }}-{{ $m -> kelas . $m -> kelas2 }}" />
						@endif
						@else
						@if($nilai_kosong == 0)
						<input type="checkbox" name="dt[]" class="data_tnk" 
						value="{{ $id_kls }}:{{ $id_reg_mhs }}:{{ $huruf }}:{{ $indeks }}:{{ $angka }}:{{ $m -> NIM }}-{{ $m -> kode }}-{{ $m -> kelas . $m -> kelas2 }}" />
						@else
						<input type="checkbox" name="dt[]" class="data_td" 
						value="{{ $id_kls }}:{{ $id_reg_mhs }}:{{ $huruf }}:{{ $indeks }}:{{ $angka }}:{{ $m -> NIM }}-{{ $m -> kode }}-{{ $m -> kelas . $m -> kelas2 }}" />
						@endif
						@endif
						{{ $m -> semester }}
					</td>
					<td>{{ $m -> NIM }}</td>
					<td>{{ $m -> nama_mahasiswa }}</td>
					<td>{{ $m -> kode }}</td>
					<td>{{ $m -> nama_matkul }}</td>
					<td>{{ $m -> kelas . $m -> kelas2 }}</td>
					<td>{{ $huruf }}</td>
					<td>{{ $indeks }}</td>
					<td>{{ $nilai_huruf }}</td>
					<td>{{ $nilai_indeks }}</td>
					<td>{{ $m -> strata . ' ' . $m -> nama_prodi }}</td>
					<td>
						@if($terdaftar && $nilai_kosong == 0) 
						<i class="fa fa-exclamation-triangle text-danger" data-toggle="popover" data-content="Nilai sudah terdaftar di FEEDER, namun masih kosong"></i> 
						@elseif($terdaftar) 
						<i class="fa fa-check text-success" data-toggle="popover" data-content="Nilai OK."></i> 
						@else
						<i class="fa fa-question-circle text-danger" data-toggle="popover" data-content="Nilai <strong>BELUM</strong> terdaftar di FEEDER"></i> 
						@endif
						
						@if($id_kls_kuliah == '0')
						<i class="fa fa-group text-danger" data-toggle="popover" data-content="Kelas Kuliah <strong>BELUM</strong> terdaftar."></i> 
						@endif
						@if($id_reg_mhs == '0')
						<i class="fa fa-user text-danger" data-toggle="popover" data-content="Mahasiswa <strong>BELUM</strong> terdaftar."></i> 
						@endif
					</td>
					<td>
						@if($terdaftar && $nilai_kosong != 0) 
						<a href="{{ route('delete.feeder.nilaiv1') }}?query={{ $id_kls }}:{{ $id_reg_mhs }}:{{ $m -> NIM }}:{{ $m -> nama_mahasiswa }}:{{ $m -> kode }}:{{ $m -> nama_matkul }}" class="btn btn-danger btn-flat btn-xs has-confirmation" data-toggle="popover" data-content="Hapus Nilai yang sudah terdaftar di FEEDER."><i class="fa fa-times"></i></a>
						@endif
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>	
		<button type="button" class="btn btn-danger btn-flat btn-check">
			<input type="checkbox" class="check-all" value="ttd">
			Pilih Nilai yang <strong>BELUM</strong> terdaftar di Feeder 
		</button>
		<button type="button" class="btn btn-info btn-flat btn-check">
			<input type="checkbox" class="check-all" value="tnk">
			Pilih Nilai yang masih Kosong
		</button>
		<button type="button" class="btn btn-success btn-flat btn-check">
			<input type="checkbox" class="check-all" value="td">
			Pilih Nilai yang <strong>SUDAH</strong> terdaftar di Feeder 
		</button>
		<hr/>
		<button class="btn btn-primary btn-lg btn-flat"><i class="fa fa-send"></i> Kirim data</button>
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