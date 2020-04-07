@extends('app')

@section('title')
Data Dosen - {{ $dosen -> nama}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Data Dosen</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen') }}"> Daftar Dosen</a></li>
		<li class="active">{{ $dosen -> nama}}</li>
	</ol>
</section>
@endsection

@push('styles')
<style>
	#preview{
	width: 166px;
	height: 220px;
	padding: 5px;
	margin: 15px auto;
	border: 1px solid #999;
	position: relative;
	overflow: hidden;
	}
	
	#preview img {
	max-width: 100%;
	max-height: 100%;
	width: auto;
	height: auto;
	position: absolute;
	left: 50%;
	top: 50%;
	-webkit-transform: translate(-50%, -50%);
	-moz-transform: translate(-50%, -50%);
	-ms-transform: translate(-50%, -50%);
	transform: translate(-50%, -50%);
	}
	
	.status{
	width: 100%;
	text-align: center;
	margin-bottom: 10px;
	}
	.sidebar-menu-small h5{
	text-align: center;
	background-color: #023355;
	color: white;
	padding: 5px;
	}
	.sidebar-menu-small {
    list-style: none;
    margin: 0;
    padding: 0
	}
	.sidebar-menu-small > li {
    position: relative;
    margin: 0;
    padding: 0
	}
	.sidebar-menu-small > li > a {
    padding: 5px 2px 5px 12px;
    display: block
	}
	.sidebar-menu-small > li > a > .fa{
    width: 20px
	}
	
	.sidebar-menu-small > li > a {
    border-left: 3px solid transparent;
	color: #120101;
	border-bottom: 1px solid #bbb;
	}
	.sidebar-menu-small > li:hover > a,
	.sidebar-menu-small > li.active > a {
    color: #3c8dbc;
    background: #f5f9fc;
    border-left-color: #3c8dbc
	}
	
</style>
@endpush

