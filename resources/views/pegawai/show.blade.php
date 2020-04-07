@extends('app')

@section('title')
Data Pegawai Non Dosen - {{ $pegawai -> nama}}
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
<?php
	$conf = config('custom.pilihan');	
?>
<div class="row">
	<div class="col-sm-3">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Nama : {{ $pegawai -> niy }} / {{ ucwords(strtolower($pegawai -> nama)) }}</h3>
			</div>
			<div class="box-body" style="padding-left: 0px;">
				<div id="preview">
					<img src="@if(isset($pegawai->foto) and $pegawai->foto != '')/getimage/{{ $pegawai->foto }} @else/images/b.png @endif"></img>
				</div>
				<div class="status">
					@if($pegawai -> status_keaktifan == 1)
					<span class="label label-success">{{ $conf['emis']['status_keaktifan'][$pegawai -> status_keaktifan] }}</span>
					@else
					<span class="label label-default">{{ $conf['emis']['status_keaktifan'][$pegawai -> status_keaktifan] }}</span>
					@endif
				</div>
				
				<ul class="sidebar-menu-small">
					<li><h5>AKSI CEPAT</h5></li>
					<li><a href="#biodata" data-toggle="tab">Detil Pegawai</a></li>
					<li><a href="#ikatan" data-toggle="tab">Ikatan Pegawai</a></li>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="col-sm-9">
		<div class="box box-primary">
			<div class="box-header with-border">	
				<div class="box-tool pull-right">
					<a href="{{ route('pegawai.edit', $pegawai -> id) }}" class="btn btn-warning btn-xs btn-flat" title="Edit data pegawai"><i class="fa fa-pencil-square-o"></i> Edit Data</a>
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-xs-12">
						<table width="100%">
							<tbody>
								<tr><th width="20%">NAMA</th><td width="30%">: {{ $pegawai -> nama }}</td><th width="20%">NAMA IBU</th><td>: {{ $pegawai->nama_ibu }}</td></tr>
								<tr><th>TEMPAT LAHIR</th><td>: {{ $pegawai->tmp_lahir }}</td><th>TANGGAL LAHIR</th><td>: {{ $pegawai->tgl_lahir }}</td></tr>
								<tr><th>JENIS KELAMIN</th><td>: {{ $conf['jenisKelamin'][$pegawai -> jenis_kelamin] }}</td><th></th><td></td></tr>
								<tr><th>STATUS AKTIF</th><td>: {{ $conf['emis']['status_keaktifan'][$pegawai -> status_keaktifan] }}</td><th>NIP</th><td>: {{ $pegawai -> nip }}</td></tr>
							</tbody>
						</table>
					</div>
				</div>		
			</div>
		</div>
		
		<div class="box box-info">			
			<ul class="nav nav-tabs">
				<li class="active"><a href="#biodata" data-toggle="tab">BIODATA</a></li>
				<li><a href="#ikatan" data-toggle="tab">IKATAN KERJA</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="biodata">
					<table class="table table-bordered table-striped">
						<tbody>
							<tr><th width="18%">NIK</th><td> {{ $pegawai -> nik }}</td><th width="10%"></th><td colspan="3"></td></tr>
							<tr><th>ALAMAT</th><td colspan="3">{{ $pegawai -> alamat }}</td></tr>
							<tr><th>KABUPATEN</th><td> {{ $pegawai -> kab }}</td><th>PROVINSI:</th><td>{{ $conf['emis']['provinsi'][$pegawai -> provinsi] }}</td></tr>
							<tr><th>TELEPON</th><td colspan="3">{{ $pegawai -> telp }}</td></tr>
								<tr><th>EMAIL</th><td colspan="3">{{ $pegawai -> email }}</td></tr>
							</tbody>
							</table>
						</div>
						<div class="tab-pane" id="ikatan">
							<table class="table table-bordered table-striped">
								<tbody>
									<tr><th width="18%">NAMA</th><td colspan="3">{{ $pegawai -> gelar_depan ?? '' }} {{ trim($pegawai -> nama) }} {{ $pegawai -> gelar_belakang ?? '' }}</td></tr>
									<tr><th>JENIS PEGAWAI</th><td colspan="3"><?php $pns = [1 => 'PNS', 2 => 'Non PNS']; ?>{{ $pns[$pegawai -> pns] }}</td></tr>
									<tr><th>NIP</th><td colspan="3"> {{ $pegawai->nip }}</td></tr>
									<tr><th>GOLONGAN</th><td colspan="3"> {{ $conf['golongan'][$pegawai->golongan] }}</td></tr>
									<tr><th>NIY</th><td colspan="3"> {{ $pegawai->niy }}</td></tr>
									<tr><th>SK AWAL</th><td>{{ $pegawai->no_sk_awal }}</td><th>TMT</th><td>{{ $pegawai->tmt_sk_awal }}</td></tr>
									<tr><th>SK TERBARU</th><td>{{ $pegawai->no_sk_terbaru }}</td><th>TMT</th><td>{{ $pegawai->tmt_sk_terbaru }}</td></tr>
									<tr><th>INSTANSI PENGANGKAT</th><td colspan="3">{{ $conf['emis']['instansi'][$pegawai -> instansi] }}</td></tr>
									<tr><th>STATUS TUGAS</th><td colspan="3">{{ $conf['emis']['status_tugas'][$pegawai -> status_tugas] }}</td></tr>
									<tr><th>STATUS KEAKTIFAN</th><td colspan="3">{{ $conf['emis']['status_keaktifan'][$pegawai -> status_keaktifan] }}</td></tr>
									<tr><th>UNIT TUGAS</th><td>{{ $conf['emis']['unit_tugas'][$pegawai -> unit_tugas] }}</td><th>NAMA UNIT</th><td>{{ $pegawai -> nama_unit_tugas }}</td></tr>
									<tr><th>TUGAS POKOK</th><td colspan="3">{{ $conf['emis']['tugas_pokok'][$pegawai -> tugas_pokok] }}</td></tr>					
									<tr><th>TUGAS TAMBAHAN</th><td colspan="3">{{ $conf['emis']['tugas_tambahan'][$pegawai -> tugas_tambahan] }}</td></tr>					
									<tr><th>PENDIDIKAN TERAKHIR</th><td colspan="3">{{ $conf['pendidikanDosen'][$pegawai -> pendidikan_terakhir] }}</td></tr>					
									<tr><th>PROGRAM STUDI</th><td colspan="3">{{ $pegawai -> program_studi }}</td></tr>					
									<tr><th>TANGGAL IJASAH</th><td colspan="3">{{ $pegawai -> tgl_ijasah }}</td></tr>					
								</tr>
							</tbody>
						</table>
					</div>
				</div>							
			</div>							
		</div>							
	@endsection																			