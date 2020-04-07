@extends('app')

@section('title')
Ekspor Data Kelas Kuliah FEEDER
@endsection

@section('header')
<section class="content-header">
	<h1>
		Kelas Kuliah
		<small>Ekspor Data FEEDER</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url('/matkul/tapel/') }}"> Kelas Kuliah</a></li>
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
		{!! Form::open(['url' => url('/export/feeder/kelaskuliah'), 'method' => 'get', 'class' => 'form-inline', 'autocomplete' => 'off']) !!}
		<div class="form-group">
			{!! Form::label('prodi_id', 'Prodi:', ['class' => 'sr-only']) !!}
			{!! Form::select('prodi_id', $prodi, Request::get('prodi_id'), ['class' => 'form-control']) !!}
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
		<h3 class="box-title">Data Kelas Kuliah</h3>
	</div>
	<div class="box-body">	
		@if($kelas == null)	
		<p>Data Kelas Kuliah tidak ditemukan. Pilih Program Studi dan Tahun Akademik terlebih dahulu.</p>
		@else
		<div id="div_button">
			<button type="button" class="btn btn-danger btn-flat btn-check">
				<input type="checkbox" class="check-all" value="ttd"/>
				Pilih Kelas Kuliah yang <strong>BELUM</strong> terdaftar di Feeder 
			</button>
		</div>
		{!! Form::open(['url' => url('/export/feeder/kelaskuliah'), 'method' => 'post']) !!}
		<?php $c=1; ?>
		<table class="table table-bordered" id="tbl-data">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th rowspan="2">No</th>
					<th rowspan="2">Semester</th>
					<th rowspan="2">Kode Mata Kuliah</th>
					<th rowspan="2">Nama Mata Kuliah</th>
					<th rowspan="2">Nama Kelas</th>
					<th colspan="2">Dosen</th>
					<th rowspan="2">PRODI</th>
					<th rowspan="2">
						Status Feeder <i class="fa fa-question-circle-o text-info" data-toggle="popover" data-content="<i class='fa fa-check text-success'></i>
						: Kelas Kuliah <strong>SUDAH</strong> terdaftar. <br/><i class='fa fa-exclamation-triangle text-danger'></i>: Kelas Kuliah belum terdaftar."></i>
					</th>
					<th rowspan="2">
						<input type="checkbox" class="check-all" value="del"/>
						<button class="btn btn-danger btn-flat btn-xs btn-del-sel" type="button" data-url='/feeder/kelaskuliah/delete'><i class="fa fa-trash"></i> Hapus</button>
					</th>
				</tr>
				<tr>
					<th>Lokal</th>
					<th>Feeder</th>
				</tr>
			</thead>
			<tbody>
				@foreach($kelas as $m)
				<?php
					$key = $m -> kode . '-' . $m -> kelas . $m -> kelas2 . '-' . $m -> semester;
					$terdaftar = array_key_exists($key, $kls_terdaftar) ? $kls_terdaftar[$key] : '0';
					$td = [];
					foreach($m -> tim_dosen as $d)
					{
						if(isset($penugasan_dosen[$d -> NIDN]))
						{
							$td[] = $penugasan_dosen[$d -> NIDN];
						}
					}
					$td = count($td) ? implode('|', $td) : 0;
					
					$id_kls_dosen = explode(':', $terdaftar);
					$id_kls = $id_kls_dosen[0];
				?>
				<tr class="@if($terdaftar != '0') success @else warning @endif">
					<td>{{ $c }}</td>
					<td>
						<label for="id_{{ $m -> id_mt }}">
							@if($terdaftar == '0')
							<input type="checkbox" name="id_i[]" id="id_{{ $m -> id_mt }}" class="data_ttd " 
							value="{{ $m -> id }}:{{ $m -> kode }}:{{ $m -> nama_matkul }}:{{ $m -> kelas . $m -> kelas2 }}:{{ $td }}:{{ $m -> sks }}" />
							@else
							<a data-toggle="popover" data-content="Sync data Kelas Kuliah di SIAKAD dan Feeder." class="btn btn-warning btn-xs btn-flat" 
							href="{{ route('sync.feeder.kelaskuliah') }}?id_kls={{ $id_kls }}
							&kode={{ $m -> kode }}&nama={{ $m -> nama_matkul }}&kelas={{ $m -> kelas . $m -> kelas2 }}&id_reg_dosen={{ $td }}
							&sks={{ $m -> sks }}&id_semester={{ $id_semester }}&id_prodi_local={{ $id_prodi_local }}">
							<i class="fa fa-refresh" ></i></a>
							@endif
							{{ $m -> semester }}
						</label>
					</td>
					<td>{{ $m -> kode }}</td>
					<td>{{ $m -> nama_matkul }}</td>
					<td>{{ $m -> kelas . $m -> kelas2 }}</td>
					<td>{!! formatTimDosen($m -> tim_dosen) !!}</td>
					<td>{{ $id_kls_dosen[1] ?? '' }}</td>
					<td>{{ $m -> strata . ' ' . $m -> nama_prodi }}</td>
					<td>
						@if($terdaftar != '0') 
						<i class="fa fa-check text-success"></i> 
						@else
						<i class="fa fa-exclamation-triangle text-danger"></i> 
						@endif
					</td>
					<td>
						@if($terdaftar != '0') 
						<input type="checkbox" class="data_del" value="{{ $id_kls }}" />
						<a class="btn btn-danger btn-flat btn-xs has-confirmation" href="{{ url('/feeder/kelaskuliah/delete?type=data&id_kls=' . $id_kls) }}&prodi_id={{ Request::get('prodi_id') }}&tapel_id={{ Request::get('tapel_id') }}"><i class="fa fa-trash"></i></a>
					@endif
					</td>
					</tr>
					<?php $c++; ?>
					@endforeach
					</tbody>
					</table>	
					<button type="button" class="btn btn-danger btn-flat btn-check">
					<input type="checkbox" class="check-all" value="ttd">
					Pilih Kelas Kuliah yang <strong>BELUM</strong> terdaftar di Feeder 
					</button>
					<hr/>
					<button class="btn btn-primary btn-lg btn-flat"><i class="fa fa-upload"></i> Kirim data</button>
					{!! Form::hidden('id_prodi', $id_prodi) !!}
					{!! Form::hidden('id_prodi_local', $id_prodi_local) !!}
					{!! Form::hidden('id_semester', $id_semester) !!}
					{!! Form::close() !!}
					@endif
					</div>
					</div>
					@endsection	
					
					@include('feeder.lib')		
										