@section('content')
<div class="row">
	<div class="col-sm-3">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Nama : {{ $dosen -> NIY }} / {{ ucwords(strtolower($dosen -> nama)) }}</h3>
			</div>
			<div class="box-body" style="padding-left: 0px;">
				<div id="preview">
					<img src="@if(isset($dosen->foto) and $dosen->foto != '')/getimage/{{ $dosen->foto }} @else/images/b.png @endif"></img>
				</div>
				<div class="status">
					@if($dosen -> statusDosen == 1)
					<span class="label label-success">{{ config('custom.pilihan.statusKepegawaian')[$dosen -> statusKepegawaian] }}</span>
					@else
					<span class="label label-default">{{ config('custom.pilihan.statusKepegawaian')[$dosen -> statusKepegawaian] }}</span>
					@endif
				</div>
				
				<ul class="sidebar-menu-small">
					<li><h5>AKSI CEPAT</h5></li>
					@include('dosen.partials._menu', ['role_id' => \Auth::user() -> role_id, 'dosen' => $dosen])
					</ul>
			</div>
		</div>
	</div>
	
	<div class="col-sm-9">
		<div class="box box-primary">
			<div class="box-header with-border">	
				<div class="box-tool pull-right">
					<a href="{{ route('dosen.edit', $dosen -> id) }}" class="btn btn-warning btn-xs btn-flat" title="Edit data dosen"><i class="fa fa-pencil-square-o"></i> Edit Data</a>
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-xs-12">
						<table width="100%">
							<tbody>
								<tr><th width="20%">NAMA</th><td width="30%">: {{ $dosen->nama }}</td><th width="20%">NAMA IBU</th><td>: {{ $dosen->nama_ibu }}</td></tr>
								<tr><th>TEMPAT LAHIR</th><td>: {{ $dosen->tmpLahir }}</td><th>TANGGAL LAHIR</th><td>: {{ $dosen->tglLahir }}</td></tr>
								<tr><th>JENIS KELAMIN</th><td>: {{ config('custom.pilihan.jenisKelamin')[$dosen -> jenisKelamin] }}</td><th>AGAMA</th><td>: {{ config('custom.pilihan.agama')[$dosen->agama] }}</td></tr>
								<tr><th>STATUS AKTIF</th><td>: {{ config('custom.pilihan.statusKepegawaian')[$dosen -> statusKepegawaian] }}</td><th>NIDN / NUP / NIDK</th><td>: {{ $dosen->NIDN }}</td></tr>
							</tbody>
						</table>
					</div>
				</div>		
			</div>
		</div>
		
		<div class="box box-info">			
			<ul class="nav nav-tabs">
				<li class="active"><a href="#biodata" data-toggle="tab">BIODATA</a></li>
				<li><a href="#keluarga" data-toggle="tab">KELUARGA</a></li>
				<li><a href="#ikatan" data-toggle="tab">IKATAN KERJA DOSEN</a></li>
				<li><a href="#homebase" data-toggle="tab">HOMEBASE</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="biodata">
					<table class="table table-bordered table-striped">
						<tbody>
							<tr><th>NIY</th><td> {{ $dosen->NIY }}</td><th width="10%">KODE DOSEN:</th><td colspan="3">{{ $dosen->kode }}</td></tr>
							<tr><th>NAMA</th><td colspan="5">{{ $dosen -> gelar_depan }} {{ trim($dosen -> nama) }}@if(isset($dosen -> gelar_belakang)), {{ $dosen -> gelar_belakang }} @endif</td></tr>
							<tr><th width="18%">NIK</th><td colspan="5">{{ $dosen->NIK }}</td></tr>
							<tr><th>ALAMAT</th><td colspan="5">{{ $dosen -> alamat }}</td></tr>
							<tr><th>KABUPATEN</th><td> {{ $dosen -> kabupaten }}</td><th>PROVINSI:</th><td colspan="3">{{ config('custom.pilihan.emis.provinsi')[$dosen -> provinsi] }}</td></tr>
							<tr><th>TELEPON</th><td>{{ $dosen->telp }}</td><th>HP</th><td colspan="3">{{ $dosen -> hp }}</td></tr>
							<tr><th>Email</th><td colspan="5">{{ $dosen->email }}</td></tr>
						</tbody>
					</table>
				</div>			
				<div class="tab-pane" id="homebase">
					<table class="table table-bordered table-striped">
						<tbody>
							<tr><th width="18%">HOMEBASE</th><td>@if(isset($prodi[$dosen -> homebase])) {{ $prodi[$dosen -> homebase] }} @else - @endif</td></tr>
							<tr><th>BIDANG KEAHLIAN</th><td>@if(isset($prodi[$dosen -> bid_keahlian])) {{ $prodi[$dosen -> bid_keahlian] }} @else - @endif</td></tr>
							<tr>
								<th>BIDANG MATKUL</th>
								<td>
									@if(isset($dosen) and null !== $dosen -> bid_matkul)
									<ol class="bid_matkul tim_dosen">
										@foreach($dosen -> bid_matkul as $b)
										<li id="{{ $b -> id }}">{{ $b -> kode }} - {{ $b -> nama }}</li>
										@endforeach
									</ol>
									@endif
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="keluarga">
					<table class="table table-bordered table-striped">
						<tbody>
							<tr><th width="18%">STATUS PERNIKAHAN</th><td colspan="5">{{ isset($dosen -> statusSipil) ? config('custom.pilihan.statusSipil')[$dosen -> statusSipil] : '' }}</td></tr>
							<tr><th>NAMA SUAMI / ISTRI </th><td> </td></tr>
							<tr><th>NIP SUAMI / ISTRI </th><td> </td></tr>
							<tr><th>TMT PNS </th><td> </td></tr>
							<tr><th>PEKERJAAN</th><td> </td></tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="ikatan">
					<table class="table table-bordered table-striped">
						<tbody>
							<tr><th>NAMA</th><td colspan="5">{{ $dosen -> gelar_depan }} {{ trim($dosen -> nama) }}@if(isset($dosen -> gelar_belakang)), {{ $dosen -> gelar_belakang }} @endif</td></tr>
							<tr><th>NIY</th><td> {{ $dosen->NIY }}</td></tr>
							<tr><th>NIDN / NUP / NIDK</th><td> {{ $dosen->NIDN }}</td></tr>
							<tr><th width="18%">IKATAN KERJA</th><td>@if(isset(config('custom.pilihan.statusDosen')[$dosen -> statusDosen])){{ config('custom.pilihan.statusDosen')[$dosen -> statusDosen] }}@endif</td><th></th><td></td></tr>
							<tr><th>JENIS PEGAWAI</th><td colspan="5"><?php $pns = [1 => 'PNS', 2 => 'Non PNS']; ?>{{ $pns[$dosen -> pns] }}</td></tr>
							<tr><th>NO SK CPNS</th><td colspan="5">{{ $dosen -> skcpns }}</td></tr>
							<tr><th>LEMBAGA PENGANGKAT</th><td colspan="5">{{ config('custom.pilihan.emis.instansi')[$dosen -> instansi] }}</td></tr>
							<tr><th>TANGGAL MULAI MASUK</th><td>{{ $dosen -> tgl_mulai_masuk }}</td></tr>
							<tr><th>NO SK PENGANKATAN</th><td>{{ $dosen -> no_sk_awal }} <strong>TMT:</strong> {{ $dosen -> tmt_sk_awal }}</td></tr>
							<tr><th>NO SK TERBARU</th><td>{{ $dosen -> no_sk_awal }} <strong>TMT:</strong> {{ $dosen -> tmt_sk_awal }}</td></tr>
							<tr><th>PANGKAT GOLONGAN</th><td colspan="5">{{ $dosen -> golongan }}</td></tr>
							<tr><th>SUMBER GAJI</th><td colspan="5">{{ $dosen -> sumber_gaji }}</td></tr>
							<tr><th>STATUS KEAKTIFAN EMIS</th><td colspan="5">{{ config('custom.pilihan.emis.status_keaktifan')[$dosen -> status_keaktifan] }}</td></tr>
							<tr><th>JABFUNG</th><td colspan="5">{{ $dosen->jabfung }}</td></tr>
							<tr><th>STATUS SERTIFIKASI</th><td colspan="5">{{ $dosen->sertifikasi }}</td></tr>
							<tr><th>TAHUN LULUS SERTIFIKASI</th><td colspan="5">{{ $dosen->thn_lulussertifikasi }}</td></tr>
							<tr><th>TUNJANGAN PROFESI</th><td colspan="5"><?php $tunjangan = ['Belum menerima', 'Sudah menerima']; ?>{{ $tunjangan[$dosen -> tunjangan_profesi] }}</td></tr>
							<tr><th>BESAR TUNPROF</th><td colspan="5">Rp {{ number_format($dosen -> besar_tunjangan_profesi, 2, ',', '.') }}</td></tr>
							<tr><th>JABATAN TAMBAHAN</th><td colspan="5">{{ config('custom.pilihan.emis.jabatan_tambahan')[$dosen -> jabatan_tambahan] }}</td></tr>					
						</tr>
					</tbody>
				</table>
			</div>
		</div>							
	</div>							
</div>							
@endsection																	