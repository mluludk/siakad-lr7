@extends('app')

@section('title')
Ekspor Data Kelulusan FEEDER
@endsection

@section('header')
<section class="content-header">
	<h1>
		Kelulusan
		<small>Ekspor Data FEEDER</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Ekspor Data Kelulusan FEEDER</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter Kelulusan</h3>
	</div>
	<div class="box-body">
		{!! Form::open(['url' => url('/export/feeder/kelulusan'), 'method' => 'get', 'class' => 'form-inline', 'autocomplete' => 'off']) !!}
		<div class="form-group">
			{!! Form::label('kode_dikti', 'Prodi:', array('class' => 'sr-only')) !!}
			{!! Form::select('kode_dikti', $prodi_select, Request::get('kode_dikti'), ['class' => 'form-control']) !!}
		</div>
		<div class="form-group">
			{!! Form::label('angkatan', 'Angkatan:', array('class' => 'sr-only')) !!}
			{!! Form::select('angkatan', $angkatan_select, Request::get('angkatan'), ['class' => 'form-control']) !!}
		</div>
		<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
		{!! Form::close() !!}
	</div>
</div>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Kelulusan</h3>
	</div>
	<div class="box-body">
		@if($mahasiswa == null)
		<p>Data Nilai tidak ditemukan. Pilih Program Studi dan Angkatan terlebih dahulu.</p>				
		@else
		<?php $c=1; ?>
		<div id="div_button">
			<button type="button" class="btn btn-danger btn-flat btn-check">
				<input type="checkbox" class="check-all" value="ttd">
				Pilih Kelulusan yang <strong>BELUM</strong> terdaftar di Feeder 
			</button>
			<button type="button" class="btn btn-success btn-flat btn-check">
				<input type="checkbox" class="check-all" value="td">
				Pilih Kelulusan yang <strong>SUDAH</strong> terdaftar di Feeder 
			</button>
		</div>	
		{!! Form::open(['url' => url('/export/feeder/kelulusan'), 'method' => 'post']) !!}
		<table class="table table-bordered" id="tbl-data">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); 
				background-image: -moz-linear-gradient(#3A8341,#054a10);
				color: white;">
					<th rowspan="2">No</th>
					<th rowspan="2">NIM</th>
					<th rowspan="2">Nama</th>
					<th colspan="2">Jenis Keluar</th>
					<th colspan="2">NO IJAZAH</th>
					<th colspan="2">Tgl KELUAR</th>
					<th colspan="2">Tgl SK</th>
					<th colspan="2">IPK</th>
					<th rowspan="2">PRODI</th>
					<th rowspan="2">Status Feeder</th>
					<th rowspan="2">
						<input type="checkbox" class="check-all" value="del"/>
						<button class="btn btn-danger btn-flat btn-xs btn-del-sel" type="button" data-url='/feeder/kelulusan/delete'><i class="fa fa-trash"></i> Hapus</button>
					</th>
				</tr>
				<tr>
					<th class="ctr" style="background-color: #2cf0e4;">Lokal</th>
					<th class="ctr" style="background-color: #2cf0e4;">Feeder</th>
					<th class="ctr" style="background-color: #2cf0e4;">Lokal</th>
					<th class="ctr" style="background-color: #2cf0e4;">Feeder</th>
					<th class="ctr" style="background-color: #aba3f0;">Lokal</th>
					<th class="ctr" style="background-color: #aba3f0;">Feeder</th>
					<th class="ctr" style="background-color: #efdd0b;">Lokal</th>
					<th class="ctr" style="background-color: #efdd0b;">Feeder</th>
					<th class="ctr" style="background-color: #0befc2;">Lokal</th>
					<th class="ctr" style="background-color: #0befc2;">Feeder</th>
				</tr>
			</thead>
			<tbody>
				@if(!count($mahasiswa))
				<tr>
					<td colspan="7">Data tidak ditemukan</td>
				</tr>
				@else
				@foreach($mahasiswa as $m)
				<?php		
					$nim = trim($m -> NIM);
					$status_keluar = array_key_exists($nim, $lls_terdaftar) ? $lls_terdaftar[$nim] : '0';
					$reg_mhs = array_key_exists($nim, $mhs_terdaftar) ? explode(':', $mhs_terdaftar[$nim])[1] : '0';
				?>
				<tr class="@if($status_keluar != '0') success @else warning @endif">
					<td>{{ $c }}</td>
					<td>
						<label>
							@if($status_keluar == '0')				
							<input type="checkbox" name="dt[]" class="data_ttd" value="{{ $nim }}:0:{{ $reg_mhs }}" />
							@else
							<input type="checkbox" name="dt[]" class="data_td" value="{{ $nim }}:1:{{ $reg_mhs }}" />
							@endif
							{{ $m -> NIM }}
						</label>
					</td>
					<td>{{ $m -> nama }}</td>
					<td>
						@if($m -> statusMhs == 4)
						Lulus
						@elseif($m -> statusMhs == 3)
						DO
						@else
						-
						@endif
					</td>
					<td>{{ $mhs_pt[$m -> NIM]['fk_jns_keluar'] ?? '-' }}</td>
					<td>{{ $m -> noIjazah }}</td>
					<td>{{ $mhs_pt[$m -> NIM]['no_ijasah'] ?? '-' }}</td>
					<td>{{ $m -> tglKeluar }}</td>
					<td>{{ $mhs_pt[$m -> NIM]['tgl_keluar'] ?? '-' }}</td>
					<td>{{ $m -> tglSKYudisium ?? '' }}</td>
					<td>{{ $mhs_pt[$m -> NIM]['tgl_sk_yudisium'] ?? '-' }}</td>
					<td>{{ $m -> ipk }}</td>
					<td>{{ $mhs_pt[$m -> NIM]['ipk'] ?? '-' }}</td>
					<td>{{ $m -> strata }} {{ $m -> singkatan }}</td>
					<td>
						@if($status_keluar != '0') 
						<i class="fa fa-check text-success" data-toggle="popover" data-content="Kelulusan Mahasiswa <strong>SUDAH</strong> terdaftar"></i> 
						@else
						<i class="fa fa-exclamation-triangle text-danger" data-toggle="popover" data-content="Kelulusan Mahasiswa belum terdaftar"></i> 
						@endif
						
						@if($reg_mhs == '0') 
						<i class="fa fa-user text-danger" data-toggle="popover" data-content="Mahasiswa belum terdaftar "></i> 
						@endif
					</td>
					<td>
						@if($status_keluar != '0') 
						<input type="checkbox" class="data_del" value="{{ $reg_mhs }}" />
						<a class="btn btn-danger btn-flat btn-xs has-confirmation" href="{{ url('/feeder/kelulusan/delete?type=data&id_reg_pd=' . $reg_mhs) }}&kode_dikti={{ Request::get('kode_dikti') }}&angkatan={{ Request::get('angkatan') }}"><i class="fa fa-trash"></i></a>
						@endif
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
			</tbody>
		</table>
		@if(count($mahasiswa))
		<button type="button" class="btn btn-danger btn-flat btn-check">
			<input type="checkbox" class="check-all" value="ttd">
			Pilih Mahasiswa yang <strong>BELUM</strong> terdaftar di Feeder 
		</button>
		<button type="button" class="btn btn-success btn-flat btn-check">
			<input type="checkbox" class="check-all" value="td">
			Pilih Kelulusan yang <strong>SUDAH</strong> terdaftar di Feeder 
		</button>
		@endif
		<hr/>
		<button class="btn btn-primary btn-lg btn-flat"><i class="fa fa-upload"></i> Kirim data</button>
		{!! Form::hidden('id_sms', $id_prodi_feeder) !!}
		{!! Form::hidden('kode_dikti', $kode_dikti) !!}
		{!! Form::hidden('angkatan', $angkatan) !!}
	{!! Form::close() !!}
	@endif
	</div>
	</div>
	@endsection
	
	@include('feeder.lib')										