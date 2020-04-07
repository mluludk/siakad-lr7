@extends('app')

@section('title')
Data Mahasiswa - {{ $mahasiswa -> nama ?? ''}}
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
				<h3 class="box-title">Nama : {{ $mahasiswa -> NIM }} / {{ ucwords(strtolower($mahasiswa -> nama)) }}</h3>
			</div>
			<div class="box-body" style="padding-left: 0px;">
				<div id="preview">
					<img src="@if(isset($mahasiswa->foto) and $mahasiswa->foto != '')/getimage/{{ $mahasiswa->foto }} @else/images/b.png @endif"></img>
				</div>
					<div class="status">
					@if($mahasiswa -> statusMhs == 1)
					<span class="label label-success">{{ config('custom.pilihan.statusMhs')[$mahasiswa -> statusMhs] }}</span>
					@else
					<span class="label label-default">{{ config('custom.pilihan.statusMhs')[$mahasiswa -> statusMhs] }}</span>
					@endif
				</div>
			</div>
		</div>
	</div>

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Detail</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li class="active">{{ ucwords(strtolower($mahasiswa -> nama)) }}</li>
	</ol>
</section>
@endsection

<div class="col-sm-9">
		<div class="box box-primary">
			<div class="box-header with-border">	
				<div class="box-tool pull-right">
				<a href="{{ url('profil/edit') }}" class="btn btn-warning btn-xs btn-flat" title="Edit Profil"><i class="fa fa-pencil-square-o"></i> Edit Profil</a>	
			</div>
		</div>
			<div class="box-body">
				<div class="row">
					<div class="col-xs-12">
						<table width="100%">
							<tbody>
								<tr><th width="20%">NAMA</th><td width="30%">: {{ $mahasiswa->nama }}</td><th width="20%">NAMA IBU</th><td>: {{ $mahasiswa->namaIbu }}</td></tr>
								<tr><th>TEMPAT LAHIR</th><td>: {{ $mahasiswa->tmpLahir }}</td><th>TANGGAL LAHIR</th><td>: {{ $mahasiswa->tglLahir }}</td></tr>
								<tr><th>JENIS KELAMIN</th><td>: {{ config('custom.pilihan.jenisKelamin')[$mahasiswa -> jenisKelamin] }}</td><th>AGAMA</th><td>: {{ config('custom.pilihan.agama')[$mahasiswa->agama] }}</td></tr>
								<tr><th>STATUS AKTIF</th><td>: {{ config('custom.pilihan.statusMhs')[$mahasiswa -> statusMhs] }}</td><th>NIM</th><td>: {{ $mahasiswa->NIM }}</td></tr>
							</tbody>
						</table>
					</div>
				</div>		
			</div>
		</div>

		<div class="box box-info">			
			<ul class="nav nav-tabs">
				<li class="active"><a href="#alamat" data-toggle="tab">BIODATA</a></li>
				<li><a href="#ortu" data-toggle="tab">ORANG TUA</a></li>
				<li><a href="#wali" data-toggle="tab">WALI</a></li>
				<li><a href="#akademik" data-toggle="tab">AKADEMIK</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="alamat">
					<table class="table table-bordered table-striped">
					<tbody>
						<tr><th width="18%">Nama</th><td colspan="5">{{ $mahasiswa->nama }}</td></tr>
						<tr><th width="18%">NIK</th><td colspan="5">{{ $mahasiswa->NIK }}</td></tr>
						<tr><th>TTL</th><td colspan="5">@if($mahasiswa->tmpLahir != ''){{ $mahasiswa->tmpLahir }}, @endif {{ $mahasiswa->tglLahir }}</td></tr>
						<tr><th>Kewarganegaraan</th><td colspan="5">{{ config('custom.pilihan.statusWrgNgr')[$mahasiswa -> statusWrgNgr] }} {{ $mahasiswa -> kewarganegaraan -> nama }}</td></tr>
						<tr><th>Status Sipil</th><td colspan="5">{{ config('custom.pilihan.statusSipil')[$mahasiswa -> statusSipil] }}</td></tr>
						<tr><th>Alamat</th><td colspan="5">{{ $alamat }}</td></tr>
						<tr><th>Telepon</th><td>{{ $mahasiswa->telp }}</td><th>HP</th><td colspan="3">{{ $mahasiswa -> hp }}</td></tr>
						<tr><th>Email</th><td colspan="5">{{ $mahasiswa->email }}</td></tr>
							<tr>
								<th>Penerima KPS?</th>
							<td>
							@if($mahasiswa -> kps == 'Y')
							Ya
							@else
							Tidak
							@endif
							</td>
							<th>No. KPS</th><td>{{ $mahasiswa -> noKps }}</td></tr>
							</tbody>
					</table>
				</div>
			<div class="tab-pane" id="ortu">
					<table class="table table-bordered table-striped">
						<tbody>
							<tr><th width="18%">AYAH</th><td> {{ $mahasiswa -> namaAyah }}</td><th>IBU</th><td>{{ $mahasiswa->namaIbu }}</td></tr>
							<tr><th>NIK</th><td>{{ $mahasiswa->NIKAyah }}</td><th>NIK</th><td>{{ $mahasiswa->NIKIbu }}</td></tr>
							<tr><th>Tanggal Lahir</th><td>{{ $mahasiswa->tglLahirAyah }}</td><th>Tanggal Lahir</th><td>{{ $mahasiswa->tglLahirIbu }}</td></tr>
							<tr>
								<th>Pendidikan</th><td>{{ config('custom.pilihan.pendidikanOrtu')[$mahasiswa -> pendidikanAyah] }}</td>
								<th>Pendidikan</th><td>{{ config('custom.pilihan.pendidikanOrtu')[$mahasiswa -> pendidikanIbu] }}</td>									
							</tr>
							<tr>
								<th>Pekerjaan</th><td>{{ config('custom.pilihan.pekerjaanOrtu')[$mahasiswa -> pekerjaanAyah] }}</td>
								<th>Pekerjaan</th><td>{{ config('custom.pilihan.pekerjaanOrtu')[$mahasiswa -> pekerjaanIbu] }}</td>									
							</tr>
						<tr>
						<th>Penghasilan</th><td>{{ config('custom.pilihan.penghasilanOrtu')[$mahasiswa -> penghasilanAyah] }}</td>
						<th>Penghasilan</th><td>{{ config('custom.pilihan.penghasilanOrtu')[$mahasiswa -> penghasilanIbu] }}</td>									
						</tr>
						</tbody>
						</table>							
						</div>
			<div class="tab-pane" id="wali">
					<table class="table table-bordered table-striped">
						<tbody>
							<tr>
							<th width="15%">NIK</th><td colspan="5">{{ $mahasiswa->NIKWali ?? "_"}}</td>
							</tr>
							<tr>
							<th>Nama</th><td colspan="5">{{ $mahasiswa->namaWali ?? "_"}}</td>
							</tr>
							<tr>
							<th>Tanggal Lahir</th><td colspan="5">{{ $mahasiswa->tglLahirWali ?? "_"}}</td>
							</tr>
							<tr>
							<th>Pendidikan</th><td colspan="5">{{ config('custom.pilihan.pendidikanOrtu')[$mahasiswa -> pendidikanWali] }}</td>	
							</tr>
							<tr>
							<th>Pekerjaan</th><td colspan="5">{{ config('custom.pilihan.pekerjaanOrtu')[$mahasiswa -> pekerjaanWali] }}</td>								
							</tr>
							<tr>
							<th>Penghasilan</th><td colspan="5">{{ config('custom.pilihan.penghasilanOrtu')[$mahasiswa -> penghasilanWali] }}</td>
							</tr>
						</tbody>
						</table>
						</div>
			<div class="tab-pane" id="akademik">
					<table class="table table-bordered table-striped">
						<tbody>
							<tr>
							<tr><th width="15%">NIM</th><td>{{ $mahasiswa->NIM }}</td>
							<th>NIRM</th><td colspan="5">{{ $mahasiswa->NIRM }}</td>
							</tr>
							<tr>
							<th>NIRL 1</th><td>{{ $mahasiswa->NIRL1 }}</td>
							<th>NIRL 2</th><td colspan="5">{{ $mahasiswa->NIRL2 }}</td>
							</tr>
							<tr>
							<th>PRODI</th><td colspan="5">{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }}</td>
							</tr>
							<tr>
							<th>Program Kelas</th><td colspan="5">{{ $mahasiswa -> kelas -> nama }} / <span class="label label-warning">{{ config('custom.pilihan.jenisPembayaran')[$mahasiswa -> jenisPembayaran] }}</td>
							</tr>
							<tr>
							<th>Semester</th><td colspan="5">{{ $mahasiswa->semesterMhs }}  <strong>/</strong> 
							@if($mahasiswa -> statusMhs == 1)
							<span class="label label-success">{{ config('custom.pilihan.statusMhs')[$mahasiswa -> statusMhs] }}</span>
							@else
							<span class="label label-default">{{ config('custom.pilihan.statusMhs')[$mahasiswa -> statusMhs] }}</span>
							@endif
							
							</tr>
							<tr>
							<th>Dosen Wali</th><td colspan="5">{{ $mahasiswa -> dosenwali -> nama }}</td>
							</tr>
							<tr>
							<th>Mulai Masuk</th><td width="25%">{{ $mahasiswa -> tglMasuk ?? '' }} </td> 
							<th>Tahun Akademik</th><td colspan="5"> {{ $tapel -> nama ?? '' }}</td>
							</tr>
							<tr>
							<th>Jenis Daftar</th><td>{{ config('custom.pilihan.jenisPendaftaran')[$mahasiswa -> jenisPendaftaran] }}</td>
							<th>Jalur Masuk</th><td colspan="5">{{ config('custom.pilihan.jalurMasuk')[$mahasiswa -> jalurMasuk] }}</td>
							</tr>
							<tr>
							<th>Pendidikan Sebelumnya</th><td>{{ $mahasiswa -> thSMTA ?? '-' }}, Jurusan {{ $mahasiswa -> jurSMTA ?? '-' }}</td>
							<th>NISN</th><td colspan="5">{{ $mahasiswa -> NISN ?? '_'}}</td>
							</tr>
							<tr><th>Judul Skripsi</th><td colspan="5">{{ $mahasiswa -> skripsi -> judul ?? '-' }}</td></tr>
							<tr><th>SK Yudisium</th><td>@if(isset($mahasiswa -> wisuda))
							@if($mahasiswa -> wisuda -> SKYudisium != '')
							{{ $mahasiswa -> wisuda -> SKYudisium }}, <strong>Tanggal:</strong> {{ $mahasiswa -> wisuda -> tglSKYudisium }}
							@else
							- 
							@endif
							@endif</td><th width="10%">No. Ijasah</th><td width="20%">@if($mahasiswa -> noIjazah != ''){{ $mahasiswa -> noIjazah}}, <strong>Tanggal:</strong> {{ $mahasiswa -> tglIjazah  ?? '-'}}@else - @endif</td><th width="10%">Tgl. Keluar</th><td>{{ $mahasiswa -> tglKeluar  ?? '-' }}</td></tr>
						</tbody>
					</table>						
				</div>
			</div>			
		</div>				
	</div>	
</div>			
@endsection	